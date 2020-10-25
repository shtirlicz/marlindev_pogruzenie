<?php
session_start();
require "function.php";

$email = $_POST['email'];
$password = $_POST['password'];
$data = $_POST;
$user = get_user_by_email_or_id("register", $email);

if (!empty($user)) {
    set_flash_message("danger", "Этот эл. адрес уже занят другим пользователем");
    redirect_to("/users.php");
}

$user_id = add_user("register", $email, $password);
edit("register", $data, $user_id);
set_status("register", $data['status'], $user_id);
add_social_links("register", $data, $user_id); //добавление ссылок соц. сетей
upload_avatar($_FILES['img_avatar'], "register", $user_id); //загрузка аватара **/
set_flash_message("success", "Профиль успешно создан");
redirect_to("/users.php");


?>
