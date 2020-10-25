<?php

session_start();
require "function.php";
$open_user_id = $_GET['id'];
$user = get_user_by_email_or_id("register", null, $open_user_id);
if (empty($_FILES['image']['name'])) {
    redirect_to("/media.php?id=" . $open_user_id);
} else {
    if (!empty($user['img_avatar'])) {
        delete_avatar("register", $open_user_id);
    }
    upload_avatar($_FILES['image'], "register", $open_user_id); //загрузка аватара
}

set_flash_message("success", "Профиль успешно обновлен");
redirect_to("/page_profile.php?id=" . $open_user_id);

