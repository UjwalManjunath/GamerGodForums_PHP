<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'config/config.php';

if(isset($_POST['noposts']))
{
	//echo "in";	
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

?>
<html>
<head>
  <style>
p { background:#dad;
font-weight:bold;
font-size:16px; }
</style>
  <script src="./js/jquery-latest.js"></script>
</head>
<body>
 

						<h1>Pagination Settings</h1>
						
<p ><form  style= "border:solid 1px;padding: 15px;" method="post" action="">
<label> No of Thread/Posts per page: </label>
<select name="noposts" value="options">
<?php echo '<option value="'.$count.'">Select option</option>'?>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
</SELECT>
<input name="update" type="submit" id="update" value="update">
</form>

<h1>Attachment Settings</h1>
				
						<form style= "border:solid 1px;padding: 15px;" method="post" action="" >
						<label> Max no of attachments </label>
						<select name="maxattach" value="options">
						<?php echo '<option value="'.$max.'">Select option</option>'?>
						<option value="5">5</option>
						<option value="10">10</option>
						<input type="submit" name="submitmax" value="submit" >
						</form>
</p>

<script>
$("button").click(function () {
$("form").toggle("slow");
});    
</script>

</body>
</html>