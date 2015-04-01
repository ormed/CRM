<?php
@session_start();

include_once 'database/Database.php';
include_once 'database/Inventory.php';

class Products {

    /*
     * function to check if form was submitted ok
     * return errors if found any
     */
    public static function testNewProduct() {
        $err = '';
        if ((empty($_POST['desc'])) || (empty($_POST['price'])) || (empty($_POST['quantity']))) {
            $err = "Please fill in all the fields";
        }
        return $err;
    }
    
    public static function getProduct($desc) {
    	$db = new Database();
    	$q = "SELECT * FROM products WHERE description='{$desc}'";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result;
    	} else {
    		return FALSE;
    	}
    }
    
    /*
     * insert new product
     * return FALSE if any error in inserting
     */
    public static function insertProduct() {
    	$db = new Database();
    	$q = "merge into products d using (SELECT :cdesc description, :cprice price from dual) s ON (d.description = s.description) WHEN MATCHED THEN UPDATE SET d.price = s.price WHEN NOT MATCHED THEN INSERT (description, price) VALUES (s.description, s.price)";
    	$stid = $db->parseQuery($q);
    	oci_bind_by_name($stid, ':cdesc', $_POST['desc']);
    	oci_bind_by_name($stid, ':cprice', $_POST['price']);
    	$r = oci_execute($stid);  // executes and commits
    	
    	// Update inventory
    	$product = Products::getProductId($_POST['desc']);
    	$inv = Inventory::insertToInventory($product, $_POST['quantity']);
    	return $r*$inv; // if something goes wrong returns FALSE
    }
    
    public static function getProductId($desc) {
    	$db = new Database();
    	$q = "select p_id from products where description='{$desc}'";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result[0]['P_ID'];
    	} else {
    		return FALSE;
    	}
    }
    
    public static function getProductMaxQuantity($desc) {
    	$db = new Database();
    	$q = "select quantity from (select p.p_id, p.description, p.price, i.quantity from products p, inventory i where p.p_id = i.p_id order by p.p_id) where description='{$desc}'";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result[0]['QUANTITY'];
    	} else {
    		return FALSE;
    	}
    }
}
