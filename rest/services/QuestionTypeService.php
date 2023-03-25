<?php

require_once __DIR__ . '/BaseService.class.php';
require_once __DIR__ . '/../dao/QuestionTypeDao.class.php';

class QuestionTypeService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new QuestionTypeDao());
    }
}

?>