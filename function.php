<?php
session_start();


function get_user_by_email_or_id($table, $email = "", $user_id = "")
{
    $pdo = new PDO("mysql:host=localhost;dbname=new_leson", "root", "root");

    if(!empty($email)) {
    $sql = "SELECT * FROM $table WHERE email=:email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
}elseif(!empty($user_id)){
    $sql = "SELECT * FROM $table WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(["id" => $user_id]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
}else{
    $sql = "SELECT * FROM $table";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $user = $statement->fetchAll(PDO::FETCH_ASSOC);
}

    return $user;
};
function login($table, $email, $password) {

    $user = get_user_by_email_or_id($table, $email);


    if(empty($user)) {
        return "email not found";
    }elseif(!password_verify($password, $user['password'])) {
        return "password not found";
    }else {
        return $user;
    }

}
function add_user($table, $email, $password) {
    $pdo = new PDO("mysql:host=localhost;dbname=new_leson", "root", "root");
    $sql = "INSERT INTO $table(email, password) VALUES (:email, :password)";
    $statement = $pdo->prepare($sql);
    $result = $statement->execute(["email" => $email,
        "password" => password_hash($password, PASSWORD_DEFAULT)
    ]);
    return $pdo->lastInsertId();

};
function edit($table, $data, $user_id) {
    $fields = '';

    foreach($data as $key => $value) {
        if($key == "Name" || $key == "position" || $key == "telephon" || $key == "adres" || $key == "role"){
            $fields .= $key . "=:" . $key . ",";
        }else {
            unset($data[$key]);
        }
    }

    $data += ['id'=>$user_id];
    $fields = rtrim($fields, ',');

    $pdo = new PDO("mysql:host=localhost;dbname=new_leson", "root", "root");
    $sql = "UPDATE $table SET $fields WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute($data);
};
function set_status($table, $status, $user_id) {
    $pdo = new PDO("mysql:host=localhost;dbname=new_leson", "root", "root");
    $sql = "UPDATE $table SET status=:status WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(["status" => $status,
        "id" => $user_id
    ]);
};
function add_social_links($table, $data, $user_id) {
    $fields = '';

    foreach($data as $key => $value) {
        if($key == "vk" || $key == "telegram" || $key == "instagram"){
            $fields .= $key . "=:" . $key . ",";
        }else {
            unset($data[$key]);
        }
    }

    $data += ['id'=>$user_id];
    $fields = rtrim($fields, ',');

    $pdo = new PDO("mysql:host=localhost;dbname=new_leson", "root", "root");
    $sql = "UPDATE $table SET $fields WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute($data);
};
function set_flash_message($name, $message){
    $_SESSION[$name] = $message;
    $_SESSION['status_message'] = $name;

};
function display_flash_message($name){
    if(isset($_SESSION[$name])) {
        echo "<div class=\"alert alert-{$name} text-dark\" role=\"alert\">{$_SESSION[$name]}</div>";
        unset($_SESSION[$name]);
        unset($_SESSION['status_message']);
    }
};
function redirect_to($path){
    header("Location: {$path}");

    exit;
};
function is_not_logged_in () {

    if(isset($_SESSION['email']) && !empty($_SESSION['email'])) {
        return false;
    }

    return true;
};
function check_admin () {
    if($_SESSION['role'] == "admin") {
        return true;
    }
    return false;
};
function upload_avatar($image, $table, $user_id) {


    $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . "." . $extension;

    if(move_uploaded_file($image['tmp_name'], "img/avatar/" . $filename))


        $pdo = new PDO("mysql:host=localhost;dbname=new_leson", "root", "root");
    $sql = "UPDATE $table SET img_avatar=:img_avatar WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(["img_avatar" => $filename,
        "id" => $user_id
    ]);
};
function is_author ($logger_user_id, $edit_user_id) {
    if ($logger_user_id == $edit_user_id) {
        return true;
    }
    return false;
};
function edit_credentials($table, $user_id, $email = null, $password = null) {
    $pdo = new PDO("mysql:host=localhost;dbname=new_leson", "root", "root");

    if($email == null) {
        $sql = "UPDATE $table SET password=:password WHERE id=:id";
        $statement = $pdo->prepare($sql);
        $statement->execute(["id" => $user_id,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }elseif($password == null) {
        $sql = "UPDATE $table SET email=:email WHERE id=:id";
        $statement = $pdo->prepare($sql);
        $statement->execute(["id" => $user_id,
            "email" => $email
        ]);
    }else{
        $sql = "UPDATE $table SET email=:email, password=:password  WHERE id=:id";
        $statement = $pdo->prepare($sql);
        $statement->execute(["id" => $user_id,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
};
function has_image($user_id, $table) {
    $user_img = get_user_by_email_or_id($table, null, $user_id);

    if(empty($user_img['img_avatar'])) {
        return false;
    }
    return true;
}
function delete_avatar ($table, $user_id) {

    $img_for_delete = get_user_by_email_or_id($table, null, $user_id);
    unlink("img/avatar/" . $img_for_delete['img_avatar']);

    $pdo = new PDO("mysql:host=localhost;new_leson", "root", "root");
    $sql = "UPDATE $table SET img_avatar=:img_avatar WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(["img_avatar" => NULL,
        "id" => $user_id
    ]);

}
function delete($table, $user_id) {

    $img_for_delete = get_user_by_email_or_id($table, null, $user_id);
    if (!empty($img_for_delete['img_avatar'])){
        unlink("img/avatar/" . $img_for_delete['img_avatar']);
    }

    $pdo = new PDO("mysql:host=localhost;dbname=new_leson", "root", "root");
    $sql = "DELETE FROM $table WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(":id", $user_id);
    $statement->execute();
}

