<?php
require_once __DIR__ . '/BaseDao.class.php';

class UserDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("user");
    }


    //TODO: change to: return if user with "x" username exists
    public function get_user_by_username($username)
    {
        return $this->query_unique("SELECT * FROM user WHERE username = :username", ['username' => $username]);
    }
}

?>