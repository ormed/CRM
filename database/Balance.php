<?php
@session_start();

include_once 'database/Database.php';
include_once 'database/Products.php';

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
    
    public static function insertBalanceWithParameters($p_id, $quantity, $essence, $db) {
    	$move_date = date("d/m/Y"); // get current date
    	$q = "insert into balance (move_date, p_id, quantity, essence) values (to_date(:cmove_date, 'dd/mm/yyyy'), :cp_id, :cquantity, :cessence)";
    	$stid = $db->parseQuery($q);
    	oci_bind_by_name($stid, ':cmove_date', $move_date);
    	oci_bind_by_name($stid, ':cp_id', $p_id);
    	oci_bind_by_name($stid, ':cquantity', $quantity);
    	oci_bind_by_name($stid, ':cessence', $essence);
    	oci_execute($stid);  // executes and commits
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
    	$q = "select * from balance order by move_date, move_id";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
    public static function getAllBalanceByDate($start, $end) {
    	$db = new Database();
    	// Get the right date format to insert
    	$start = date("d/m/Y", strtotime($start));
    	$end = date("d/m/Y", strtotime($end));
    	
    	$q = "select * from balance where move_date between to_date('{$start}', 'dd/mm/yyyy') and to_date('{$end}', 'dd/mm/yyyy') order by move_date, move_id";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
    public static function getBalanceDate($move_id) {
    	$db = new Database();
    	$q = "select TO_CHAR(MOVE_DATE, 'dd/mm/yyyy') as MOVE_DATE from balance where move_id = '{$move_id}'";
    	$result = $db->createQuery($q);
    	return $result;
    }
    

    /**
     * Get Balance by start and end dates
     * @param Date $start_date
     * @param Date $end_date
     * @return array of moves:
     */
    public static function getTotalBalance($move_id) {
    	$db = new Database();
    	$q = "select sum(multi*total) as total from
		    (SELECT (CASE WHEN essence = 'Credit' THEN 1 ELSE -1 END) AS multi, total
		    from (	select a.essence, (a.quantity*p.price) as total from balance a, balance b, products p where a.move_date<b.move_date and p.p_id = a.p_id  and b.move_id='{$move_id}'
		    		UNION
		    		select a.essence, (a.quantity*p.price) as total from balance a, balance b, products p where a.move_date=b.move_date and p.p_id = a.p_id  and b.move_id='{$move_id}' and a.move_id <= b.move_id
		    	)
    		)";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
    public static function getTotalBalanceByDate($start, $end, $move_id) {
    	$db = new Database();
    	// Get the right date format to insert
    	$start = date("d/m/Y", strtotime($start));
    	$end = date("d/m/Y", strtotime($end));
    	
    	$q = "select sum(multi*total) as total from
    	(SELECT (CASE WHEN essence = 'Credit' THEN 1 ELSE -1 END) AS multi, total
    	from (	select a.essence, (a.quantity*p.price) as total from balance a, balance b, products p where a.move_date<b.move_date and p.p_id = a.p_id  and b.move_id='{$move_id}' and a.move_date between to_date('{$start}', 'dd/mm/yyyy') and to_date('{$end}', 'dd/mm/yyyy')
    	UNION
    	select a.essence, (a.quantity*p.price) as total from balance a, balance b, products p where a.move_date=b.move_date and p.p_id = a.p_id  and b.move_id='{$move_id}' and a.move_id <= b.move_id and a.move_date between to_date('{$start}', 'dd/mm/yyyy') and to_date('{$end}', 'dd/mm/yyyy')
    	)
    	)";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
//     $q = "select sum(multi*total) as total from
//     (SELECT (CASE WHEN essence = 'Credit' THEN 1 ELSE -1 END) AS multi, total
//     from (select a.essence, (a.quantity*p.price) as total from balance a, balance b, products p where a.move_date<=b.move_date and p.p_id = a.p_id and b.move_id='{$move_id}'
//     ))";
    
    
    
}
