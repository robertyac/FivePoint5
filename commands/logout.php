<?php

session_start();
if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
    unset($_SESSION['user_id']);
    echo "You have been logged out";
} else {
    $_SESSION['alert'] = "You weren't logged in";
    echo "You weren't logged in";
}
header('Location: ../index.php');
