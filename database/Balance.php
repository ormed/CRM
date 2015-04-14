<?php
@session_start();

include_once 'database/Database.php';
include_once 'database/Products.php';

class Balance {

    /**
     * insert new Balance move
     */
    public static function insertBalance() {
        $db = new Database();
        $move_date = $_POST['order_date'];
        $i = 0;
        while(isset($_POST['quantity_'.$i])) {
        	if($_POST['quantity_'.$i] != 0) {
        		$q = "insert into balance (move_date, user_id, p_id, quantity, essence) values (to_date(:cmove_date, 'dd/mm/yyyy'), :cuser_id, :cp_id, :cquantity, 'Credit')";
		        $stid = $db->parseQuery($q);
		        oci_bind_by_name($stid, ':cmove_date', $move_date);
		        oci_bind_by_name($stid, ':cuser_id', $_SESSION['id']);
		        oci_bind_by_name($stid, ':cp_id', $_POST['p_id_'.$i]);
		        oci_bind_by_name($stid, ':cquantity', $_POST['quantity_'.$i]);
		        oci_execute($stid);  // executes and commits
        	}
        	$i++;
        }
        
    }
    
    /**
     * Add new Balance move
     * @param int $p_id
     * @param int $quantity
     * @param double $price
     * @param String(Credit/Debit) $essence
     * @param Database $db
     */
    public static function insertBalanceWithParameters($p_id, $user_id, $quantity, $price, $essence, $db) {
    	$move_date = date("d/m/Y"); // get current date
    	$q = "insert into balance (move_date, user_id, p_id, quantity, price, essence) values (to_date(:cmove_date, 'dd/mm/yyyy'), :cuser_id, :cp_id, :cquantity, :cprice, :cessence)";
    	$stid = $db->parseQuery($q);
    	oci_bind_by_name($stid, ':cmove_date', $move_date);
    	oci_bind_by_name($stid, ':cuser_id', $user_id);
    	oci_bind_by_name($stid, ':cp_id', $p_id);
    	oci_bind_by_name($stid, ':cquantity', $quantity);
    	oci_bind_by_name($stid, ':cprice', $price);
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
    
    public static function getBalance($db) {
    	$q = "select * from balance order by move_date, move_id";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
    public static function getAllBalanceByDate($start, $end, $db) {
    	// Get the right date format to insert
    	$start = date("d/m/Y", strtotime($start));
    	$end = date("d/m/Y", strtotime($end));
    	
    	$q = "select * from balance where move_date between to_date('{$start}', 'dd/mm/yyyy') and to_date('{$end}', 'dd/mm/yyyy') order by move_date, move_id";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
    public static function getBalanceDate($move_id, $db) {
    	$q = "select TO_CHAR(MOVE_DATE, 'dd/mm/yyyy') as MOVE_DATE from balance where move_id = '{$move_id}'";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
    /**
     * Get Total balance for a move
     * @param int $move_id
     * @param Database $db
     * @return array of moves:
     */
    public static function getTotalBalance($move_id, $db) {
    	$q = "select sum(multi*total) as total from
    	(SELECT (CASE WHEN essence = 'Credit' THEN 1 ELSE -1 END) AS multi, total
    	from (select a.essence, (a.quantity*a.price) as total from balance a, balance b where a.move_date<b.move_date and b.move_id='{$move_id}'
    	UNION
    	select a.essence, (a.quantity*a.price) as total from balance a, balance b where a.move_date=b.move_date and b.move_id='{$move_id}' and a.move_id <= b.move_id
    	)
    	)";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
    /**
     * Get Balance by start and end dates
     * @param Date $start_date
     * @param Date $end_date
     * @return array of moves:
     */
    public static function getTotalBalanceByDate($start, $end, $move_id, $db) {
    	// Get the right date format to insert
    	$start = date("d/m/Y", strtotime($start));
    	$end = date("d/m/Y", strtotime($end));
    	
    	$q = "select sum(multi*total) as total from
    	(SELECT (CASE WHEN essence = 'Credit' THEN 1 ELSE -1 END) AS multi, total
    	from (	select a.essence, (a.quantity*a.price) as total from balance a, balance b where a.move_date<b.move_date and b.move_id='{$move_id}' and a.move_date between to_date('{$start}', 'dd/mm/yyyy') and to_date('{$end}', 'dd/mm/yyyy')
    	UNION
    	select a.essence, (a.quantity*a.price) as total from balance a, balance b where a.move_date=b.move_date and b.move_id='{$move_id}' and a.move_id <= b.move_id and a.move_date between to_date('{$start}', 'dd/mm/yyyy') and to_date('{$end}', 'dd/mm/yyyy')
    	)
    	)";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
    /**
     * Get Total balance for a move
     * @param int $move_id
     * @param Database $db
     * @return array of moves:
     */
    public static function getBalanceBySeller($user_id, $db) {
    	$q = "select * from balance where user_id='{$user_id}' order by move_date, move_id";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
    /**
     * Get Total balance for a move by seller
     * @param int $user_id
     * @param int $move_id
     * @param Database $db
     * @return array of moves:
     */
    public static function getTotalBalanceBySeller($user_id, $move_id, $db) { 
    	$q = "select sum(multi*total) as total from
    	(SELECT (CASE WHEN essence = 'Credit' THEN 1 ELSE -1 END) AS multi, total
    	from (	select a.essence, (a.quantity*a.price) as total from balance a, balance b where a.move_date<b.move_date and b.move_id='{$move_id}' and a.user_id='{$user_id}'
    	UNION
    	select a.essence, (a.quantity*a.price) as total from balance a, balance b where a.move_date=b.move_date and b.move_id='{$move_id}' and a.move_id <= b.move_id and a.user_id='{$user_id}'
    	)
    	)";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
    
    /**
     * Insert Balances by order id
     * @param int $order_id
     */
    public static function insertOrderBalanceWithParameters($order_id) {
    	$db = new Database();    	 
    	// Get the order rows
    	$results = Order::getOrderRows($order_id, $db);
    	foreach ($results as $result) {
    		// Insert to Balance each row
    		Balance::insertBalanceWithParameters($result['P_ID'], $_SESSION['id'], $result['QUANTITY'], $result['PRICE'], 'Credit', $db);
    	}
    }
}
