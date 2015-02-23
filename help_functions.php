<?php

/**
 * function get param and write it to php error log
 * used for debug
 * 
 * @param $var - param to be written in error log 
 * @param $readable - TRUE for readable state .
 */
function debug($var, $readable = TRUE) {
    $dump = $readable ? print_r($var, TRUE) : var_export($var, TRUE);
    error_log(("==============================\n\n" . $dump . "\n"), 0);
}

//method that clean the input from things we dont want 
//return clean data
function cleanInput($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
