<?php

require_once __DIR__ . '/BaseService.class.php';
require_once __DIR__ . '/../dao/QuizDao.class.php';

class QuizService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new QuizDao());
    }

    public function get_by_quiz_type($quiz_type_id, $category_name)
    {
        return $this->dao->get_by_quiz_type($quiz_type_id, $category_name);
    }

    public function get_quiz_data_by_id($id)
    {
        return $this->dao->get_quiz_data_by_id($id);
    }
}
?>