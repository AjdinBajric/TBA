<?php
require_once __DIR__ . '/../Config.class.php';

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

Flight::route('POST /register', function () {
    $registered_user = Flight::request()->data->getData();
    $storedUser = Flight::userService()->get_user_by_username($registered_user['username']);

    if (isset($storedUser['id'])) {
        Flight::json(["message" => "User with that username already exists. Try different username."], 404); #check return code
    } else {
        Flight::json(Flight::userService()->add_element($registered_user));
    }
});

Flight::route('POST /login', function () {
    $login_user_data = Flight::request()->data->getData();
    $user = Flight::userService()->get_user_by_username($login_user_data['username']);

    if (isset($user['id'])) {
        if ($user['password'] == $login_user_data['password']) {
            unset($user['password']);
            $jwt = JWT::encode($user, Config::JWT_SECRET(), 'HS256');
            Flight::json(['token' => $jwt]);
        } else {
            Flight::json(["message" => "Invalid credentials."], 404);
        }
    } else {
        Flight::json(["message" => "User does not exist."], 404);
    }
});

Flight::route('GET /user', function(){
    Flight::json(Flight::userService()->get_all());
});

Flight::route('POST /user', function (){
    $new_user = Flight::request()->data->getData();
    Flight::json(Flight::userService()->add_element($new_user));
});

Flight::route('GET /user/@id', function ($id){
    Flight::json(Flight::userService()->get_by_id($id));
});

Flight::route('DELETE /user/@id', function ($id){
    Flight::userService()->delete_element($id);
    Flight::json(["message" => "User deleted."]);
});

Flight::route('PUT /user/@id', function ($id){
    $new_data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->update_element($id, $new_data));
});

?>