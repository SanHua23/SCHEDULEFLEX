<?php
session_start();
if (!isset($_SESSION['user']['admin_id'])  || $_SESSION['user']['user_role'] != '1') {
    header('Location: login');
}

?>