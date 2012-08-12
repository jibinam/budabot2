<?php

class ModuleScanner {
	public $commands = array();
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

		while (!$stream->atEnd()) {
			$stream->withWhiteSpace(true);
			$token = $stream->getNext();
			if ($token->type == T_VARIABLE && $token->value == '$command') {
				$stream->withWhiteSpace(false);
				if ($stream->peek(1)->value == '->' && $stream->peek(2)->value == 'register') {
					$this->parseCommandRegister($stream);
				}
			}
			else if ($token->type == T_VARIABLE && $token->value == '$event') {
				$stream->withWhiteSpace(false);
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
		$foundOpenTag = false;
		$tokens = array();
		$stream = $this->getTokenStream($fileName);

		while (!$stream->atEnd()) {
			$stream->withWhiteSpace(true);
			$token = $stream->getNext();
			if (!$foundOpenTag && $token->type == T_OPEN_TAG && trim($token->value) == '<?php') {
				$foundOpenTag = true;
				continue;
			} else if ($token->type == T_VARIABLE && $token->value == '$chatbot') {
//				$stream->withWhiteSpace(false);
//				if ($stream->peek(1)->value == '->' && $stream->peek(2)->value == 'data') {
//					$tokens []= new Token(array(T_VARIABLE, '$this'));
//					$tokens []= new Token(array(T_VARIABLE, '->'));
//				}
			}
			$tokens []= $token;
		}
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
		$stream->withWhiteSpace(true);
		$braceCount = 0;

		while (!$stream->atEnd()) {
			$token = $stream->getNext();
			// look for top-level if ( ... ) { ... }, parse regexp matchers
			// from condition and get contents from within curly braces
			if ($token->type == T_IF && $braceCount == 0) {
				$stream->withWhiteSpace(false);
				$token = $stream->getNext();
				if ($token->value != '(') {
					continue;
				}
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
				$token = $stream->getNext();
				if ($token->value != '{') {
					var_dump($token);
					continue;
				}
				// read handler code from within curly braces
				$braceCount++;
				$handlerTokens = array();
				$stream->withWhiteSpace(true);
				while ($braceCount) {
					$token = $stream->getNext();
					if ($token->value == '{') {
						$braceCount++;
					} else if ($token->value == '}') {
						$braceCount--;
					}
					if ($braceCount == 0) {
						break;
					}
					$handlerTokens []= $token;
				}
				// debug print condition and handler code
				print "condition: ";
				foreach ($conditionTokens as $token) {
					print $token->value;
				}
				print "\n";				
				print "handler: \n";
				foreach ($handlerTokens as $token) {
					print $token->value;
				}
				print "\n";				
			}
		}
	}
}

