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
            $err = "Please fill in all the form";
        } else {
            $user = cleanInput($_POST['username']);
            $result = User::getUser($user);
            //if user was not found in database -> create new user
            if ($result) {
                $hash = $result[0]['password'];
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
        if ((empty($_POST['first_name'])) || (empty($_POST['last_name'])) || (empty($_POST['user'])) || (empty($_POST['email'])) || (empty($_POST['pass']))) {
            $err = "Please fill in all the form";
        } else {
            $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
            if (!preg_match($email_exp, $_POST['email'])) {
                $err = 'The Email Address you entered does not appear to be valid.';
            }
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
                } else {
                    User::newUser();
                }
            } else {
                $err = "SORRY...YOU ARE ALREADY REGISTERED USER...";
            }
        }
        return $err;
    }

    /**
     * get user params from post
     */
    public static function newUser() {
        $firstName = cleanInput($_POST['first_name']);
        $lastName = cleanInput($_POST['last_name']);
        $userName = cleanInput($_POST['user']);
        $email = cleanInput($_POST['email']);
        $password = password_hash($_POST['pass'], PASSWORD_BCRYPT);
        User::insertUser($userName, $password, $email, $firstName, $lastName);

    }

    /**
     * update a new user to database
     */
    public static function insertUser($user, $password, $email, $first_name, $last_name) {
        $db = new Database();
        $q = "INSERT INTO `users` (`id`,`email`, `username`, `password`, `first_name`, `last_name`) VALUES
             (NULL, '{$email}','{$user}','{$password}','{$first_name}','{$last_name}');";
        $db->createQuery($q);
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
    
    public static function updateLogo($url, $user) {
    	$db = new Database();
    	$q = "UPDATE `wix_for_poor`.`users` SET `logo` = '{$url}' WHERE `users`.`username` = '{$user}';";
    	$db->createQuery($q);
    }

}
