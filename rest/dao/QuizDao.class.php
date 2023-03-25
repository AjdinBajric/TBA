<?php
require_once __DIR__ . '/BaseDao.class.php';

class QuizDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("quiz");
    }
}

?>