<?php
@session_start();

class About {

    /**
     * get about details of user
     * @param $user_name - the user to get the about of
     * @return false if no user found
     */
    public static function getAbout($user_name) {
        $db = new Database();
        $q = "SELECT * FROM about WHERE username='{$user_name}'";
        $result = $db->createQuery($q);
        if (count($result) > 0) {
            return $result;
        } else {
            return FALSE;
        }
    }

    /**
     * insert user's about to database
     * @param $user_name - the user name of user to insert
     * @param $url/2 - the url of user's image
     * @param $paragraph - the paragraph the user entered in about page
     */
    public static function insertAbout($user_name, $url, $url2, $paragraph) {
        $db = new Database();
        $q = "INSERT INTO `about` (`id`, `username`, `url`, `url2`, `paragraph`) VALUES
        (NULL,'{$user_name}', '{$url}', '{$url2}','{$paragraph}');";
        debug($q);
        $db->createQuery($q);
    }
    
    public static function updateAbout($user_name, $url, $url2, $paragraph) {
    	$db = new Database();
    	$q = "UPDATE `wix_for_poor`.`about` SET `url` = '{$url}', `url2` = '{$url2}', `paragraph` = '{$paragraph}' WHERE `about`.`username` = '{$user_name}'";
    	debug($q);
    	$db->createQuery($q);
    }

}
