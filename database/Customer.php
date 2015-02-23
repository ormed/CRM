<?php
@session_start();

include_once 'database/Database.php';

class Customer {

    /**
     * get customer details
     * @param $id - the customer number
     * @return false if no customer found
     */
    public static function getCustomer($id) {
        $db = new Database();
        $q = "SELECT * FROM customers WHERE cust_id='{$id}'";
        $result = $db->createQuery($q);
        if (count($result) > 0) {
            return $result;
        } else {
            return FALSE;
        }
    }

    /**
     * insert new customer details
     * @param $first_name, $last_name
     */
    public static function insertCustomer($first_name, $last_name) {
        $db = new Database();
        $q = "INSERT INTO customers(FIRST_NAME, LAST_NAME) VALUES (:cfirst_name, :clast_name)";
        $stid = $db->parseQuery($q);
        oci_bind_by_name($stid, ':cfirst_name', $first_name);
        oci_bind_by_name($stid, ':clast_name', $last_name);
        $r = oci_execute($stid);  // executes and commits
        return $r;
    }
    
    /*public static function updateCustomer($id, $first_name, $last_name) {
    	$db = new Database();
    	$q = "UPDATE customers SET first_name = '{$first_name}', last_name = '{$last_name}', WHERE cust_id = {$id}";
    	$db->createQuery($q);
    }*/
    
    /*
     * function to check if form was submitted ok
     * return errors if found any
     */
    public static function testNewCustomer() {
    	$err = '';
    	if ((empty($_POST['first_name'])) || (empty($_POST['last_name']))) {
    		$err = "Please fill in all the form";
    	} else {
    		$string_exp = "/^[A-Za-z .'-]+$/";
    		if (!preg_match($string_exp, $_POST['first_name'])) {
    			$err = 'The First Name you entered does not appear to be valid.';
    			return $err;
    		}
    		if (!preg_match($string_exp, $_POST['last_name'])) {
    			$err = 'The Last Name you entered does not appear to be valid.';
    			return $err;
    		}
    	}
    	return $err;
    }

}
