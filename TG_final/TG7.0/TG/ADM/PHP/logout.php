<?php
session_start();
require("redirect.php");

session_destroy();
redirect("../../MenuLogin.html");
?>