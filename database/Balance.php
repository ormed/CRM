<?php
@session_start();

include_once 'database/Database.php';

class Balance {

    /*
     * insert new invoice details
     */
    public static function insertBalance() {
        $db = new Database();
        
        $move_date = $_POST['order_date'];
        $i = 0;
        while(isset($_POST['quantity_'.$i])) {
        	if($_POST['quantity_'.$i] != 0) {
        		$q = "insert into balance (move_date, p_id, quantity, essence) values (to_date(:cmove_date, 'dd/mm/yyyy'), :cp_id, :cquantity, 'Credit')";
		        $stid = $db->parseQuery($q);
		        oci_bind_by_name($stid, ':cmove_date', $move_date);
		        oci_bind_by_name($stid, ':cp_id', $_POST['p_id_'.$i]);
		        oci_bind_by_name($stid, ':cquantity', $_POST['quantity_'.$i]);
		        oci_execute($stid);  // executes and commits
        	}
        	$i++;
        }
        
    }
    
    public static function editBalance() {
    	
    }
    
    public static function deleteBalance($move_id) {
    	$db = new Database();
    	// Delete Balance 
    	$q = "delete from balance where (move_id = :cmove_id)";
    	$stid = $db->parseQuery($q);
    	oci_bind_by_name($stid, ':cinvoice_id', $move_id);
    	oci_execute($stid); // delete balance
    }
    
    public static function getBalance() {
    	$db = new Database();
    	$q = "select * from balance order by move_id";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
    public static function getBalanceDate($move_id) {
    	$db = new Database();
    	$q = "select TO_CHAR(MOVE_DATE, 'dd/mm/yyyy') as MOVE_DATE from balance where move_id = '{$move_id}'";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
    public static function getTotalBalance($move_id) {
    	$db = new Database();
    	$q = "select sum(credit2) as total from (select a.move_id, b.move_id, 
    			(a.quantity*i.price) as credit1 , (b.quantity*j.price) as credit2 
    			from balance a, balance b, products i, products j
    			where(a.move_id >= b.move_id and a.p_id = i.p_id and b.p_id = j.p_id and a.move_id = '{$move_id}'))";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
    
}
