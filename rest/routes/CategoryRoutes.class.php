<?php
require_once __DIR__ . '/../Config.class.php';

Flight::route('GET /category', function () {
    Flight::json(Flight::categoryService()->get_all());
});

Flight::route('GET /category/@id', function ($id) {
    $category = Flight::categoryService()->get_by_id($id);
    if ($category)
        Flight::json($category);
    else
        Flight::json(['message' => 'Category does not exist']);
});

Flight::route('DELETE /category/@id', function ($id) {
    $category = Flight::categoryService()->get_by_id($id);
    if (isset($category['id'])) {
        Flight::categoryService()->delete_element($id);
        Flight::json(["message" => "Category deleted."]);
    } else {
        Flight::json(['message' => 'Category does not exist']);
    }
});

Flight::route('PUT /category/@id', function ($id) {
    $new_data = Flight::request()->data->getData();
    $category = Flight::categoryService()->get_by_id($id);
    if (isset($category['id'])) {
        Flight::json(Flight::categoryService()->update_element($id, $new_data));
    } else {
        Flight::json(['message' => 'Category does not exist']);
    }
});

Flight::route('POST /category', function () {
    $new_user = Flight::request()->data->getData();
    Flight::json(Flight::categoryService()->add_element($new_user));
});
?>