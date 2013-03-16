<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'config/config.php';

if(isset($_POST['noposts']))
{	
	$var = $_POST['noposts'];
	if($res = $conn->query("update Settings_proj2 set no_of_posts='$var' where id=1"))
	{
		echo "updated";
	}
}


if(isset($_POST['submitmax']))
{
	//echo "in";	
	$var = $_POST['maxattach'];
	if($res = $conn->query("update Settings_proj2 set maxattachments='$var' where id=1"))
	{
        echo "updated";
	}
}

$res = $conn->query("select no_of_posts from Settings_proj2 where id=1");
$counter= $res->fetch_row();
$count= $counter[0];

$res = $conn->query("select maxattachments from Settings_proj2 where id=1");
$maxattach= $res->fetch_row();
$max= $maxattach[0];


echo '<html>';
echo '<head>';
echo ' <style>';
echo 'p { background:#dad;';
echo 'font-weight:bold;';
echo 'font-size:16px; }';
echo '</style>';
echo '<script src="./js/jquery-latest.js"></script>';
echo '</head>';
echo '<body>';
echo '<h1>Pagination Settings</h1>';
echo '<p ><form  style= "border:solid 1px;padding: 15px;" method="post" action="">';
echo '<label> No of Thread/Posts per page: </label>';
echo '<select name="noposts" value="options">';
echo '<option value="'.$count.'">Select option</option>';
echo '<option value="5">5</option>';
echo '<option value="6">6</option>';
echo '<option value="7">7</option>';
echo '<option value="8">8</option>';
echo '<option value="9">9</option>';
echo '<option value="10">10</option>';
echo '<option value="11">11</option>';
echo '<option value="12">12</option>';
echo '<option value="13">13</option>';
echo '<option value="14">14</option>';
echo '<option value="15">15</option>';
echo '</SELECT>';
echo '<input name="update" type="submit" id="update" value="update">';
echo '</form>';
echo '<h1>Attachment Settings</h1>';
echo '	<form style= "border:solid 1px;padding: 15px;" method="post" action="" >';
echo '	<label> Max no of attachments </label>';
echo '		<select name="maxattach" value="options">';
echo '<option value="'.$max.'">Select option</option>';
echo '	<option value="5">5</option>';
echo '	<option value="10">10</option>';
echo '	<input type="submit" name="submitmax" value="submit" >';
echo '	</form>';
echo '</p>';
echo '<script>';
echo '$("button").click(function () {';
echo '$("form").toggle("slow");';
echo '});';
echo '</script>';
echo '</body>';
echo '</html>';
?>