<?php
@session_start();

include_once 'database/Database.php';
include_once 'database/Products.php';

class Inventory {

	/*
	 * Insert or Update inventory
	 * @params: $p_id- the product id. $quantity- quantity of the product.
	 */
	public static function insertToInventory($p_id, $quantity) {
		$db = new Database();
    	$q = "merge into inventory d using (select :cp_id p_id, :cquantity quantity from dual) s ON (d.p_id = s.p_id) WHEN MATCHED THEN UPDATE SET d.quantity = s.quantity WHEN NOT MATCHED THEN INSERT (p_id, quantity) VALUES (s.p_id, s.quantity)";
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
		debug($_POST);
		while(isset($_POST['quantity_'.$i])) {
			$current_quantity = Inventory::getMaxQuantity($_POST['p_id_'.$i]);
			$new_quantity = $current_quantity - $_POST['quantity_'.$i];
			// Update quantity
			$q = "update inventory set quantity = :cquantity where (p_id = :cp_id)";
			$stid = $db->parseQuery($q);
			oci_bind_by_name($stid, ':cquantity', $new_quantity);
			oci_bind_by_name($stid, ':cp_id', $_POST['p_id_'.$i]);
			oci_execute($stid);  // executes and commits
			
			// if new_quantity < 10 -> Make an order to store -> Add Balance move
			if($new_quantity < 10) {
				$result = Products::getProductById($_POST['p_id_'.$i]);
				Balance::insertBalanceWithParameters($_POST['p_id_'.$i], $_SESSION['id'], ($new_quantity + 10), $result[0]['STORE_PRICE'], 'Debit', $db);
			}
			
			$i++;
		}
		
	}
    
}
