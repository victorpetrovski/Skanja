<?php
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

include("../vendor/autoload.php");
use \Instagram\Instagram;

$instagram = new \Instagram\Instagram();

if (isset($_SESSION["user_session"]))
    $instagram->initFromSavedSession($_SESSION["user_session"]);
else
    header("Location: login_form.php");

if ($instagram->getLoggedInUser() != null) {
    header("Location: user.php");
    exit();
}else {
    header("Location: login_form.php");
    exit();
}
