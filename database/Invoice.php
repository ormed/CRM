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
}
