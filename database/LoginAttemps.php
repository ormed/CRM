<?php
@session_start();

class LoginAttemps {
    /**
     * Returns user's count of failed logins
     * @param $ip_adress - the ip of current user
     * @return number of failed logins
     */
    public static function get_failed_log_count($ip_address) {
        $db = new Database();
        $q = "SELECT * FROM login_attempts WHERE user_ip = '{$ip_address}'";
        $user = $db->createQuery($q);
        $current_time = new DateTime();
        //first check if user exist in DB
        if (count($user) > 0) {
            //check if last login was less than 30 minutes
            $since_start = $current_time->diff(new DateTime($user[0]['lastlogin']));
            $minutes = $since_start->days * 24 * 60;
            $minutes += $since_start->h * 60;
            $minutes += $since_start->i;
            if ($minutes > 30) {
                LoginAttemps::clear_attempts($ip_address);
            }
            $attempts = $user[0]['attempts'];
            $attempts++;
            $q = "UPDATE  `wix_for_poor`.`login_attempts` SET  `attempts` =  '{$attempts}' WHERE  `login_attempts`.`id` ='{$user[0]['id']}';";
            $db->createQuery($q);

        } else {//not in DB so we add him
            $q = "INSERT INTO `wix_for_poor`.`login_attempts` (`id`, `user_ip`, `attempts`, `lastlogin`)";
            $q .= " VALUES (NULL, '{$ip_address}', '1', CURRENT_TIMESTAMP);";
            // 1 because this is first try
            $db->createQuery($q);
        }

        return $user[0]['attempts'];
    }

    /**
     * Clear attempts
     * @param $user - current user
     * @desc Clears the number of failed attempts this uesr name did
     */
    public static function clear_attempts($ip_address) {
        $db = new Database();
        $q = "UPDATE  `wix_for_poor`.`login_attempts` SET  `attempts` =  '{0}' WHERE  `login_attempts`.`user_ip` ='{$ip_address}';";
        $db->createQuery($q);
    }

}
