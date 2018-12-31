<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
require 'database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));

//CREATE MESSAGE ARRAY AND SET EMPTY
$msg['message'] = '';

// CHECK IF RECEIVED DATA FROM THE REQUEST
if(isset($data->title) && isset($data->body) && isset($data->author)){
    // CHECK DATA VALUE IS EMPTY OR NOT
    if(!empty($data->title) && !empty($data->body) && !empty($data->title)){
        
        $insert_query = "INSERT INTO `posts`(title,body,author) VALUES(:title,:body,:author)";
        
        $insert_stmt = $conn->prepare($insert_query);
        // DATA BINDING
        $insert_stmt->bindValue(':title', htmlspecialchars(strip_tags($data->title)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':body', htmlspecialchars(strip_tags($data->body)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':author', htmlspecialchars(strip_tags($data->author)),PDO::PARAM_STR);
        
        if($insert_stmt->execute()){
            $msg['message'] = 'Data Inserted Successfully';
        }else{
            $msg['message'] = 'Data not Inserted';
        } 
        
    }else{
        $msg['message'] = 'Oops! empty field detected. Please fill all the fields';
    }
}
else{
    $msg['message'] = 'Please fill all the fields | title, body, author';
}
//ECHO DATA IN JSON FORMAT
echo  json_encode($msg);
?>