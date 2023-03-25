<?php

require_once __DIR__ . '/BaseService.class.php';
require_once __DIR__ . '/../dao/QuizQuestionDao.class.php';

class QuizQuestionService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new QuizQuestionDao());
    }
}

?>