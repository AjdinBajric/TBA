<?php
require_once __DIR__ . '/../Config.class.php';

use Firebase\JWT\JWT;

Flight::route('GET /username', function () {
    $valid_user = Flight::get('validUser');
    Flight::json($valid_user['username']);
});

Flight::route('GET /id', function () {
    $valid_user = Flight::get('validUser');
    Flight::json($valid_user['id']);
});

Flight::route('GET /firstname', function () {
    $valid_user = Flight::get('validUser');
    Flight::json($valid_user['firstname']);
});

Flight::route('GET /user', function () {
    Flight::json(Flight::userService()->get_all());
});

Flight::route('POST /user', function () {
    $new_user = Flight::request()->data->getData();
    Flight::json(Flight::userService()->add_element($new_user));
});

Flight::route('GET /user/@id', function ($id) {
    Flight::json(Flight::userService()->get_by_id($id));
});

Flight::route('DELETE /user/@id', function ($id) {
    Flight::userService()->delete_element($id);
    Flight::json(["message" => "User deleted."]);
});

Flight::route('PUT /user/@id', function ($id) {
    $new_data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->update_element($id, $new_data));
});


Flight::route('POST /register', function () {
    $registerUser = Flight::request()->data->getData();
    $storedUser = Flight::userService()->get_user_by_username($registerUser['username']);

    if (isset($storedUser['id'])) {
        Flight::json(["message" => "User with that username already exists. Try different username."], 404);
    } else {
        $options = [
            'cost' => 10 // The higher the cost, the more secure the hash (but also slower to compute)
        ];
        $registerUser['password'] = password_hash($registerUser['password'], PASSWORD_DEFAULT, $options);
        Flight::userService()->add_element($registerUser);
        Flight::json(["success" => "true"]);
    }
});


Flight::route('POST /login', function () {

    $login = Flight::request()->data->getData();

    $user = Flight::userService()->get_user_by_username($login['username']);

    if (isset($user['id'])) {

        if (password_verify($login['password'], $user['password'])) {
            unset($user['password']);
            $jwt = JWT::encode($user, Config::JWT_SECRET(), 'HS256');
            Flight::json(['token' => $jwt]);
        } else {
            Flight::json(["message" => "Password is incorrect"], 404);
        }
    } else {
        Flight::json(["message" => "User with that username doesn't exist"], 404);
    }
});

?>