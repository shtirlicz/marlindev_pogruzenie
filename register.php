<?php
session_start();
require "function.php";

$email = $_POST['email'];
$password = $_POST['password'];

$user = get_user_by_email_or_id("register", $email);

//перенаправляем назад, если такой адрес уже занят
if(!empty($user)) {
    set_flash_message("danger", "Этот эл. адрес уже занят другим пользователем");
    redirect_to("page_register.php");
    exit;
}
add_user("register", $email, $password);

set_flash_message("success", "Регистрация успешна!");
redirect_to("page_login.php");



