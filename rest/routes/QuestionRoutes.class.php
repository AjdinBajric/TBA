<?php
require_once __DIR__ . '/../Config.class.php';
require_once __DIR__ . '/../helpers/helpers.php';

Flight::route('GET /question', function () {
    $questions = Flight::questionService()->get_all();
    foreach ($questions as &$element) {
        if ($element['image'] != null) {
            $element['image'] = base64_encode($element['image']);
        }
    }
    Flight::json($questions);
});

Flight::route('GET /question/@id', function ($id) {
    $question = Flight::questionService()->get_by_id($id);
    if ($question) {
        if ($question['image'] != null) {
            $question['image'] = base64_encode($question['image']);
        }
        Flight::json($question);
    } else
        Flight::json(['message' => 'Question does not exist']);
});

Flight::route('DELETE /question/@id', function ($id) {
    $question = Flight::questionService()->get_by_id($id);
    if (isset($question['id'])) {
        Flight::questionService()->delete_element($id);
        Flight::json(["message" => "Question deleted."]);
    } else {
        Flight::json(['message' => 'Question does not exist']);
    }
});

Flight::route('PUT /question/@id', function ($id) {
    $error_messages = array();
    $new_data = Flight::request()->data->getData();
    $question = Flight::questionService()->get_by_id($id);
    $category = Flight::categoryService()->get_by_id($new_data['category']);
    $quiz_type = Flight::quizTypeService()->get_by_id($new_data['category']);
    if (!isset($category['id'])) $error_messages[] = "Category does not exist";
    if (!isset($quiz_type['id'])) $error_messages[] = "Quiz type does not exist";
    if (!isset($question['id'])) $error_messages[] = "Question does not exist";

    if (count($error_messages) == 0) {
        Flight::json(Flight::questionService()->update_element($id, $new_data));
    } else {
        Flight::json(["message" => $error_messages]);
    }
});

Flight::route('POST /question', function () {
    $error_messages = array();
    $new_question = Flight::request()->data->getData();
    $category = Flight::categoryService()->get_by_id($new_question['category']);
    $quiz_type = Flight::quizTypeService()->get_by_id($new_question['category']);
    if (!isset($category['id'])) $error_messages[] = "Category does not exist";
    if (!isset($quiz_type['id'])) $error_messages[] = "Quiz type does not exist";
    if (count($error_messages) == 0) {
        if (array_key_exists('image', $new_question)) {
            $new_question['image'] = base64_decode($new_question['image']);
        }
        Flight::json(Flight::questionService()->add_element($new_question));
    } else {
        Flight::json(["message" => $error_messages]);
    }
});
?>