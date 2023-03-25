<?php
require_once __DIR__ . '/../Config.class.php';

Flight::route('GET /quiz_question', function () {
    Flight::json(Flight::quizQuestionService()->get_all());
});

Flight::route('GET /quiz_question/@id', function ($id) {
    $test = Flight::quizQuestionService()->get_by_id($id);
    if ($test)
        Flight::json($test);
    else
        Flight::json(['message' => 'Quiz question does not exist']);
});

Flight::route('DELETE /quiz_question/@id', function ($id) {
    $test = Flight::quizQuestionService()->get_by_id($id);
    if (isset($test['id'])) {
        Flight::quizQuestionService()->delete_element($id);
        Flight::json(["message" => "Quiz question deleted."]);
    } else {
        Flight::json(['message' => 'Quiz question does not exist']);
    }
});

Flight::route('PUT /quiz_question/@id', function ($id) {
    $error_messages = array();
    $new_data = Flight::request()->data->getData();
    $quiz_question = Flight::quizQuestionService()->get_by_id($id);
    $question = Flight::questionService()->get_by_id($new_data['question_id']);
    $test = Flight::testService()->get_by_id($new_data['test_id']);
    if (!isset($question['id'])) $error_messages[] = "Question does not exist";
    if (!isset($test['id'])) $error_messages[] = "Test does not exist";
    if (!isset($quiz_question['id'])) $error_messages[] = "Quiz question does not exist";

    if (count($error_messages) == 0)
        Flight::json(Flight::quizQuestionService()->update_element($id, $new_data));
    else {
        Flight::json(['message' => $error_messages]);
    }
});

Flight::route('POST /quiz_question', function () {
    $error_messages = array();
    $new_quiz_question = Flight::request()->data->getData();
    $question = Flight::questionService()->get_by_id($new_quiz_question['question_id']);
    $test = Flight::testService()->get_by_id($new_quiz_question['test_id']);
    if (!isset($question['id'])) $error_messages[] = "Question does not exist";
    if (!isset($test['id'])) $error_messages[] = "Test does not exist";

    if (count($error_messages) == 0)
        Flight::json(Flight::quizQuestionService()->add_element($new_quiz_question));
    else {
        Flight::json(['message' => $error_messages]);
    }
});
?>