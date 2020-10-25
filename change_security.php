<?php
session_start();
require "function.php";

$open_user_id = $_GET['id'];
$current_user_email = $_SESSION['email'];
$entered_email = $_POST['user_email'];
$entered_password = $_POST['user_password'];

$found_user = get_user_by_email_or_id("register", $entered_email, null);

if($current_user_email == $entered_email and empty($entered_password)) {
    redirect_to("/security.php?id=".$open_user_id);
}elseif(!empty($found_user['email']) and empty($entered_password)) {
    set_flash_message("danger", "Данный email адрес уже занят");
    redirect_to("/security.php?id=".$open_user_id);
}

if(empty($entered_password) and empty($found_user['email'])) {              //не меняли пароль, но изменили емаил
    edit_credentials("two_person", $open_user_id, $entered_email);
    if(is_author($_SESSION['id'], $open_user_id)){$_SESSION['email'] = $entered_email;}
}elseif(!empty($found_user['email']) and !empty($entered_password)) {       //не меняли емаил, но изменили пароль
    edit_credentials("register", $open_user_id, null, $entered_password);
}else {                                                                     //изменили емаил и ввели пароль
    edit_credentials("register", $open_user_id, $entered_email, $entered_password);
    if(is_author($_SESSION['id'], $open_user_id)){$_SESSION['email'] = $entered_email;}
}

set_flash_message("success", "Профиль успешно обновлен");
redirect_to("/security.php?id=".$open_user_id);

?>
