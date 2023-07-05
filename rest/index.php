<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
require_once __DIR__ . '/services/UserService.class.php';
require_once __DIR__ . '/services/QuizTypeService.php';

require_once __DIR__ . '/dao/UserDao.class.php';
require_once __DIR__ . '/dao/QuizTypeDao.class.php';

require_once __DIR__ . '/dao/CategoryDao.class.php';
require_once __DIR__ . '/services/CategoryService.class.php';

require_once __DIR__ . '/dao/QuizDao.class.php';
require_once __DIR__ . '/services/QuizService.class.php';

require_once __DIR__ . '/dao/QuestionDao.class.php';
require_once __DIR__ . '/services/QuestionService.class.php';

require_once __DIR__ . '/dao/QuestionChoiceDao.class.php';
require_once __DIR__ . '/services/QuestionChoiceService.class.php';


Flight::register('userDao', 'UserDao');
Flight::register('userService', 'UserService');

Flight::register('quizTypeDao', 'QuizTypeDao');
Flight::register('quizTypeService', 'QuizTypeService');

Flight::register('categoryDao', 'CategoryDao');
Flight::register('categoryService', 'CategoryService');

Flight::register('quizDao', 'QuizDao');
Flight::register('quizService', 'QuizService');

Flight::register('questionDao', 'QuestionDao');
Flight::register('questionService', 'QuestionService');

Flight::register('questionChoiceDao', 'QuestionChoiceDao');
Flight::register('questionChoiceService', 'QuestionChoiceService');


require_once __DIR__ . '/routes/UserRoutes.php';
require_once __DIR__ . '/routes/QuizTypeRoutes.php';
require_once __DIR__ . '/routes/CategoryRoutes.class.php';
require_once __DIR__ . '/routes/QuizRoutes.class.php';
require_once __DIR__ . '/routes/QuestionRoutes.class.php';
require_once __DIR__ . '/routes/QuestionChoiceRoutes.class.php';


Flight::start();

?>