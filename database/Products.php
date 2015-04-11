<?php
@session_start();

include_once 'database/Database.php';
include_once 'database/Inventory.php';

class Products {

    /**
     * Function to check if form was submitted ok
     * @return errors if found any
     */
    public static function testNewProduct($desc, $price, $quantity) {
        $err = '';
        if ((empty($desc)) || (empty($price)) || (empty($quantity))) {
            $err = "Please fill in all the fields";
        }
        return $err;
    }
    
    /**
     * Find the product details by description
     * @param $desc- the product description
     * @return an array of the product if found or FALSE otherwise
     */
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
    
    /**
     * Get all products details
     * @return an array of products
     */
    public static function getAllProducts() {
    	$db = new Database();
    	$q = "select * from products p, inventory i where p.p_id = i.p_id order by p.description";
    	$results = $db->createQuery($q);
    	return $results;
    }
    
    /**
     * insert new product
     * @return FALSE if any error in inserting
     */
    public static function insertProduct($desc, $price, $quantity) {
    	$db = new Database();
    	$q = "merge into products d using (SELECT :cdesc description, :cprice price from dual) s ON (d.description = s.description) WHEN MATCHED THEN UPDATE SET d.price = s.price WHEN NOT MATCHED THEN INSERT (description, price) VALUES (s.description, s.price)";
    	$stid = $db->parseQuery($q);
    	oci_bind_by_name($stid, ':cdesc', $desc);
    	oci_bind_by_name($stid, ':cprice', $price);
    	$r = oci_execute($stid);  // executes and commits
    	
    	// Update inventory
    	$product = Products::getProductId($desc);
    	$inv = Inventory::insertToInventory($product, $quantity);
    	return $r*$inv; // if has error returns FALSE
    }
    
    /**
     * Find the product id by it's description
     * @param $desc- the product description
     * @return the product id if found or FALSE otherwise
     */
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
    
    /**
     * Find the description by the product id
     * @param $p_id- the product id
     * @return a string of the product description if found or FALSE otherwise
     */
    public static function getProductDesc($p_id) {
    	$db = new Database();
    	$q = "select description from products where p_id='{$p_id}'";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result[0]['DESCRIPTION'];
    	} else {
    		return FALSE;
    	}
    }
    
    /**
     * Find the product price by it's id
     * @param $p_id- the product id
     * @return an integer of the product price if found or FALSE otherwise
     */
    public static function getProductPrice($p_id) {
    	$db = new Database();
    	$q = "select price from products where p_id='{$p_id}'";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result[0]['PRICE'];
    	} else {
    		return FALSE;
    	}
    }
    
    public static function reduceQuantity($p_id, $quantity, $db) {
    	$q = "select * from products p, inventory i where i.p_id = p.p_id and p.p_id = '{$p_id}'";
    	$result = $db->createQuery($q);
    	$old_quantity = $result[0]['QUANTITY'];
    	$new_quantity = $old_quantity - $quantity;
    	$q = "UPDATE inventory SET quantity = :cquantity WHERE p_id = :cp_id";
    	$stid = $db->parseQuery($q);
    	oci_bind_by_name($stid, ':cquantity', $new_quantity);
    	oci_bind_by_name($stid, ':cp_id', $p_id);
    	oci_execute($stid);  // executes and commits
    }
    
    /**
     * Find the quantity of a product
     * @param $desc- the product description
     * @return the quantity of the product in the inventory if found or FALSE otherwise
     */
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
    
    /**
     * Get product details by description and id
     * @param String $desc
     * @param int $p_id
     * @return array of products:
     */
    public static function getProductDetails($desc, $p_id) {
    	$db = new Database();
    	$q = "select p.p_id, p.description, p.price, i.quantity from products p, inventory i where (p.p_id = i.p_id) and (p.description='{$desc}' or p.p_id='{$p_id}')";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
}
