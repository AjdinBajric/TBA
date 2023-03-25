<?php
require_once __DIR__ . '/BaseDao.class.php';

class QuestionChoiceDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("question_choices");
    }
}

?>