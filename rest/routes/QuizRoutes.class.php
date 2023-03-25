<?php
require_once __DIR__ . '/../Config.class.php';

Flight::route('GET /quiz', function () {
    Flight::json(Flight::quizService()->get_all());
});

Flight::route('GET /quiz/@id', function ($id) {
    $quiz = Flight::quizService()->get_by_id($id);
    if ($quiz)
        Flight::json($quiz);
    else
        Flight::json(['message' => 'Quiz does not exist']);
});

Flight::route('DELETE /quiz/@id', function ($id) {
    $quiz = Flight::quizService()->get_by_id($id);
    if (isset($quiz['id'])) {
        Flight::quizService()->delete_element($id);
        Flight::json(["message" => "Quiz deleted."]);
    } else {
        Flight::json(['message' => 'Quiz does not exist']);
    }
});

Flight::route('PUT /quiz/@id', function ($id) {
    $error_messages = array();
    $new_data = Flight::request()->data->getData();
    $quiz = Flight::quizService()->get_by_id($id);
    $category = Flight::categoryService()->get_by_id($new_data['category']);
    if (!isset($quiz['id'])) $error_messages[] = "Quiz does not exist";
    if (!isset($category['id'])) $error_messages[] = "Category does not exist";

    if (count($error_messages) == 0)
        Flight::json(Flight::quizService()->update_element($id, $new_data));
    else {
        Flight::json(['message' => $error_messages]);
    }
});

Flight::route('POST /quiz', function () {
    $new_quiz = Flight::request()->data->getData();
    $category = Flight::categoryService()->get_by_id($new_quiz['category']);
    if (isset($category['id']))
        Flight::json(Flight::quizService()->add_element($new_quiz));
    else {
        Flight::json(['message' => 'Category does not exist']);
    }
});
?>