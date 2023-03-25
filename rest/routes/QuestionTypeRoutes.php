<?php
require_once __DIR__ . '/../Config.class.php';

Flight::route('GET /question_type', function () {
    Flight::json(Flight::questionTypeService()->get_all());
});

Flight::route('GET /question_type/@id', function ($id) {
    $question_type = Flight::questionTypeService()->get_by_id($id);
    if ($question_type)
        Flight::json($question_type);
    else
        Flight::json(['message' => 'Question type does not exist']);
});

Flight::route('DELETE /question_type/@id', function ($id) {
    $question_type = Flight::questionTypeService()->get_by_id($id);
    if (isset($question_type['id'])) {
        Flight::questionTypeService()->delete_element($id);
        Flight::json(["message" => "Question type deleted."]);
    } else {
        Flight::json(['message' => 'Question type does not exist']);
    }
});

Flight::route('PUT /question_type/@id', function ($id) {
    $new_data = Flight::request()->data->getData();
    $question_type = Flight::questionTypeService()->get_by_id($id);
    if (isset($question_type['id'])) {
        Flight::json(Flight::questionTypeService()->update_element($id, $new_data));
    } else {
        Flight::json(['message' => 'Question type does not exist']);
    }
});

Flight::route('POST /question_type', function () {
    $new_question_type = Flight::request()->data->getData();
    Flight::json(Flight::questionTypeService()->add_element($new_question_type));
});
?>