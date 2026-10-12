<?php
session_start();

if (@isset($_SESSION['loginadmin']) && @$_SESSION['loginadmin'] <> '') {
    session_destroy();
    header("Location:login.php");
} else {
    $AccessAddress = $file;
    include_once '../404.php';
}
