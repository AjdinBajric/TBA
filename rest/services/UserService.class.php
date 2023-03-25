<?php

require_once __DIR__ . '/BaseService.class.php';
require_once __DIR__ . '/../dao/UserDao.class.php';

class UserService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new UserDao());
    }

    //TODO: change to: return user with username 'x' exists
    public function get_user_by_username($username)
    {
        return $this->dao->get_user_by_username($username);
    }
}

?>