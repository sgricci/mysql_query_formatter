<?php
// Define statuses
define('STATUS_NOT_READY', false);
define('STATUS_READY', 1);
define('STATUS_COMPLETE', 2);

class query_formatter
{
	private $_query; // The query to perform formatting on
	private $_stat; // Status variable
	private $_result; // Variable to store the result of the operation
	private $_eol = "\n"; // End of line character
	// Keywords that cause a line break prior to them
	private $_lb_keywords = array('SELECT', 'FROM', 'WHERE', 'INNER JOIN', 'LEFT JOIN', 'GROUP BY', 'ORDER BY', 'AND'); 

	public function __construct($sql = '')
	{
		if (!empty($sql)) {
			$this->_query = $sql;
			$this->_stat = STATUS_READY;
		}
		return true;
	}

	public function query($sql = '')
	{
		if (!empty($sql))
		{
			$this->_query = $sql;
			$this->_stat = STATUS_READY;
			return true;
		}
		return false;
		//TODO: this should throw a warning if it is empty
	}
	private function _set_status($status)
	{
		$this->_stat = $status;
	}

	private function _is_status($status)
	{
		return ($this->_stat == $status);
	}

	public function process_result()
	{
		if ($this->_is_status(STATUS_READY))
		{
			$tmp = $this->_query;
			$tmp .= "\n";
			foreach($this->_lb_keywords as $word)
				$tmp = str_ireplace(' '.$word, $this->_eol.$word, $tmp);
			$this->_set_result($tmp);
		}
	}

	private function _set_result($result = '')
	{
		if (!empty($result)) $this->_set_status(STATUS_COMPLETE);
		$this->_result = $result;
	}

	public function get_result()
	{
		if ($this->_is_status(STATUS_COMPLETE))
			return $this->_result;

		throw new Exception('Formatting has not been done, nothing to run');
		//TODO: this should throw a warning
	}

	public function get_debug_info()
	{
		$debug = array();

		$debug['_query'] = $this->_query;
		$debug['_stat'] = $this->_stat;
		$debug['_result'] = $this->_result;

		return $debug;
	}

	public function __destruct()
	{
		unset($this->_stat);
		unset($this->_query);
		unset($this->_result);
	}
}

?>
