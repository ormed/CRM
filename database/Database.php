<?php
class Database {
	private $_username = 'HR';
	private $_password = 'oracle';
	private $_oracle_sid = 'localhost:1521/xe';
	protected $_query;
	protected $_dbh;

	// contructor for creating a connection to the database
	public function __construct() {
		$this->_dbh = oci_connect ( $this->_username, $this->_password, $this->_oracle_sid );
		if ($this->_dbh) {
		}
		else {
			$err = oci_error ();
			debug("Connection failed: " . $err);
			trigger_error ( htmlentities ( $err ['message'], ENT_QUOTES ), E_USER_ERROR );

		}
	}
	public function __destruct() {
		oci_close($this->_dbh);
		// close connection
	}
	
	// execute the query and return result
	public function createQuery($q) {
		$stid = oci_parse($this->_dbh, $q);
		oci_execute($stid);
		// get results and limit it
		$result = array ();
		while (($row = oci_fetch_array($stid, OCI_BOTH)) != false) {
			array_push ( $result, $row );
		}
		return $result;
	}
}
