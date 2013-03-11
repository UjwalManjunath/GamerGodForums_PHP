<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'config/config.php';
require_once 'Header.php';
include 'signin.php';
$sentmail=0;

if (!class_exists('KeyCAPTCHA_CLASS'))
{
	// Replace '/home/path_to_keycaptcha_file/' with the real path to keycaptcha.php
	include('keycaptcha.php');
}
$kc_o = new KeyCAPTCHA_CLASS();
if ($kc_o->check_result($_POST['capcode'])) 
{
	// A visitor solved KeyCAPTCHA task correctly
	// Add your code that will save the data of your form
	if($_POST['username_email']=="")
	{
		echo "*firstname field  empty \r\n";
	}	
	else
	{
		$username_email=  explode("@",$_POST['username_email']);
		$username= $username_email[0];
		if( $result= $conn ->query("select user_pass,user_email from users_proj4 where user_name='$username' and user_status=0"))
		{
			if(mysqli_num_rows($result) > 0)
			{
				$temp = $result->fetch_row();
				$user_pass= $temp[0];
				$email= $temp[1];
				$to=$email;
				$subject="Your Password Reminder!!";
				
				$message = "
<html>
<head>
<title>Password Reminder</title>
</head>
<body>
<p>Your username and Password for GAMER GOD FORUMS!</p>
<table>
<tr>
<th>USERNAME</th>
<th>PASSWORD</th>
</tr>
<tr>
<td><b>$username</b></td>
<td><b>$user_pass</b></td>
</tr>
</table>
</body>
</html>
";
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

				// More headers
				$headers .= 'From: <webmaster@gamer.com>' . "\r\n";
				$sentmail = mail($to,$subject,$message,$headers);
				if($sentmail)
				{
					echo "Your Paasword Has Been Sent To Your Email Address.";
					
				}
				else 
				{
					echo "Error sending Password to your e-mail address";
				}
				
				
			
			
			}
			else
			{
                echo "username or email not found";
			}
		
		
			
		}
		else
		{
            echo "error in query";
		}
		
		
	}
	
}
else
{
	// A visitor solved KeyCAPTCHA task incorrectly
	// Add your code that will generate an error message
}


if(!$sentmail)
{
    echo '<div class="pageContent">';
    echo '<div class="titleBar">';
    echo '<div id="titleBar_Helper"><h1>Lost Password';
    echo '</h1>';
    echo '</div>';
    echo '</div>';
    echo '<div id="azk99838" style="text-align: center;"></div>';
    echo '<form action="" method="post" class="xenForm formOverlay">';
    echo 'If you have forgotten your password, you can use this form to reset your password. You will receive an email with instructions.';
    echo '<dl class="ctrlUnit">';
    echo '<dt><label for="ctrl_username_email">*Username or Email:</label></dt>';
    echo '<dd><input type="text" name="username_email" class="textCtrl" id="ctrl_username_email" autofocus="true"></dd>';
    echo '</dl>';
    echo '<fieldset>';
    echo '<dl class="ctrlUnit">';
    echo '<dt>Verification:</dt>';
    echo '<dd>';
    echo '<table id="keycaptcha_verification"><tbody><tr><td>';
    echo '<input type="hidden" name="capcode" id="capcode" value="false" />';

    if (!class_exists('KeyCAPTCHA_CLASS'))
    {
        // Replace '/home/path_to_keycaptcha_file/' with the real path to keycaptcha.php
        include('keycaptcha.php');
    }
    $kc_o = new KeyCAPTCHA_CLASS();
    echo $kc_o->render_js();

    echo '</td></tr></tbody></table></dd>';
    echo '</dl>';
    echo '</fieldset>';
    echo '<dl class="ctrlUnit submitUnit">';
    echo '<dt></dt>';
    echo '<dd><input type="submit" value="Reset Password" accesskey="s" class="button primary"></dd>';
    echo '</dl>';
    echo '<input type="hidden" name="_xfToken" value="">';
    echo '</form>';
    echo '<div id="azk44987" style="text-align: center;"></div>';
    echo '</div>';
}
require_once 'Footer.html'; 
?>