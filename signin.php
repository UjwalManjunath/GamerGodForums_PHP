<?php
if(isset($_COOKIE['keep_meuser']) && isset($_COOKIE['keep_mepass']))
{
	$usorlogin =  $_COOKIE['keep_meuser'];
	$passwrd = $_COOKIE['keep_mepass'];
	$results=$conn->query("SELECT user_id,user_name,user_level,user_suspended FROM	users_proj4 where user_name = '$usorlogin' And user_pass = '$passwrd' and user_status=0");
			$num_rows = mysqli_num_rows($results);
			if($num_rows == 0) 
			{
				$_SESSION['signedin'] = false;
			}
			else
			{
				$_SESSION['signedin'] =true;
				while ($row = $results->fetch_assoc())
				{
					$_SESSION['user_id'] 	= $row['user_id'];
					$_SESSION['user_name'] 	= $row['user_name'];
					$_SESSION['user_level'] = $row['user_level'];
					$_SESSION['user_suspended'] = $row['user_suspended'];
					
				}
			}
}
				



if(!((isset($_SESSION['signedin'])) && ($_SESSION['signedin']==TRUE)))
{
	
	if(!empty($_POST['user']))
	{
		if(!empty($_POST['pass']))
		{
			$user = $_POST['user'];
			//echo $user;
			$pass = $_POST['pass'];
			//$query = "SELECT	user_id,user_name,user_level FROM	users_proj4 where user_name = '$user' And user_pass = '$pass'";
			$results=$conn->query("SELECT	user_id,user_name,user_level,user_suspended FROM	users_proj4 where user_name = '$user' And user_pass = '$pass' and user_status=0");
			//echo "SELECT	user_id,user_name,user_level,user_suspended FROM	users_proj4 where user_name = '$user' And user_pass = '$pass' and user_status=0";
			$num_rows = mysqli_num_rows($results);
			if($num_rows == 0) 
			{
				$_SESSION['signedin'] = false;
			}
			else
			{
				$_SESSION['signedin'] =true;
				while ($row = $results->fetch_assoc())
				{
					$_SESSION['user_id'] 	= $row['user_id'];
					$_SESSION['user_name'] 	= $row['user_name'];
					$_SESSION['user_level'] = $row['user_level'];
					$_SESSION['user_suspended'] = $row['user_suspended'];
				}
				if(isset($_POST['rememberme'])) 
				{
					//set the cookies for 1 day, ie, 1*24*60*60 secs
					//change it to something like 30*24*60*60 to remember user for 30 days
					setcookie('username', $user, time() + 1*24*60*60);
					setcookie('password', $pass, time() + 1*24*60*60);
				}
				else 
				{
					//destroy any previously set cookie
					setcookie('username', '', time() - 1*24*60*60);
					setcookie('password', '', time() - 1*24*60*60);
				}
				if(isset($_POST['loggedin']))
				{
					setcookie("keep_meuser",$user,time() + 1*24*60*60);
					setcookie("keep_mepass",$pass,time() + 1*24*60*60);
				}
				else 
				{
					//destroy any previously set cookie
					setcookie('keep_meuser', '', time() - 1*24*60*60);
					setcookie('keep_mepass', '', time() - 1*24*60*60);
				}
				
			}
			if((isset($_SESSION['user_id']))&&($_SESSION['signedin']==true)) 
			{
				$uid=$_SESSION['user_id'];
				echo '<script language="javascript">';
				echo 'document.getElementById(\'welcome\').innerHTML = "Hello '.$_SESSION['user_name'].' ";';
				echo 'document.getElementById(\'open\').innerHTML = "Logout";';
				echo 'document.getElementById(\'open\').href="logout.php";';
				echo '</script>';

				if((isset($_SESSION['user_level'])) && (($_SESSION['user_level']=="1") || ($_SESSION['user_level']=="2")))
				{
					echo '<script language="javascript">';
					echo 'document.getElementById(\'Settingsuser\').innerHTML="<li ><a id= \"Settings\" href=\"admin.php\" style=\" float:right;\"><img src=\"./images/setting.png\" /></a></li><li><a href=\"member.php?id=<?php echo $uid ?>\">Profile</a></li>";';
					echo '</script>';
				}
				else 
					if((isset($_SESSION['user_level'])) && (($_SESSION['user_level']=="0") ))
					{
						echo '<script language="javascript">';
						echo 'document.getElementById(\'Settingsuser\').innerHTML="<li><a href=\"member.php?id=<?php echo $uid ?>\">Profile</a></li>";';
						echo '</script>';
					}
			
			}
			else
			{
				echo '<script language="javascript">';
				echo 'alert("Invalid username and password . Please try again");';
				echo '</script>';
	
			}
		}
	}
}
else 
	if($_SESSION['signedin'])
	{
		$uid=$_SESSION['user_id'];
		echo '<script language="javascript">';
		echo 'document.getElementById(\'welcome\').innerHTML = "Hello '.$_SESSION['user_name'].'";';
		echo 'document.getElementById(\'open\').innerHTML = "Logout";';
		echo 'document.getElementById('open').href="logout.php";';
		echo '</script>';
		if((isset($_SESSION['user_level'])) && (($_SESSION['user_level']=="1") || ($_SESSION['user_level']=="2")))
		{
			echo '<script language="javascript">';
			echo 'document.getElementById(\'Settingsuser\').innerHTML="<li ><a id= \"Settings\" href=\"admin.php\" style=\" float:right;\"><img src=\"./images/setting.png\" /></a></li><li><a href=\"member.php?id=<?php echo $uid ?>\">Profile</a></li>";';
			echo '</script>';
		}
		else 
			if((isset($_SESSION['user_level'])) && (($_SESSION['user_level']=="0") ))
			{
				echo '<script language="javascript">';
				echo 'document.getElementById(\'Settingsuser\').innerHTML="<li><a href=\"member.php?id='.$uid.'\">Profile</a></li>";';
				echo '</script>';
			}
	}
?>