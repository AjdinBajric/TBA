<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
require_once __DIR__ . '/services/UserService.class.php';
require_once __DIR__ . '/services/QuestionTypeService.php';

require_once __DIR__ . '/dao/UserDao.class.php';
require_once __DIR__ . '/dao/QuestionTypeDao.class.php';

require_once __DIR__ . '/dao/CategoryDao.class.php';
require_once __DIR__ . '/services/CategoryService.class.php';

require_once __DIR__ . '/dao/TestDao.class.php';
require_once __DIR__ . '/services/TestService.class.php';

require_once __DIR__ . '/dao/QuestionDao.class.php';
require_once __DIR__ . '/services/QuestionService.class.php';

require_once __DIR__ . '/dao/QuestionChoiceDao.class.php';
require_once __DIR__ . '/services/QuestionChoiceService.class.php';

require_once __DIR__ . '/dao/QuizQuestionDao.class.php';
require_once __DIR__ . '/services/QuizQuestionService.class.php';


Flight::register('userDao', 'UserDao');
Flight::register('userService', 'UserService');

Flight::register('questionTypeDao', 'QuestionTypeDao');
Flight::register('questionTypeService', 'QuestionTypeService');

Flight::register('categoryDao', 'CategoryDao');
Flight::register('categoryService', 'CategoryService');

Flight::register('testDao', 'TestDao');
Flight::register('testService', 'TestService');

Flight::register('questionDao', 'QuestionDao');
Flight::register('questionService', 'QuestionService');

Flight::register('questionChoiceDao', 'QuestionChoiceDao');
Flight::register('questionChoiceService', 'QuestionChoiceService');

Flight::register('quizQuestionDao', 'QuizQuestionDao');
Flight::register('quizQuestionService', 'QuizQuestionService');

require_once __DIR__ . '/routes/UserRoutes.php';
require_once __DIR__ . '/routes/QuestionTypeRoutes.php';
require_once __DIR__ . '/routes/CategoryRoutes.class.php';
require_once __DIR__ . '/routes/TestRoutes.class.php';
require_once __DIR__ . '/routes/QuestionRoutes.class.php';
require_once __DIR__ . '/routes/QuestionChoiceRoutes.class.php';
require_once __DIR__ . '/routes/QuizQuestionRoutes.class.php';


Flight::start();

?>