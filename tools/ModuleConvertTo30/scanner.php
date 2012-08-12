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
	
	public function scanModule() {
		$moduleName  = basename($this->rootPath);
		$this->parse("{$moduleName}.php");
	}
	
	private function parse($fileName) {
		$stream = $this->getTokenStream($fileName);

		while (true) {
			$stream->withCodeOnly(false);
			$token = $stream->getNext();
			if ($token == null) {
				break;
			}
			if ($token->type == T_VARIABLE && $token->value == '$command') {
				$stream->withCodeOnly(true);
				if ($stream->peek(1)->value == '->' && $stream->peek(2)->value == 'register') {
					$this->parseCommandRegister($stream);
				}
			}
			else if ($token->type == T_VARIABLE && $token->value == '$event') {
				$stream->withCodeOnly(true);
				if ($stream->peek(1)->value == '->' && $stream->peek(2)->value == 'register') {
					$this->parseEventRegister($stream);
				}
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
	
	private function parseCommandRegister($stream) {
		$stream->getNext(); // ->
		$stream->getNext(); // register
		$stream->getNext(); // (
		$args = array();
		while (true) {
			$value = $stream->getNext()->value;
			if ($value == ')') {
				break;
			}
			if ($value != ',') {
				$args []= trimQuotes($value);
			}
		}
		$stream->getNext(); // ;
		$index = 0;
		$getNextArg = function() use ($args, &$index) {
			$value = ($index < count($args))? $args[$index]: null;
			$index++;
			return $value;
		};
		
		$register['module']        = $getNextArg();
		$register['channels']      = $getNextArg();
		$register['filename']      = $getNextArg();
		$register['command']       = $getNextArg();
		$register['accessLevel']   = $getNextArg();
		$register['description']   = $getNextArg();
		$register['help']          = $getNextArg();
		$register['defaultStatus'] = $getNextArg();
		$this->commands[$register['command']] = $register;
		$this->parseCommandHandlerFile($register['command'], $register['filename']);
	}

	private function parseEventRegister($stream) {
		$stream->getNext(); // ->
		$stream->getNext(); // register
		$stream->getNext(); // (
		$args = array();
		while (true) {
			$value = $stream->getNext()->value;
			if ($value == ')') {
				break;
			}
			if ($value != ',') {
				$args []= trim($value, "\"'");
			}
		}
		$stream->getNext(); // ;
		$index = 0;
		$getNextArg = function() use ($args, &$index) {
			$value = ($index < count($args))? $args[$index]: null;
			$index++;
			return $value;
		};
		$register['module']        = $getNextArg();
		$register['type']          = $getNextArg();
		$register['filename']      = $getNextArg();
		$register['description']   = $getNextArg();
		$register['help']          = $getNextArg();
		$register['defaultStatus'] = $getNextArg();
		$register['contents'] = $this->parseEventHandlerFile($register['filename']);
		$this->events []= $register;
	}
	
	private function parseEventHandlerFile($fileName) {
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
		
		$self = $this;

		$memberVarCallback = function($matches) use ($self) {
			if (!in_array($matches[1], $self->memberVars)) {
				$self->memberVars []= $matches[1];
			}
			return "\$this->$matches[1]";
		};
		$data = preg_replace_callback("/\\\$chatBot->data\\[['\"]([^'\"]+)['\"]\\]/", $memberVarCallback, $data);
		$data = str_replace('$sender', '$eventObj->sender', $data);

		$injectVarCallback = function($matches) use ($self) {
			$varName = lcfirst($matches[1]);
			if (!in_array($varName, $self->injectVars)) {
				$self->injectVars []= $varName;
			}
			return "\$this->$varName->";
		};
		$data = preg_replace_callback("/([a-z0-9_]+)::/i", $injectVarCallback, $data);
		return $data;
	}
	
	private function parseCommandHandlerFile($command, $fileName) {
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
					$expectTokenValue($cToken, '$args');
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
				$this->commandHandlers []= $handler;
			} else if ($token->type == T_OPEN_TAG || $token->type == T_CLOSE_TAG) {
				continue;
			} else if ($token->type == T_ELSE) {
				$readTokensFromBraces($stream, 0);
			} else {
				throw new ScanError("Unknown token ". token_name($token->type) .", ({$token->value}) in $fileName @ line {$token->line}");
			}
		}
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
}

class ScanError extends Exception {
}