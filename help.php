<?php
include("includes/header.php");

if(!($_SESSION['tipo'] == "normal"))
{
    header("Location: home.php");
}
?>
