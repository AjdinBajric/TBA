<?php
require_once __DIR__ . '/../Config.class.php';

Flight::route('GET /quiz_type', function () {
    Flight::json(Flight::quizTypeService()->get_all());
});

Flight::route('GET /quiz_type/@id', function ($id) {
    Flight::json($id);
    $quiz_type = Flight::quizTypeService()->get_by_id($id);
    if ($quiz_type)
        Flight::json($quiz_type);
    else
        Flight::json(['message' => 'Quiz type does not exist']);
});

Flight::route('GET /quiz_type_by_category', function () {
    $request = Flight::request();
    $category = $request->query->category;
    $quiz_type = Flight::quizTypeService()->get_by_category_name($category);
    if ($quiz_type)
        Flight::json(['types' => $quiz_type], 200);
    else
        Flight::json(['message' => 'Quiz type does not exist'], 400);
});


Flight::route('DELETE /quiz_type/@id', function ($id) {
    $quiz_type = Flight::quizTypeService()->get_by_id($id);
    if (isset($quiz_type['id'])) {
        Flight::quizTypeService()->delete_element($id);
        Flight::json(["message" => "Quiz type deleted."]);
    } else {
        Flight::json(['message' => 'Quiz type does not exist']);
    }
});

Flight::route('PUT /quiz_type/@id', function ($id) {
    $new_data = Flight::request()->data->getData();
    $quiz_type = Flight::quizTypeService()->get_by_id($id);
    if (isset($quiz_type['id'])) {
        Flight::json(Flight::quizTypeService()->update_element($id, $new_data));
    } else {
        Flight::json(['message' => 'Quiz type does not exist']);
    }
});

Flight::route('POST /quiz_type', function () {
    $new_quiz_type = Flight::request()->data->getData();
    Flight::json(Flight::quizTypeService()->add_element($new_quiz_type));
});
?>