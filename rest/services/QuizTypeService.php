<?php

require_once __DIR__ . '/BaseService.class.php';
require_once __DIR__ . '/../dao/QuizTypeDao.class.php';

class QuizTypeService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new QuizTypeDao());
    }
    public function get_by_category_name($category)
    {
        return $this->dao->get_by_category_name($category);
    }
}

?>