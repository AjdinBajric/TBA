<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
require_once __DIR__.'/services/UserService.class.php';
require_once __DIR__.'/services/QuestionTypeService.php';

require_once __DIR__.'/dao/UserDao.class.php';
require_once __DIR__.'/dao/QuestionTypeDao.class.php';


Flight::register('userDao','UserDao');
Flight::register('userService', 'UserService');

Flight::register('questionTypeDao','QuestionTypeDao');
Flight::register('questionTypeService', 'QuestionTypeService');

require_once __DIR__ . '/routes/UserRoutes.php';
require_once __DIR__ . '/routes/QuestionTypeRoutes.php';

Flight::start();

?>