<?php
@session_start();

include_once 'database/Database.php';
include_once 'database/Order.php';
include_once 'database/Customer.php';
include_once 'database/Balance.php';

class Invoice {

    /**
     * insert new invoice details
     * @param $order_id
     * @param $_POST- info about the new invoice
     */
    public static function insertInvoice($order_id) {
        $db = new Database();
        
        // Change order status
        Order::closeOrder($order_id, $db);
     
        // Insert invoice header
        $result = Order::getOrderHeader($order_id, $db);
        $q = "insert into invoice_header(order_id, order_date, cust_id) values (:corder_id, to_date(:corder_date, 'dd/mm/yyyy'), :ccust_id)";
        $stid = $db->parseQuery($q);
        oci_bind_by_name($stid, ':corder_id', $order_id);
        oci_bind_by_name($stid, ':corder_date', $result[0]['ORDER_DATE']);
        oci_bind_by_name($stid, ':ccust_id', $result[0]['CUST_ID']);
        $r = oci_execute($stid);  // executes and commits
        
        // Insert invoice rows
        $invoice_id = Invoice::getInvoiceId($order_id);
        $results = Order::getOrderRows($order_id, $db);
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
    
    /**
     * Edits invoice
     * @param $_POST- contains the info of the order
     */
    public static function editInvoice() {
    	$db = new Database();
    	$invoice_id = $_POST['invoice_id'];
    	$i = 0;
    	while(isset($_POST['quantity_'.$i])) {
    		$old_quantity = Invoice::getQuantity($invoice_id, $_POST['p_id_'.$i], $db);
    		
	    	// Update quantity
	    	$q = "update invoice_rows_view_copy set quantity = :cquantity where (p_id = :cp_id and invoice_id = :cinvoice_id)";
	    	$stid = $db->parseQuery($q);
	    	oci_bind_by_name($stid, ':cquantity', $_POST['quantity_'.$i]);
	    	oci_bind_by_name($stid, ':cp_id', $_POST['p_id_'.$i]);
	    	oci_bind_by_name($stid, ':cinvoice_id', $invoice_id);
	    	oci_execute($stid);  // executes and commits
	    	
	    	// Check if the customer has returned items
	    	if($_POST['quantity_'.$i] < $old_quantity) { 
	    		$returned_quantity = $old_quantity - $_POST['quantity_'.$i];
	    		$price = Products::getProductPrice($_POST['p_id_'.$i]);
	    		
	    		// Add returned items to Inventory
	    		Inventory::increaseQuantity($_POST['p_id_'.$i], $returned_quantity, $db);
	    		
	    		// Make Debit for the returned items
	    		Balance::insertBalanceWithParameters($_POST['p_id_'.$i], $_SESSION['id'], $returned_quantity, $price, 'Debit', $db);
	    	}
    		$i++;
    	}
    }
    
    public static function getQuantity($invoice_id, $p_id, $db) {
    	$q = "select quantity from invoice_rows where invoice_id = '{$invoice_id}' and p_id = '{$p_id}'";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result[0]['QUANTITY'];
    	} else {
    		return FALSE;
    	}
    }
    
