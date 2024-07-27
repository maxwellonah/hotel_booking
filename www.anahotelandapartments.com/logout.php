<?php
session_start();

session_unset();
session_destroy();

header("Location: http://localhost/IT/2/index1.php");
?>