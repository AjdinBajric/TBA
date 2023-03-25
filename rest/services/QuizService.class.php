<?php

require_once __DIR__ . '/BaseService.class.php';
require_once __DIR__ . '/../dao/QuizDao.class.php';

class QuizService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new QuizDao());
    }
}

?>