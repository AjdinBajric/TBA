<?php
require_once __DIR__ . '/BaseDao.class.php';

class TestDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("test");
    }
}

?>