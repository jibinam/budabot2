<?php

class ModuleScanner {
	public $commands = array();
	public $commandHandlers = array();
	public $events = array();
	public $memberVars = array();
	public $injectVars = array();

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

		$readTokensFromBraces = function($stream, $braceCount) {
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
				$stream->withCodeOnly(true);
				$token = $stream->getNext();
				$expectTokenValue($token, '(');
				// read condition from inside parentheses
				$parenthesisCount = 1;
				$conditionTokens = array();
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
					$conditionTokens []= $token;
				}
				// parse the condition into matchers
				$cToken = array_shift($conditionTokens);
				$expectTokenValue($cToken, 'preg_match');

				$cToken = array_shift($conditionTokens);
				$expectTokenValue($cToken, '(');

				$regExpString = trimQuotes(array_shift($conditionTokens)->value);

				$cToken = array_shift($conditionTokens);
				$expectTokenValue($cToken, ',');

				$cToken = array_shift($conditionTokens);
				$expectTokenValue($cToken, '$message');

				$cToken = array_shift($conditionTokens);
				if ($cToken->value == ',') {
					$cToken = array_shift($conditionTokens);
					$matcherVariable = $cToken->value;
					$cToken = array_shift($conditionTokens);
				}
				$expectTokenValue($cToken, ')');
				
				if (!empty($conditionTokens)) {
					throw new ScanError("Failed to parse condition in $fileName");
				}
				
				// read handler code from within curly braces
				$token = $stream->getNext();
				$expectTokenValue($token, '{');
				$stream->withCodeOnly(false);
				$handlerTokens = $readTokensFromBraces($stream, 1);
				$handlerTokens = $this->trimTokens($handlerTokens, array(T_WHITESPACE));
				// collect data and add it to commandHandlers
				$handler = new StdClass();
				$handler->matchers = array();
				$handler->matchers []= $regExpString;
				$handler->command = $command;
				$handler->contents = '';
				foreach ($handlerTokens as $token) {
					$handler->contents .= $token->value;
				}
				$handler->contents = $this->chatbotDataToMemberVars($handler->contents);
				$handler->contents = $this->staticCallsToInjects($handler->contents);
				$handler->contents = $this->globalVarsToInjects($handler->contents);
				// replace varibles which were defined outside of the top level
				// if-checks with their contents
				foreach ($variables as $name => $value) {
					$handler->contents = str_replace($name, $value, $handler->contents);
				}
				if (isset($matcherVariable)) {
					$handler->contents = str_replace($matcherVariable, '$args', $handler->contents);
				}

				$this->commandHandlers []= $handler;
			} else if ($token->type == T_OPEN_TAG || $token->type == T_CLOSE_TAG) {
				continue;
			} else if ($token->type == T_ELSE) {
				$stream->withCodeOnly(true);
				if ($stream->peek(1)->type != T_IF) {
					$readTokensFromBraces($stream, 0);
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