<?php

define( CODE_CHAR, 10000 );

class Token {
	public $type;
	public $value;

	public function __construct($token) {
		if (is_array($token)) {
			$this->type  = $token[0];
			$this->value = $token[1];
			$this->line  = $token[2];
		} else {
			$this->type  = CODE_CHAR;
			$this->value = $token;
			$this->line  = '?';
		}
	}
}

class TokenStream {
	public function __construct($tokens) {
		$this->tokens = $tokens;
		$this->index  = -1;
		$this->withCodeOnly = false;
	}
	
	public function withCodeOnly($enabled) {
		$this->withCodeOnly = $enabled;
	}
	
	public function getNext() {
		$tokenObj = null;
		while (true) {
			$this->index++;
			if (!isset($this->tokens[$this->index])) {
				return null;
			}
			$tokenObj = new Token($this->tokens[$this->index]);
			if ($this->isTokenDisabled($tokenObj)) {
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
			if (!isset($this->tokens[$index])) {
				return null;
			}
			$tokenObj = new Token($this->tokens[$index]);
			if ($this->isTokenDisabled($tokenObj)) {
				$index++;
				continue;
			}
			break;
		}
		return $tokenObj;
	}
	
	private function isTokenDisabled($token) {
		return ($token->type == T_WHITESPACE || $token->type == T_COMMENT) && $this->withCodeOnly;
	}

	private $index;
	private $tokens;
}
