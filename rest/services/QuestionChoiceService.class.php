<?php

require_once __DIR__ . '/BaseService.class.php';
require_once __DIR__ . '/../dao/QuestionChoiceDao.class.php';

class QuestionChoiceService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new QuestionChoiceDao());
    }
}

?>