<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/php-login-signup-api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';
 
// instantiate user object
include_once '../objects/user.php';
 
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// set user property values
$user->username = $data->username;
$user->password = base64_encode($data->password);
$user->created = date('Y-m-d H:i:s');
 
// create the user
if(
    !empty($user->username) &&
    !empty($user->password) &&
    $user->signup()
){
 // set response code
    http_response_code(200);
 
    // display message: user was created
    echo json_encode(array("message" => "User was created."));
}
// message if unable to create user
elseif($user->isAlreadyExist()){
 
    // set response code
    http_response_code(400);
 
    // display message: unable to create user
    echo json_encode(array("message" => "Already exist."));
}else{
    // set response code
    http_response_code(400);
 
    // display message: unable to create user
    echo json_encode(array("message" => "Unable to create user."));
}
?>