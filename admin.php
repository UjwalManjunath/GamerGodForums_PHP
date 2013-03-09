<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'config/config.php';
require_once 'Header.php';
include 'signin.php';
if((isset($_SESSION['user_level'])) && (($_SESSION['user_level']=="1")|| ($_SESSION['user_level']=="2")))
{
    echo '<br/>';
    echo '<div style="border:2px solid #a1a1a1;padding:10px 40px;margin-right:35px;background:#dddddd;width:75%;border-radius:25px;-moz-border-radius:25px ;float:right;">';
    echo '<iframe frameborder="0" scrolling="yes" width="100%" height="700px" src="./general_settings.php" name="imgbox" id="imgbox" >';
    echo '<p>iframes are not supported by your browser.</p>';
    echo '</iframe>';
    echo '</div ><br />';
    echo '<div style="border:2px solid #a1a1a1; margin-left:25px;padding:10px 40px; height:300px;background:#dddddd;width:25%;border-radius:25px;-moz-border-radius:25px">';
    echo '<a href="./general_settings.php" target="imgbox">General </a><br />';
    echo '<a href="./forumsettings.php" target="imgbox">Forum</a><br />';
    if((isset($_SESSION['user_level'])) && (($_SESSION['user_level']=="1") || ($_SESSION['user_level']=="2")))
    {
        echo '<a href="./usersettings.php" target="imgbox">Users</a>';
    }
}
else
{
    echo '<div id="titleBar_Helper"><h1>YOU DO NOT HAVE PERMISSIONS TO VIEW THIS PAGE</a>';
    echo '</h1>';
    echo '</div>';
}
echo '</div>';
require_once 'Footer.html'; 
?>