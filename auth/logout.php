<?php
session_start(); // Start the Session

session_unset(); // Unset the Session

session_destroy(); // Destroy the Session

//To move user into the login page

header('Location: ../index.php');

exit();
