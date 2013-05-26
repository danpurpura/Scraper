<?php

class Scraper {
	
	protected $patterns;
	protected $all = false;
	protected $data;
	
	public function addPattern($aString) {
		$this->patterns[] = $aString;
		return $this;
	}
	
	public function all($enable = true) {
		$this->all = $enable;
		return $this;
	}

	public function setData($data) {
		$this->data = $data;
		return $this;
	}
	
	public function getData() {
		return $this->data;
	}

	public function process() {
		$data = $this->getData();
		$results = array();
		foreach((array)$this->patterns as $key => $pattern) {
			$matches = $this->match($pattern, $data);
			
			if (is_string($key)) {
				$matches = array($key => $matches);
			}
			
			$results = array_merge($results, $matches);

		}
		return $this->clean($results);
	}

	public function clean($results) {
		return $results;
	}

	protected function match($pattern, $data) {
		$func = 'preg_match'.($this->all ? '_all' : '');
		$func($pattern, $data, $matches);
		return $matches;
	}
	
	public function getPregError() {
		$error = preg_last_error();
		
		switch($error) {
			case PREG_NO_ERROR:
				return 'No Error';
			case PREG_INTERNAL_ERROR:
				return 'Internal Error';
			case PREG_BACKTRACK_LIMIT_ERROR:
				return 'Backtrack Limit Error';
			case PREG_RECURSION_LIMIT_ERROR:
				return 'Recursion Limit Error';
			case PREG_BAD_UTF8_ERROR:
				return 'Bad UTF8 Error';
            case PREG_BAD_UTF8_OFFSET_ERROR:
                return 'Bad UTF8 Offset Error';
			default:
				return 'Unknown Error';
		}
	}    
}
