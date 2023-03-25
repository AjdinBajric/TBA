<?php
require_once __DIR__ . '/BaseDao.class.php';

class QuestionDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("question");
    }
}

?>