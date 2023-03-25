<?php

require_once __DIR__ . '/BaseService.class.php';
require_once __DIR__ . '/../dao/TestDao.class.php';

class TestService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new TestDao());
    }
}

?>