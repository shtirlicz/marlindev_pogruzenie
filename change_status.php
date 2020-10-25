<?php
session_start();
require "function.php";

$open_user_id = $_GET['id'];
$current_user_email = $_SESSION['email'];
$entered_status = $_POST['status'];

set_status("register", $entered_status, $open_user_id);

set_flash_message("success", "Профиль успешно обновлен");
redirect_to("/status.php?id=".$open_user_id);

?>
