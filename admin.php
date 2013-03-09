<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'config/config.php';
require_once 'Header.php';
include 'signin.php';
if((isset($_SESSION['user_level'])) && (($_SESSION['user_level']=="1")|| ($_SESSION['user_level']=="2")))
{
?>
<br/>
<div style="border:2px solid #a1a1a1;
padding:10px 40px; 
margin-right:35px;
background:#dddddd;
width:75%;
border-radius:25px;
-moz-border-radius:25px ;float:right;">
<iframe frameborder="0" scrolling="yes" width="100%" height="700px" 
   src="./general_settings.php" name="imgbox" id="imgbox" >
   <p>iframes are not supported by your browser.</p>
 </iframe>
 </div ><br />
 <div style="border:2px solid #a1a1a1;
 margin-left:25px;
padding:10px 40px; height:300px;
background:#dddddd;
width:25%;
border-radius:25px;
-moz-border-radius:25px">
 <a href="./general_settings.php" target="imgbox">General </a><br />
 <a href="./forumsettings.php" target="imgbox">Forum</a><br />
<?php
if((isset($_SESSION['user_level'])) && (($_SESSION['user_level']=="1") || ($_SESSION['user_level']=="2")))
{
?>
 <a href="./usersettings.php" target="imgbox">Users</a> 
 <?php
}
}
else
{
echo '<div id="titleBar_Helper"><h1>YOU DO NOT HAVE PERMISSIONS TO VIEW THIS PAGE</a>';
		echo '</h1>';
		echo '</div>';
}
?>
</div>
<?php
require_once 'Footer.html'; 
?>