    /**
     * Deletes an invoice by id
     * @param int $invoice_id
     */
    public static function deleteInvoice($invoice_id) {
    	$db = new Database();
    	// Add to Balance as Debit
    	$results = Invoice::getInvoiceRows($invoice_id, $db);
    	foreach ($results as $result) {
    		Balance::insertBalanceWithParameters($result['P_ID'], $_SESSION['id'], $result['QUANTITY'], $result['PRICE'], 'Debit', $db);
    	}
    	
    	// Create a credit invoice header and rows for the current invoice_id
    	$header = Invoice::getInvoiceHeader($invoice_id, $db);
    	Invoice::creditInvoice($header[0], $results, $db);
    	
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
    
    public static function refund($invoice_id) {
    	$db = new Database();
    	// Add to Balance as Debit
    	$results = Invoice::getInvoiceRows($invoice_id, $db);
    	foreach ($results as $result) {
    		Balance::insertBalanceWithParameters($result['P_ID'], $_SESSION['id'], $result['QUANTITY'], $result['PRICE'], 'Debit', $db);
    	}
    	 
    	// Create a new invoice as a refund
    	$header = Invoice::getInvoiceHeader($invoice_id, $db);
    	Invoice::insertRefundedInvoice($header[0], $db);
    	 
    	// Make invoice unrefundable
    	$q = "begin edit_refunded(:cinvoice_id, 'True'); end;";
    	$stid = $db->parseQuery($q);
    	oci_bind_by_name($stid, ':cinvoice_id', $invoice_id);
    	oci_execute($stid);  // Make refunded-'True'
    }
    
    public static function insertRefundedInvoice($header, $db) {
    	// Create refunded header
    	$order_id = $header['ORDER_ID'];
    	$order_date = Order::getOrderDate($order_id, $db)[0]['ORDER_DATE'];
    	$cust_id = $header['CUST_ID'];
    	$refunded = 'None';
    	$q = "begin insert_invoice_header(:corder_id, to_date(:corder_date, 'dd/mm/yyyy'), :ccust_id, :crefunded); end;";
    	$stid = $db->parseQuery($q);
    	oci_bind_by_name($stid, ':corder_id', $order_id);
    	oci_bind_by_name($stid, ':corder_date', $order_date);
    	oci_bind_by_name($stid, ':ccust_id', $cust_id);
    	oci_bind_by_name($stid, ':crefunded', $refunded);
    	oci_execute($stid);  // Create invoice header
    	
    	$refunded_id = Invoice::getLastAddedInvoiceId($db); // Get refunded id
    	
    	// Create refunded rows for the header
    	$rows = Invoice::getInvoiceRows($header['INVOICE_ID'], $db);
    	foreach ($rows as $index=>$row) {
    		$q = "begin insert_invoice_row(:cinvoice_id, :crow_num, :cp_id, :cquantity); end;";
    		$stid = $db->parseQuery($q);
    		oci_bind_by_name($stid, ':cinvoice_id', $refunded_id);
    		$row_num = $index + 1;
    		oci_bind_by_name($stid, ':crow_num', $row_num);
    		oci_bind_by_name($stid, ':cp_id', $row['P_ID']);
    		$refund_quantity = -1*$row['QUANTITY'];
    		oci_bind_by_name($stid, ':cquantity', $refund_quantity);
    		oci_execute($stid);  // Insert refunded row
    	}
    }
    
    public static function getLastAddedInvoiceId($db) {
    	$q = "select max(invoice_id) as last from invoice_header";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result[0]['LAST'];
    	} else {
    		return FALSE;
    	}
    }
    
    public static function creditInvoice($header, $rows, $db) {
		$q = "insert into invoice_header(order_id, order_date, cust_id) values (:corder_id, :corder_date, :ccust_id)";
		$stid = $db->parseQuery($q);
		oci_bind_by_name($stid, ':corder_id', $invoice_id);
		oci_bind_by_name($stid, ':corder_date', $header[]);
		oci_bind_by_name($stid, ':ccust_id', $header['CUST_ID']);
		oci_execute($stid); // insert header

		// Insert invoice rows
		$invoice_id = Invoice::getInvoiceId($order_id);
		$results = Order::getOrderRows($order_id, $db);
		foreach ($rows as $index=>$row) {
			$q = "insert into invoice_rows(invoice_id, row_num, p_id, quantity) values (:cinvoice_id, :crow_num, :cp_id, :cquantity)";
			$stid = $db->parseQuery($q);
			oci_bind_by_name($stid, ':cinvoice_id', $invoice_id);
			$row_num = $index + 1;
			oci_bind_by_name($stid, ':crow_num', $row_num);
			oci_bind_by_name($stid, ':cp_id', $row['P_ID']);
			oci_bind_by_name($stid, ':cquantity', $row['QUANTITY']);
			oci_execute($stid);  // executes and commits
		}
    }
    
