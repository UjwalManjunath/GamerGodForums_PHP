<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
//require_once 'classes/View.php';
require_once 'helpers/validation.php';
require_once 'config/config.php';
require_once 'Header.php';
include 'signin.php';

$result = processPost();	 
echo $result['showBox'];
if ($result['showBox']) {
	$id= $_GET['id'];
	echo messageBox(true, $result['errorHTML'],$id);
} else {
	echo $result['okMessage'];

}      

require_once 'Footer.html'; 
?>