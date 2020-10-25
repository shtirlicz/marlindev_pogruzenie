<?php
session_start();
require "function.php";

$user_id = $_GET['id'];
$data = $_POST;

edit("register", $data, $user_id);
set_flash_message("success", "Профиль успешно обновлен");
redirect_to("/edit.php?id=" . $user_id);
