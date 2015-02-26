<?php
@session_start();

class Order {

    /*
     * function to check if form was submitted ok
     * return errors if found any
     */
    public static function testOrderForm() {
        $err = '';
        if ((empty($_POST['order-date'])) || (empty($_POST['customer'])) || ($_POST['order-date'] == null)) {
            $err = "Please fill in all the fields";
        } else {
        	$i = 1;
        	while(isset($_POST['desc'.$i])) {
        		if(empty($_POST['quantity'.$i])) {
        			$err = "Please fill in all the fields";
        			return $err;
        		}
        		
        		$i++;
        	}
        	//all variables has been set
        }
        return $err;
    } 
}
