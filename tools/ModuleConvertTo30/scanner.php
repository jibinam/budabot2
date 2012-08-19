<?php

class ModuleScanner {
	public $commands = array();
	public $commandHandlers = array();
	public $events = array();
	public $memberVars = array();
	public $injectVars = array();
	public $hasLogger = false;

	public function __construct($pathToModule) {
		$this->rootPath = $pathToModule;
	}
	
	public function scanEventHandlerFile($fileName) {
		$tokens = array();
		$stream = $this->getTokenStream($fileName);

		while (true) {
			$stream->withCodeOnly(false);
			$token = $stream->getNext();
			if ($token == null) {
				break;
			}
			if ($token->type == T_VARIABLE && $token->value == '$chatbot') {
//				$stream->withCodeOnly(true);
//				if ($stream->peek(1)->value == '->' && $stream->peek(2)->value == 'data') {
//					$tokens []= new Token(array(T_VARIABLE, '$this'));
//					$tokens []= new Token(array(T_VARIABLE, '->'));
//				}
			}
			$tokens []= $token;
		}
		$tokens = $this->trimTokens($tokens, array(T_WHITESPACE, T_OPEN_TAG, T_CLOSE_TAG));
		$data = '';
		foreach ($tokens as $token) {
			$data .= $token->value;
		}
		
		$data = str_replace('$sender', '$eventObj->sender', $data);
		$data = $this->chatbotDataToMemberVars($data);
		$data = $this->registryGetInstanceToInjects($data);
		$data = $this->legacyLoggerToInject($data);
		$data = $this->staticCallsToInjects($data);
		$data = $this->globalVarsToInjects($data);
		return $data;
	}
	
	public function scanCommandHandlerFile($command, $fileName) {
		$variables = array();
		$stream = $this->getTokenStream($fileName);
		
		// throws exception if given token's value is not correct
		$expectTokenValue = function($token, $expectedValue) use ($fileName) {
			if ($token->value != $expectedValue) {
				throw new ScanError("Unexpected token value '{$token->value}' (expected: $expectedValue)) in $fileName @ line {$token->line}");
			}
		};

		while (true) {
			$stream->withCodeOnly(true);
			$token = $stream->getNext();
			if ($token == null) {
				break;
			}
			// look for top-level if ( ... ) { ... }, parse regexp matchers
			// from condition and get contents from within curly braces
			if ($token->type == T_IF) {
				$stream->withCodeOnly(false);
				$ifStatement = $this->scanIfStatement($stream);
				if ($ifStatement['condition'][0]->value == 'preg_match') {
					// parse the condition into matchers
					$matcherVariables = array();
					$matchers = array();
					$conditionStream = new TokenStream($ifStatement['condition']);
					while (true) {
						$function = $this->scanFunctionCall($conditionStream);
						if ($function['name'] != 'preg_match' || $function['params'][1] != '$message') {
							throw new ScanError('Expected preg_match(...), got ' . $function['name'] . '(' . implode(', ', $function['params']) . ')');
						}

						$matchers []= trimQuotes($function['params'][0]);
						if (count($function['params']) <= 3) {
							$matcherVariables []= $function['params'][2];
						}
						$token = $conditionStream->getNext();
						if ($token == null) {
							break;
						}
						// on boolean OR scan another preg_match() call
						if ($token->type == T_BOOLEAN_OR) { // ||
							continue;
						}
					}
					if ($conditionStream->getNext() != null) {
						throw new ScanError("Failed to parse condition in $fileName");
					}
					
					// collect data and add it to commandHandlers
					$handler = new StdClass();
					$handler->matchers = $matchers;
					$handler->command = $command;
					$handler->contents = '';
					foreach ($ifStatement['codeblock'] as $token) {
						$handler->contents .= $token->value;
					}
					// replace variables which were defined outside of the top level
					// if-checks with their contents
					foreach ($variables as $name => $value) {
						$handler->contents = str_replace($name, $value, $handler->contents);
					}
					$handler->contents = $this->chatbotDataToMemberVars($handler->contents);
					$handler->contents = $this->registryGetInstanceToInjects($handler->contents);
					$handler->contents = $this->legacyLoggerToInject($handler->contents);
					$handler->contents = $this->staticCallsToInjects($handler->contents);
					$handler->contents = $this->globalVarsToInjects($handler->contents);
					foreach ($matcherVariables as $var) {
						$handler->contents = str_replace($var, '$args', $handler->contents);
					}

					$this->commandHandlers []= $handler;
				} else if ($ifStatement['condition'][0]->value == '!' && $ifStatement['condition'][1]->value == 'function_exists') {
					// codeblock contains function definition, ignore this
				}
			} else if ($token->type == T_OPEN_TAG || $token->type == T_CLOSE_TAG) {
				continue;
			} else if ($token->type == T_ELSE) {
				$stream->withCodeOnly(true);
				if ($stream->peek(1)->type != T_IF) {
					$this->scanTokensFromBraces($stream, 0);
				}
			} else if ($token->type == T_VARIABLE) {
				$variable = $token->value;

				$stream->withCodeOnly(true);
				$token = $stream->getNext();
				$expectTokenValue($token, '=');
				$value = '';
				do {
					$token = $stream->getNext();
					$value .= $token->value;
				} while($token->value != ';');
				$variables[$variable] = substr($value, 0, -1);
			} else if ($token->type == T_INCLUDE) {
				$stream->withCodeOnly(true);
				$stream->getNext(); // filename
				$stream->getNext(); // ;
			} else {
				throw new ScanError("Unknown token ". token_name($token->type) .", ({$token->value}) in $fileName @ line {$token->line}");
			}
		}
	}

