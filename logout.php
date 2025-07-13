<?php
include "includes/System.class.php";
require_once "includes/start.php";
$login = new LoginSystem();
$id = $_SESSION["idusername"]; 
$login->logout($id);
header("Location: login.php");
exit;
?>