    /**
     * Find invoice by id
     * @param int $order_id
     * @return boolean
     */
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
    
    /**
     * Get all the invoices headers
     */
    public static function getInvoicesHeaders($db) {
    	$q = "select * from invoice_header order by invoice_id DESC";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
    /**
     * Get the invoice header
     */
    public static function getInvoiceHeader($invoice_id, $db) {
    	$q = "select * from invoice_header where invoice_id = '{$invoice_id}'";
    	$result = $db->createQuery($q);
    	if (count($result) > 0) {
    		return $result;
    	} else {
    		return FALSE;
    	}
    }

    /**
     * Search the invoice details depend on the input params
     * @param int $invoice_id
     * @param int $cust_id
     * @param int $order_id
     * @param String $start_date
     * @param String $end_date
     * @return Array of invoices
     */
    public static function getInvoiceDetails($invoice_id, $cust_id, $order_id, $start_date, $end_date, $first_name, $last_name) {
    	$db = new Database();
    	$customers = Customer::getCustomersDetails($cust_id, $first_name, $last_name, $db);
    	if(!$customers) { // No input about customers has inserted
    		$cust_ids = "NULL";
    	} elseif(count($customers) > 0) {
    		$cust_ids = "";
    		foreach ($customers as $index=>$customer) {
    			$cust_ids .= ($customer['CUST_ID'].',');
    		}
    		$cust_ids[strlen($cust_ids)-1] = "";
    	} else {
    		$cust_ids = "NULL";
    	}
    	
    	 
    	// Get the right date format to insert
    	if(!empty($start_date)) {
    		$start = date("d/m/Y", strtotime($start_date));
    	} else {
    		$start = "";
    	}
    	if(!empty($end_date)) {
    		$end = date("d/m/Y", strtotime($end_date));
    	} else {
    		$end = "";
    	}

    	$q = "select i.invoice_id,i.order_id,to_char(i.order_date, 'DD/MM/YYYY') as order_date,i.cust_id, c.first_name, c.last_name from invoice_header i,customers c where i.cust_id=c.cust_id and (i.invoice_id='{$invoice_id}' or i.order_id='{$order_id}' or (i.order_date >= to_date('{$start}','dd/mm/yyyy')) or (i.order_date <= to_date('{$end}','dd/mm/yyyy')) or i.cust_id IN ({$cust_ids}))";
    	$results = $db->createQuery($q);
    	return $results;
    }
    
    /**
     * Get the invoice rows
     * @param $invoice_id 
     * @return Array of invoices
     */
    public static function getInvoiceRows($invoice_id, $db) {
    	$db = new Database();
    	$q = "select r.invoice_id, r.row_num, r.p_id, p.description, r.quantity, p.price, (r.quantity*p.price) as total from invoice_rows r, products p where r.p_id = p.p_id and r.invoice_id = '{$invoice_id}'";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
    /**
     * Find the invoice date by id
     * @param int $invoice_id
     * @return Array of invoices
     */
    public static function getInvoiceDate($invoice_id, $db) {
    	$q = "select TO_CHAR(ORDER_DATE, 'DD/MM/YYYY') AS ORDER_DATE from invoice_header where invoice_id = '{$invoice_id}'";
    	$result = $db->createQuery($q);
    	return $result;
    }
    
    /**
     * Calculate the total price of an invoice by id
     * @param int $invoice_id
     * @return int - the total price
     */
    public static function getTotal($invoice_id, $db) {
    	$q = "select sum(TOTAL) as total from (Select p.description, p.price, r.quantity, (P.PRICE*R.QUANTITY) as Total from invoice_rows r, products p where r.p_id = p.p_id and r.invoice_id = '{$invoice_id}')";
    	$total = $db->createQuery($q);
    	if(count($total) == 0) {
    		$total[0]['TOTAL'] = 0;
    	}
    	return $total[0]['TOTAL'];
    }
}
