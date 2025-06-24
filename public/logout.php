<?php

require_once '../config/auth.php';
require_once '../config/db_config.php';

logout();
header("Location: " . BASE_URL . "/public/login.php");
exit;
?>
