<?php
@session_start();

include_once 'database/Database.php';

class Invoice {

    /*
     * insert new invoice details
     */
    public static function insertInvoice() {
        $db = new Database();
//         $q = "insert into customers(FIRST_NAME, LAST_NAME) values (:cfirst_name, :clast_name)";
//         $stid = $db->parseQuery($q);
//         oci_bind_by_name($stid, ':cfirst_name', $_POST['first_name']);
//         oci_bind_by_name($stid, ':clast_name', $_POST['last_name']);
//         $r = oci_execute($stid);  // executes and commits
//         return $r;
    }
    
    /*
     * Get all the invoices headers
     */
    public static function getInvoicesHeaders() {
    	$db = new Database();
    	$q = "select * from invoice_header order by order_id";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result;
    	} else {
    		return FALSE;
    	}
    }
    
    /*
     * Get the invoice header
     */
    public static function getInvoiceHeader($invoice_id) {
    	$db = new Database();
    	$q = "select * from invoice_header where invoice_id = '{$invoice_id}'";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result;
    	} else {
    		return FALSE;
    	}
    }
    
    /*
     * Get the invoice rows
     */
    public static function getInvoiceRows($invoice_id) {
    	$db = new Database();
    	$q = "select r.invoice_id, r.row_num, r.p_id, p.description, r.quantity, p.price, (r.quantity*p.price) as total from invoice_rows r, products p where r.p_id = p.p_id and r.invoice_id = '{$invoice_id}'";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result;
    	} else {
    		return FALSE;
    	}
    }
    
    public static function getInvoiceDate($invoice_id) {
    $db = new Database();
    	$q = "select TO_CHAR(ORDER_DATE, 'DD/MM/YYYY') AS ORDER_DATE from invoice_header where invoice_id = '{$invoice_id}'";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result;
    	} else {
    		return FALSE;
    	}
    }
    
    public static function getTotal($invoice_id) {
    	$db = new Database();
    	$q = "select sum(TOTAL) as total from (Select p.description, p.price, r.quantity, (P.PRICE*R.QUANTITY) as Total from invoice_rows r, products p where r.p_id = p.p_id and r.invoice_id = '{$invoice_id}')";
    	$total = $db->createQuery($q);
    	if(count($total) == 0) {
    		$total[0]['TOTAL'] = 0;
    	}
    	return $total[0]['TOTAL'];
    }
}
