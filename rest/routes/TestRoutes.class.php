<?php
require_once __DIR__ . '/../Config.class.php';

Flight::route('GET /test', function () {
    Flight::json(Flight::testService()->get_all());
});

Flight::route('GET /test/@id', function ($id) {
    $test = Flight::testService()->get_by_id($id);
    if ($test)
        Flight::json($test);
    else
        Flight::json(['message' => 'Test does not exist']);
});

Flight::route('DELETE /test/@id', function ($id) {
    $test = Flight::testService()->get_by_id($id);
    if (isset($test['id'])) {
        Flight::testService()->delete_element($id);
        Flight::json(["message" => "Test deleted."]);
    } else {
        Flight::json(['message' => 'Test does not exist']);
    }
});

Flight::route('PUT /test/@id', function ($id) {
    $error_messages = array();
    $new_data = Flight::request()->data->getData();
    $test = Flight::testService()->get_by_id($id);
    $category = Flight::categoryService()->get_by_id($new_data['category']);
    if (!isset($test['id'])) $error_messages[] = "Test does not exist";
    if (!isset($category['id'])) $error_messages[] = "Category does not exist";

    if (count($error_messages) == 0)
        Flight::json(Flight::testService()->update_element($id, $new_data));
    else {
        Flight::json(['message' => $error_messages]);
    }
});

Flight::route('POST /test', function () {
    $new_test = Flight::request()->data->getData();
    $category = Flight::categoryService()->get_by_id($new_test['category']);
    if (isset($category['id']))
        Flight::json(Flight::testService()->add_element($new_test));
    else {
        Flight::json(['message' => 'Category does not exist']);
    }
});
?>