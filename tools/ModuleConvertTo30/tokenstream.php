<?php

define( CODE_CHAR, 10000 );

class Token {
	public $type;
	public $value;

	public function __construct($token) {
		if (is_array($token)) {
			$this->type  = $token[0];
			$this->value = $token[1];
		} else {
			$this->type  = CODE_CHAR;
			$this->value = $token;
		}
	}
}

class TokenStream {
	public function __construct($tokens) {
		$this->tokens = $tokens;
		$this->index  = -1;
		$this->withWhiteSpace = true;
	}
	
	public function withWhiteSpace($enabled) {
		$this->withWhiteSpace = $enabled;
	}
	
	public function atEnd() {
		return $this->atEndWithIndex($this->index);
	}
	
	public function getNext() {
		$tokenObj = null;
		while (true) {
			$this->index++;
			if ($this->atEnd()) {
				return null;
			}
			$tokenObj = new Token($this->tokens[$this->index]);
			if ($tokenObj->type == T_WHITESPACE && $this->withWhiteSpace == false) {
				continue;
			}
			break;
		}
		return $tokenObj;
	}

	public function peek($offset) {
		$tokenObj = null;
		$index = $this->index + $offset;
		while (true) {
			if ($this->atEndWithIndex($index)) {
				return null;
			}
			$tokenObj = new Token($this->tokens[$index]);
			if ($tokenObj->type == T_WHITESPACE && $this->withWhiteSpace == false) {
				continue;
			}
			break;
		}
		return $tokenObj;
	}
	
	private function getNextIndex($from, $ignoreWhiteSpace) {
		$index = $this->index;
		if ($index < count($this->tokens)) {
			return $index + 1;
		} else {
			return -1;
		}
	}
	
	private function atEndWithIndex($index) {
		return $index >= (count($this->tokens) - 1);
	}

	private $index;
	private $tokens;
}
