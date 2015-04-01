<?php
@session_start();

class User {

    /*
     * function to check if form was submitted ok
     * return errors if found any
     */
    public static function testSignIn() {
        $err = '';
        if ((empty($_POST['username'])) || (empty($_POST['password']))) {
            $err = "Please fill in all the fields";
        } else {
            $user = cleanInput($_POST['username']);
            $result = User::getUser($user);
            //if user was not found in database -> create new user
            if ($result) {
                $hash = $result[0]['PASSWORD'];
                $password = $_POST['password'];
                //before insert the new user check if password match
                if (!password_verify($password, $hash)) {
                    $err = "Password does not match";
                }
            } else {
                $err = "User was not found";
            }
        }
        return $err;
    }

    /*
     * function to check if form was submitted ok
     * return errors if found any
     */
    public static function testSignUp() {
        $err = '';
        if ((empty($_POST['first_name'])) || (empty($_POST['last_name'])) || (empty($_POST['user'])) || (empty($_POST['pass']))) {
            $err = "Please fill in all the fields";
        } else {
            $string_exp = "/^[A-Za-z .'-]+$/";
            if (!preg_match($string_exp, $_POST['first_name'])) {
                $err = 'The First Name you entered does not appear to be valid.';
            }
            if (!preg_match($string_exp, $_POST['last_name'])) {
                $err = 'The Last Name you entered does not appear to be valid.';
            }
            $user = $_POST['user'];
            $result = User::getUser($user);
            //if user was not found in database -> create new user
            if (!$result) {
                $string = strtoupper($_SESSION['string']); 
                $userstring = strtoupper($_POST['userstring']);
                //before insert the new user check if password match
                if ($_POST['pass'] != $_POST['cpass']) {
                    $err = "Password does not match!";
                } elseif (($string != $userstring) || (strlen($string) <= 4)) {
                    $err = "Please enter the code in the image again";
                } 
            } else {
                $err = "User is already registered...";
            }
        }
        return $err;
    }
    
    /*
     * function to check if edit form was submitted ok
     * return errors if found any
     */
    public static function testEdit() {
    	$err = '';
    	if ((empty($_POST['first_name'])) || (empty($_POST['last_name']))) {
    		$err = "Please fill in all the fields";
    	} else {
    		$string_exp = "/^[A-Za-z .'-]+$/";
    		if (!preg_match($string_exp, $_POST['first_name'])) {
    			$err = 'The First Name you entered does not appear to be valid.';
    		}
    		if (!preg_match($string_exp, $_POST['last_name'])) {
    			$err = 'The Last Name you entered does not appear to be valid.';
    		}
    	}
    	return $err;
    }

    /**
     * get user params from post
     */
    public static function newUser() {
        $first_name = cleanInput($_POST['first_name']);
        $last_name = cleanInput($_POST['last_name']);
        $username = cleanInput($_POST['user']);
        $password = password_hash($_POST['pass'], PASSWORD_BCRYPT);
        
        return User::insertUser($username, $password, $first_name, $last_name);
    }

    /**
     * update a new user to database
     */
    public static function insertUser($user, $password, $first_name, $last_name) {
        $db = new Database();
        $q = "INSERT INTO USERS(USERNAME, PASSWORD, FIRST_NAME, LAST_NAME) VALUES (:cuser, :cpass, :cfirst_name, :clast_name)";
        $stid = $db->parseQuery($q);
        oci_bind_by_name($stid, ':cuser', $user);
        oci_bind_by_name($stid, ':cpass', $password);
        oci_bind_by_name($stid, ':cfirst_name', $first_name);
        oci_bind_by_name($stid, ':clast_name', $last_name);
        $r = oci_execute($stid);  // executes and commits
        return $r;
    }

    /**
     * get user from database
     * return false if was not found
     * @param - $user - a user name to search for
     */
    public static function getUser($user) {
        $db = new Database();
        $q = "SELECT * FROM users WHERE username='{$user}'";
        $result = $db->createQuery($q);
        if (count($result) > 0) {
            return $result;
        } else {
            return FALSE;
        }
    }
    
    /**
     * update user
     * @param - $user->user to update, $first_name, $last_name
     */
    public static function updateUser($user, $first_name, $last_name) {
    	$db = new Database();
    	$q = "UPDATE users SET first_name = :cfirst, last_name = :clast WHERE username = :cuser";
    	$stid = $db->parseQuery($q);
    	oci_bind_by_name($stid, ':cuser', $user);
        oci_bind_by_name($stid, ':cfirst', $first_name);
        oci_bind_by_name($stid, ':clast', $last_name);
        $r = oci_execute($stid);  // executes and commits
        return $r;
    }
}
