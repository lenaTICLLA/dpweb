<?php
require_once "config/config.php";
session_start();
session_destroy();
header("Location: " . BASE_URL . "login");
exit;
