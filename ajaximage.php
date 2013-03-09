<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'config/config.php';
$path = "images/uploads/profilepics/";
$uid= $_SESSION['user_id'];
	$valid_formats = array("jpg", "jpeg", "gif", "png");
	if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
		{
			$name = $_FILES['photoimg']['name'];
			$size = $_FILES['photoimg']['size'];
			
			if(strlen($name))
				{
					list($txt, $ext) = explode(".", $name);
					if(in_array($ext,$valid_formats))
					{
					if($size<(1024*1024))
						{
							$actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
						
							$tmp = $_FILES['photoimg']['tmp_name'];
							
								
							if(move_uploaded_file($tmp, $path.$actual_image_name))
								{
									if ($conn->query("update users_proj4 set profileimage='$actual_image_name' where user_id= $uid ") === TRUE) 
										echo "<a href=\"#\"><img src='images/uploads/profilepics/".$actual_image_name."' width=\"140\" height=\"140\"></a>";
									else
										echo " update error";
								}
							else
								echo "failed";
						}
						else
						echo "Image file size max 1 MB";					
						}
						else
						echo "Invalid file format..";	
				}
				
			else
				echo "Please select image..!";
				
			exit;
		}
?>