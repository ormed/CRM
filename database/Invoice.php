<?php
@session_start();

include_once 'database/Database.php';
include_once 'database/Order.php';

class Invoice {

    /*
     * insert new invoice details
     */
    public static function insertInvoice($order_id) {
        $db = new Database();
        
        // Change order status
        Order::closeOrder($order_id, $db);
     
        // Insert invoice header
        $result = Order::getOrderHeader($order_id);
        $q = "insert into invoice_header(order_id, order_date, cust_id) values (:corder_id, to_date(:corder_date, 'dd/mm/yyyy'), :ccust_id)";
        $stid = $db->parseQuery($q);
        oci_bind_by_name($stid, ':corder_id', $order_id);
        oci_bind_by_name($stid, ':corder_date', $result[0]['ORDER_DATE']);
        oci_bind_by_name($stid, ':ccust_id', $result[0]['CUST_ID']);
        $r = oci_execute($stid);  // executes and commits
        
        // Insert invoice rows
        $invoice_id = Invoice::getInvoiceId($order_id);
        $results = Order::getOrderRows($order_id);
        foreach ($results as $index=>$result) {
	        $q = "insert into invoice_rows(invoice_id, row_num, p_id, quantity) values (:cinvoice_id, :crow_num, :cp_id, :cquantity)";
	        $stid = $db->parseQuery($q);
	        oci_bind_by_name($stid, ':cinvoice_id', $invoice_id);
	        $row_num = $index + 1;
	        oci_bind_by_name($stid, ':crow_num', $row_num);
	        oci_bind_by_name($stid, ':cp_id', $result['P_ID']);
	        oci_bind_by_name($stid, ':cquantity', $result['QUANTITY']);
	        oci_execute($stid);  // executes and commits
        }
        return $r;
    }
    
    public static function editInvoice() {
    	$db = new Database();
    	$invoice_id = $_POST['invoice_id'];
    	$i = 0;
    	while(isset($_POST['quantity_'.$i])) {
    		if($_POST['quantity_'.$i] == 0) {
    			// Delete row
    			$q = "delete from invoice_rows where (invoice_id = :cinvoice_id and p_id = :cp_id)";
    			$stid = $db->parseQuery($q);
    			oci_bind_by_name($stid, ':cinvoice_id', $invoice_id);
    			oci_bind_by_name($stid, ':cp_id', $_POST['p_id_'.$i]);
    			oci_execute($stid); // delete row
    		} else {
	    		// Update quantity
	    		$q = "update invoice_rows set quantity = :cquantity where (p_id = :cp_id and invoice_id = :cinvoice_id)";
	    		$stid = $db->parseQuery($q);
	    		oci_bind_by_name($stid, ':cquantity', $_POST['quantity_'.$i]);
	    		oci_bind_by_name($stid, ':cp_id', $_POST['p_id_'.$i]);
	    		oci_bind_by_name($stid, ':cinvoice_id', $invoice_id);
	    		oci_execute($stid);  // executes and commits
    		}
    		$i++;
    	}
    }
    
    public static function deleteInvoice($invoice_id) {
    	$db = new Database();
    	// Delete all rows
    	$q = "delete from invoice_rows where (invoice_id = :cinvoice_id)";
    	$stid = $db->parseQuery($q);
    	oci_bind_by_name($stid, ':cinvoice_id', $invoice_id);
    	oci_execute($stid); // delete rows
    	 
    	// Delete the header
    	$q = "delete from invoice_header where (invoice_id = :cinvoice_id)";
    	$stid = $db->parseQuery($q);
    	oci_bind_by_name($stid, ':cinvoice_id', $invoice_id);
    	oci_execute($stid); // delete header
    }
    
    public static function getInvoiceId($order_id) {
    	$db = new Database();
    	$q = "select * from invoice_header where order_id = '{$order_id}'";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result[0]['INVOICE_ID'];
    	} else {
    		return FALSE;
    	}
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
