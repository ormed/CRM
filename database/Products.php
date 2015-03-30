<?php
@session_start();

class Products {

    /*
     * function to check if form was submitted ok
     * return errors if found any
     */
    public static function testNewProduct() {
        $err = '';
        if ((empty($_POST['desc'])) || (empty($_POST['quantity']))) {
            $err = "Please fill in all the fields";
        }
        return $err;
    }
    
    public static function insertProduct() {
    	$db = new Database();
        $q = "INSERT INTO PRODUCTS(DESCRIPTION) VALUES (:cdesc)";
        $stid = $db->parseQuery($q);
        oci_bind_by_name($stid, ':cdesc', $_POST['desc']);
        $r = oci_execute($stid);  // executes and commits
        
        $product = Products::getProductId($_POST['desc']);
        $prod_id = $product[0]['P_ID'];
        
        $q2 = "INSERT INTO INVENTORY(P_ID, QUANTITY) VALUES (:p_id, :quantity)";
        $stid2 = $db->parseQuery($q2);
        oci_bind_by_name($stid2, ':p_id', $prod_id);
        oci_bind_by_name($stid2, ':quantity', $_POST['quantity']);
        $p = oci_execute($stid2);  // executes and commits
        
        return $r;
    }
    
    public static function getProductId($desc) {
    	$db = new Database();
    	$q = "SELECT p_id FROM products WHERE description='{$desc}'";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result;
    	} else {
    		return FALSE;
    	}
    }
}