	private function getTokenStream($fileName) {
		$filePath = "{$this->rootPath}/$fileName";
		$contents = file_get_contents($filePath);
		$tokens   = token_get_all($contents);
		$stream   = new TokenStream($tokens);
		return $stream;
	}
	
	private function scanFunctionCall($stream) {
		$results = array(
			'name'   => '',
			'params' => array()
		);
		$stream->withCodeOnlyCallback(function() use ($stream, &$results) {
			// throws exception if given token's value is not correct
			$expectToken = function($token, $expectedValue) {
				if ($token->type != $expectedValue && $token->value != $expectedValue) {
					throw new ScanError("Unexpected token '{$token->type}, {$token->value}' (expected: $expectedValue) while parsing a function");
				}
			};
			$token = $stream->getCurrent();
			if ($token->type != T_STRING) {
				$token = $stream->getNext();
			}
			$expectToken($token, T_STRING);
			$functionName = $token->value;
			$params = array();
			$token = $stream->getNext();
			$expectToken($token, '(');

			while (true) {
				$token = $stream->getNext();
				if ($token->value == ')') {
					break;
				} else if ($token->value != ',') {
					$params []= $token->value;
				}
			}
			$expectToken($token, ')');

			$results['name'] = $functionName;
			$results['params'] = $params;
		});
		return $results;
	}

	private function scanIfStatement($stream) {
		$results = array(
			'condition' => array(),
			'codeblock' => array()
		);
		$stream->withCodeOnlyCallback(function() use ($stream, &$results) {
			// throws exception if given token's value is not correct
			$expectToken = function($token, $expectedValue) {
				if ($token->type != $expectedValue && $token->value != $expectedValue) {
					throw new ScanError("Unexpected token '{$token->type}, {$token->value}' (expected: $expectedValue) while parsing a function");
				}
			};
			// look for 'if' keyword
			$token = $stream->getCurrent();
			if ($token->type != T_IF) {
				$token = $stream->getNext();
			}
			$expectToken($token, T_IF);

			// read condition from inside parentheses
			$token = $stream->getNext();
			$expectToken($token, '(');
			$parenthesisCount = 1;
			while ($parenthesisCount) {
				$token = $stream->getNext();
				if ($token->value == '(') {
					$parenthesisCount++;
				} else if ($token->value == ')') {
					$parenthesisCount--;
				}
				if ($parenthesisCount == 0) {
					break;
				}
				$results['condition'] []= $token;
			}
			// read handler code from within curly braces
			$token = $stream->getNext();
			$expectToken($token, '{');
		});
		$results['codeblock'] = $this->scanTokensFromBraces($stream, 1);
		$results['codeblock'] = $this->trimTokens($results['codeblock'], array(T_WHITESPACE));
		return $results;
	}

	private function scanTokensFromBraces($stream, $braceCount) {
		$tokens = array();
		do {
			$token = $stream->getNext();
			if ($token->value == '{') {
				$braceCount++;
			} else if ($token->value == '}') {
				$braceCount--;
			}
			if ($braceCount == 0) {
				break;
			}
			$tokens []= $token;
		} while ($braceCount);
		return $tokens;
	}

	private function trimTokens($tokens, $trimmedTypes) {
		while (in_array($tokens[0]->type, $trimmedTypes)) {
			array_shift($tokens);
		}
		while (in_array($tokens[count($tokens)-1]->type, $trimmedTypes)) {
			array_pop($tokens);
		}
		return $tokens;
	}
	
	private function chatbotDataToMemberVars($code) {
		$self = $this;
		$memberVarCallback = function($matches) use ($self) {
			if (!in_array($matches[1], $self->memberVars)) {
				$self->memberVars []= $matches[1];
			}
			return "\$this->$matches[1]";
		};
		return preg_replace_callback("/\\\$chatBot->data\\[['\"]([^'\"]+)['\"]\\]/", $memberVarCallback, $code);
	}

	private function registryGetInstanceToInjects($code) {
		$self = $this;
		$injectVarCallback = function($matches) use ($self) {
			$varName = lcfirst($matches[1]);
			if (!in_array($varName, $self->injectVars)) {
				$self->injectVars []= $varName;
			}
			return "\$this->$varName";
		};
		return preg_replace_callback('/Registry::getInstance\(\'([a-z0-9_]+)\'\)/i', $injectVarCallback, $code);
	}

	private function legacyLoggerToInject($code) {
		$code = str_replace('LegacyLogger::', '$this->logger->', $code, $count);
		$this->hasLogger |= $count > 0;
		return $code;
	}

	private function staticCallsToInjects($code) {
		$self = $this;
		$injectVarCallback = function($matches) use ($self) {
			$varName = lcfirst($matches[1]);
			if (!in_array($varName, $self->injectVars)) {
				$self->injectVars []= $varName;
			}
			return "\$this->$varName->";
		};
		return preg_replace_callback("/([a-z0-9_]+)::/i", $injectVarCallback, $code);
	}
	
	private function globalVarsToInjects($code) {
		$self = $this;
		$injectVarCallback = function($matches) use ($self) {
			$varName = $matches[1];
			if (!in_array($varName, $self->injectVars)) {
				$self->injectVars []= $varName;
			}
			return "\$this->$varName->";
		};
		return preg_replace_callback("/\\\$(chatBot|db|setting|buddylistManager)->/", $injectVarCallback, $code);
	}
}

class ScanError extends Exception {
}