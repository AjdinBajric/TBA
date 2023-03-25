<?php
require_once __DIR__ . '/../Config.class.php';

Flight::route('GET /question', function () {
    Flight::json(Flight::questionService()->get_all());
});

Flight::route('GET /question/@id', function ($id) {
    $question = Flight::questionService()->get_by_id($id);
    if ($question)
        Flight::json($question);
    else
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
    $question_type = Flight::questionTypeService()->get_by_id($new_data['category']);
    if (!isset($category['id'])) $error_messages[] = "Category does not exist";
    if (!isset($question_type['id'])) $error_messages[] = "Question type does not exist";
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
    $question_type = Flight::questionTypeService()->get_by_id($new_question['category']);
    if (!isset($category['id'])) $error_messages[] = "Category does not exist";
    if (!isset($question_type['id'])) $error_messages[] = "Question type does not exist";
    if (count($error_messages) == 0) {
        Flight::json(Flight::questionService()->add_element($new_question));
    } else {
        Flight::json(["message" => $error_messages]);
    }
});
?>