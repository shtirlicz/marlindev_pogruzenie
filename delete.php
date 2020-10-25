<?php
session_start();
require "function.php";
$open_user_id = $_GET['id'];

if (is_not_logged_in()) {
    redirect_to("/page_login.php");
}

if (!check_admin() and !is_author($_SESSION['id'], $open_user_id)) {
    set_flash_message("danger", "Можно редактировать только свой профиль");
    redirect_to("/users.php");
}

$user = get_user_by_email_or_id("register", null, $open_user_id);

delete("register", $open_user_id);

if ($_SESSION['id'] == $open_user_id) {
    session_unset();
    session_destroy();
    redirect_to('/page_register.php');
} else {
    set_flash_message("success", "Пользователь " . $user['Name'] .  " удален");
    redirect_to("/users.php");
}

?>
