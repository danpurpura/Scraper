<?php

/**
 * Scraper
 *
 * Performs multiple regular expression pattern matching on a data string.
 */
class Scraper {

    // data to process
    protected $data;

    // array of regex strings to apply to the data
	protected $patterns = array();

    // when set, performs a preg_match_all
	protected $all = false;

	/**
	 * Constructor
	 *
	 * @param string - optional; data to be processed
	 */
	public function __construct($data = null) {
		if (isset($data)) {
			$this->setData($data);
		}
	}

	/**
	 * all() - enables all pattern matching
	 *
	 * @param boolean - optional; defaults to true
	 *
	 * @return $this
	 */
	public function all($enable = true) {
		$this->all = $enable;
		return $this;
	}

	/**
	 * addPattern() - add regex pattern
	 *
	 * @param string - regex pattern
	 * @param string - optional; name of pattern;
     *                 can contain subkeys (comma separated i.e. 'one,two,three')
	 *
	 * @return $this
	 */
	public function addPattern($aString, $key = null) {
        if (isset($key)) {
            $this->patterns[$key] = $aString;
        } else {
            $this->patterns[] = $aString;
        }
		return $this;
	}

	/**
	 * setData() - sets the data to be processed
	 *
	 * @param string
	 *
	 * @return $this
	 */
	public function setData($data) {
		$this->data = $data;
		return $this;
	}

	/**
	 * getData() - returns the data to be processed
	 *
	 * @return string
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * process() - applies to the patterns to the data
     *
	 * @return array - results (matches); auto cleaned
	 */
	public function process() {
		$data = $this->getData();
		$results = array();
		foreach((array)$this->patterns as $key => $pattern) {
			$matches = $this->match($pattern, $data);

			if (is_string($key)) {
                $matches = $this->formatMatches($key, $matches);
				//$matches = array($key => $matches);
                
			}

			$results = array_merge($results, $matches);

		}
		return $this->clean($results);
	}

	/**
	 * formatMatches() - format the given key and matches
     *
     * - default use is, array(key => captured pattern)
     * - if key contains sub keys, returns:
     *    array(sub key 1 => captured pattern 1, ..., sub key n => captured pattern n)
	 *
	 * @return array - results
	 */
    public function formatMatches($key, $matches) {
        // split our key into parts
        $key_parts = explode(',', $key);

        // if we have no matches, return null for the key
        if (empty($matches)) {
            return array($key => null);
        }

        $results = array();

        // loop over, and build our results array
        foreach($key_parts as $key_index => $sub_key) {
            if (!$this->all) {
                $results[$sub_key] = $matches[$key_index + 1];
            } else {
                for($i = 0; $i < count($matches[0]); $i++) {
                    $results[$i][$sub_key] = $matches[$key_index + 1][$i];
                }
            }
        }

        return array($results);
    }

	/**
	 * clean() - called after processing; override to perform custom formatting
	 *
	 * @return array - the cleaned results
	 */
	public function clean($results) {
		return $results;
	}

	/**
	 * match() - wrapper for preg_match
	 *
     * @param string - pattern
     * @param string - data
     *
	 * @return array - matches
	 */
	protected function match($pattern, $data) {
		$func = 'preg_match'.($this->all ? '_all' : '');
		$func($pattern, $data, $matches);
		return $matches;
	}

    /**
     * getPregError() - returns last preg error as an English Stirng
     *
     * @return string
     */
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
