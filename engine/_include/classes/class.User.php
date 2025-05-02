<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new DB;
        $this->db->connect();
    }

    /**
     * Login
     * @param string $username
     * @param string $password
     * @return array|null
     */
    public function login($username, $password) {
        $selectSQL = "SELECT * FROM `users` WHERE `username` = '{$username}' LIMIT 0, 1;";
        $selectQuery = $this->db->q($selectSQL);
        if ($selectQuery->num_rows > 0) {
            $selectResult = $selectQuery->fetch_assoc();
            if (password_verify($password, $selectResult["password"])) {
                return $selectResult;
            }
            return null;
        }
        return null;
    }

    /**
     * Join
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function join($username, $password) {
        $selectSQL = "SELECT * FROM `users` WHERE `username` = '{$username}' LIMIT 0, 1;";
        $selectQuery = $this->db->q($selectSQL);
        if ($selectQuery->num_rows > 0) {
            return false;
        }
        $insertData = [
            "username" => $username,
            "password" => password_hash($password, PASSWORD_DEFAULT),
        ];
        $insertSQL = $this->db->insertSQL("users", $insertData);
        $this->db->q($insertSQL);
        return true;
    }

    /**
     * Get UsersInfo
     * @return array
     */
    public function getUsersInfo() {
        $users = array();
        $selectSQL = "SELECT `idx`, `username` FROM `users` ORDER BY `idx` ASC;";
        $selectQuery = $this->db->q($selectSQL);
        while ($selectResult = $selectQuery->fetch_assoc()) {
            array_push($users, $selectResult);
        }
        return $users;
    }

    /**
     * Get UserInfo
     * @param int $userIdx
     * @return array|null
     */
    public function getUserInfo($userIdx) {
        if (empty($userIdx)) return null;
        $selectSQL = "SELECT `username`, `role` FROM `users` WHERE `idx` = '{$userIdx}';";
        $selectQuery = $this->db->q($selectSQL);
        $selectResult = $selectQuery->fetch_assoc();
        return $selectResult;
    }

    /**
     * Check Admin
     * @param int $userIdx
     * @return bool
     */
    public function isAdmin($userIdx) {
        if (empty($userIdx)) return false;
        $selectSQL = "SELECT `role` FROM `users` WHERE `idx` = '{$userIdx}';";
        $selectQuery = $this->db->q($selectSQL);
        $selectResult = $selectQuery->fetch_assoc();
        if ($selectResult["role"] == 1) {
            return true;
        }
        return false;
    }
}