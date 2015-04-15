<?php
@session_start();

include_once 'database/Database.php';
include_once 'database/Products.php';

class Inventory {

	/**
	 * Insert or Update inventory
	 * @params: $p_id- the product id. $quantity- quantity of the product.
	 */
	public static function insertToInventory($p_id, $quantity) {
		$db = new Database();
    	$q = "begin insert_inventory(:cp_id, :cquantity); end;";
    	$stid = $db->parseQuery($q);
    	oci_bind_by_name($stid, ':cp_id', $p_id);
    	oci_bind_by_name($stid, ':cquantity', $quantity);
    	$r = oci_execute($stid);  // executes and commits
    	return $r;
	}
	
	public static function getMaxQuantity($p_id) {
		$db = new Database();
		$q = "select quantity from inventory where p_id = '{$p_id}'";
		$result = $db->createQuery($q);
		return $result[0]['QUANTITY'];
	}
	
	public static function reduceQuantity() {
		$db = new Database();
		$i = 0;
		while(isset($_POST['quantity_'.$i])) {
			$current_quantity = Inventory::getMaxQuantity($_POST['p_id_'.$i]);
			$new_quantity = $current_quantity - $_POST['quantity_'.$i];
			// Update quantity
			$q = "begin update_quantity(:cp_id, :cquantity); end;";
			$stid = $db->parseQuery($q);
			oci_bind_by_name($stid, ':cp_id', $_POST['p_id_'.$i]);
			oci_bind_by_name($stid, ':cquantity', $new_quantity);
			oci_execute($stid);  // executes and commits
			
			// if new_quantity < 10 -> Make an order to store -> Add Balance move
			if($new_quantity < 10 && $new_quantity >= 0) {
				$result = Products::getProductById($_POST['p_id_'.$i]);
				Balance::insertBalanceWithParameters($_POST['p_id_'.$i], $_SESSION['id'], ($new_quantity + 10), $result[0]['STORE_PRICE'], 'Debit', $db);
			} elseif($new_quantity < 0) { // Ordered more than in Inventory
				$result = Products::getProductById($_POST['p_id_'.$i]);
				Balance::insertBalanceWithParameters($_POST['p_id_'.$i], $_SESSION['id'], ($new_quantity*-1 + 10), $result[0]['STORE_PRICE'], 'Debit', $db);
			}
			$i++;
		}
	}
	
	public static function increaseQuantity($p_id, $returned_quantity, $db) {
		$current_quantity = Inventory::getMaxQuantity($p_id);
		$new_quantity = $current_quantity + $returned_quantity;

		$q = "begin update_quantity(:cp_id, :cquantity); end;";
		$stid = $db->parseQuery($q);
		oci_bind_by_name($stid, ':cp_id', $p_id);
		oci_bind_by_name($stid, ':cquantity', $new_quantity);
		oci_execute($stid);  // executes and commits
	}
    
}
