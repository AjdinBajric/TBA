<?php
require_once __DIR__ . '/../Config.class.php';

Flight::route('GET /question_choice', function () {
    $question_choice = Flight::questionChoiceService()->get_all();
    foreach ($question_choice as &$element) {
        unset($element['is_correct']);
    }
    Flight::json($question_choice);
});

Flight::route('GET /question_choice/@id', function ($id) {
    $question_choice = Flight::questionChoiceService()->get_by_id($id);
    unset($question_choice['is_correct']);
    if ($question_choice)
        Flight::json($question_choice);
    else
        Flight::json(['message' => 'Question choice does not exist']);
});

Flight::route('DELETE /question_choice/@id', function ($id) {
    $question_choice = Flight::questionChoiceService()->get_by_id($id);
    if (isset($question_choice['id'])) {
        Flight::questionChoiceService()->delete_element($id);
        Flight::json(["message" => "Question choice deleted."]);
    } else {
        Flight::json(['message' => 'Question choice does not exist']);
    }
});

Flight::route('PUT /question_choice/@id', function ($id) {
    $error_messages = array();
    $new_data = Flight::request()->data->getData();
    $question_choice = Flight::questionChoiceService()->get_by_id($id);
    $question = Flight::questionService()->get_by_id($new_data['question_id']);
    if (!isset($question['id'])) $error_messages[] = "Question does not exist";
    if (!isset($question_choice['id'])) $error_messages[] = "Question choice does not exist";

    if (count($error_messages) == 0) {
        if (array_key_exists('is_correct', $new_data)) {
            $new_data['is_correct'] = map_boolean_to_int($new_data['is_correct']);
        }
        $updated_element = Flight::questionChoiceService()->update_element($id, $new_data);
        unset($updated_element['is_correct']);
        Flight::json($updated_element);
    } else {
        Flight::json(['message' => $error_messages]);
    }
});

Flight::route('POST /question_choice', function () {
    $error_messages = array();
    $new_question_choice = Flight::request()->data->getData();
    $question = Flight::questionService()->get_by_id($new_question_choice['question_id']);
    if (!isset($question['id'])) $error_messages[] = "Question does not exist";

    if (count($error_messages) == 0) {
        $new_question_choice['is_correct'] = map_boolean_to_int($new_question_choice['is_correct']);
        $added_question_choice = Flight::questionChoiceService()->add_element($new_question_choice);
        unset($added_question_choice['is_correct']);
        Flight::json($added_question_choice);
    } else {
        Flight::json(["message" => $error_messages]);
    }
});

?>