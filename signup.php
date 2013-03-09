<?php
session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
require_once 'config/config.php';
require_once 'Header.php';
include 'signin.php';
$flag=0;
$sentmail=0;

if (!class_exists('KeyCAPTCHA_CLASS')) 
{
	// Replace '/home/path_to_keycaptcha_file/' with the real path to keycaptcha.php
	include('keycaptcha.php');
}
$kc_o = new KeyCAPTCHA_CLASS();

if ($kc_o->check_result($_POST['capcode'])) 
{
	if($_POST['firstname']==""){
		$err= "*firstname field  empty \r\n";
		$flag=1;
		}
	if($_POST['lastname']==""){
		$err .="*lastname field  empty \r\n";
		$flag=1;
		}
	if($_POST['password']=="" || $_POST['password_confirm']=="" ){
		$err .="*password field  empty\r\n";
			$flag=1;
			}
	if(	$c = strcmp ( $_POST['password'] , $_POST['password_confirm'] )!=0 ){
	$err .= "*Passwords did not match\r\n";
	$flag=1;
	}
	if( $_FILES['photoimg']['size'] > 1024* 1024 )
	{
		$err .= "* image size too large\r\n";
		$flag=1;
	}
		
	
	if($_POST['timezone']==""){
	$err .="*Select your timezone \r\n";
	$flag=1;
	}
	if(!$flag)
	{
	// A visitor solved KeyCAPTCHA task correctly
	// Add your code that will save the data of your form
	$firstname=$_POST['firstname'];
	$lastname=$_POST['lastname'];
	$email=$_POST['email'];
	$temp= explode("@",$email);
	$username = $temp[0];
	$pass1 = $_POST['password'];
	$pass2=$_POST['password_confirm'];
	//$month=$_POST['dob_month'];
	
	
	//$year=$_POST['dob_year'];
	$gender=$_POST['gender'];
	$timezone=$_POST['timezone'];
	$format=$_POST['format'];
	$confirm_code=md5(uniqid(rand())); 
	
	/* code for image upload  */
	$valid_formats = array("jpg", "jpeg", "gif", "png");

	$name = $_FILES['file']['name'];
	$size = $_FILES['file']['size'];
			
			if(strlen($name))
				{
					list($txt, $ext) = explode(".", $name);
					if(in_array($ext,$valid_formats))
					{
					if($size<(1024*1024))
					{
							$actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
						
							$tmp = $_FILES['file']['tmp_name'];
					if(!(move_uploaded_file($tmp, "images/uploads/temp/". $actual_image_name)))
						echo "moving image error";
			
			}
						else
						echo "Image file size max 1 MB";					
						}
						else
						echo "Invalid file format..";	
				}
				
				
				
			
			
			
			
			
			
			
	/* end code */
	
	//$q= "Insert into temp_members_db('$confirm_code','$firstname','$lastname','$username','$email','$pass1','$dob','$gender','$timezone')";
	//echo $q;

		if(($result = $conn->query("Insert into temp_members_db values('$confirm_code','$firstname','$lastname','$username','$email','$pass1','$gender','$timezone','$actual_image_name')")))
		{

		$email = htmlspecialchars(stripslashes(strip_tags($email)));
		$domain = explode("@", $email );
		$fp = fsockopen($domain[1], 80, $errno, $errstr, 30);
		if (!$fp) 
		{
			echo "$errstr ($errno)<br />\n";
		} 
		else 
		{
            //if the connection can be established, the email address is probabley valid
			
            //echo "valid";
			$to=$email;
			$subject="Your confirmation link here";
			//$header="from: GAMERGODFORUMS";
			if($format == "text")
			{
				$message="Your Comfirmation link \r\n";
				$message.="Click on this link to activate your account \r\n";
				$message.="https://mweigle418.cs.odu.edu/~umanjuna/proj4/confirmation.php?passkey=$confirm_code";
				$headers .= 'From: <webmaster@gamer.com>' . "\r\n";
			}
			else
			if( $format == "html")
			{
$message = '
<html>
<head>
<title>HTML email</title>
</head>
<body>
<h2>Welcome to Gamer God forums</h2>
<img border="0" src="https://mweigle418.cs.odu.edu/~umanjuna/proj4/images/banner_all_games.png" alt="Pulpit rock" >

<h2> Click here to activate your account : <a href="https://mweigle418.cs.odu.edu/~umanjuna/proj4/confirmation.php?passkey='.$confirm_code.'"><img src="https://mweigle418.cs.odu.edu/~umanjuna/proj4/images/activate.jpg" alt="active" ></a>


</body>
</html>
';

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

// More headers
$headers .= 'From: <webmaster@gamer.com>' . "\r\n";
			}
			$sentmail = mail($to,$subject,$message,$headers);
			if($sentmail)
			{
				echo "Your Confirmation link Has Been Sent To Your Email Address.";
				
			}
			else 
			{
				echo "Cannot send Confirmation link to your e-mail address";
			}

	//echo "A confirmation email has been sent";
		}
	}
	else 
	{
		echo "inserting error";
	}
	  
	  
}
else
{
echo $err;
}
}
else 
{
	// A visitor solved KeyCAPTCHA task incorrectly
	// Add your code that will generate an error message
	//echo "error";
}






if(!$sentmail)
{

?>



 <div class="pageContent">

 
<div class="titleBar">
<div id="titleBar_Helper"><h1>Sign Up
</h1>
</div>
</div>
<div id="azk99838" style="text-align: center;"></div>

<table width="100%">
<tr>
<td width= "65%" style="    vertical-align: top;">
<form action="#" method="post" class="xenForm AutoValidator"  enctype="multipart/form-data">


<dl class="ctrlUnit">
<dt style="    display: inline;"><label for="ctrl_username">*FirstName:</label></dt>
<dd style="    display: inline;">
<input type="text" name="firstname" value="<?php echo $_POST['firstname'];?>" class="textCtrl" id="ctrl_frstname" autofocus="true" autocomplete="off">

</dd>
</dl>
<dl class="ctrlUnit">
<dt style="    display: inline;"><label for="ctrl_username">*LastName:</label></dt>
<dd style="    display: inline;">
<input type="text" name="lastname" value="<?php echo $_POST['lastname'];?>" class="textCtrl" id="ctrl_lstname" autofocus="true" autocomplete="off">

</dd>
</dl>
<dl class="ctrlUnit">
<dt style="    display: inline;"><label for="ctrl_email">*Email:</label></dt>
<dd style="    display: inline;"><input type="email" name="email" value="<?php echo $_POST['email'];?>" dir="ltr" class="textCtrl" id="ctrl_email"></dd>
</dl>
<fieldset>
<dl class="ctrlUnit">
<dt style="    display: inline;"><label for="ctrl_password">*Password:</label></dt>
<dd style="    display: inline;"><input type="password" name="password" class="textCtrl OptOut" id="ctrl_password" autocomplete="off"></dd>
</dl>
<dl class="ctrlUnit">
<dt style="    display: inline;"><label for="ctrl_confirm_password">*Confirm Password:</label></dt>
<dd style="    display: inline;">
<input type="password" name="password_confirm" class="textCtrl OptOut" id="ctrl_confirm_password">
<p class="explain" >                       Enter your password in the first box and confirm it in the second.</p>
</dd>
</dl>
</fieldset>

<dl class="ctrlUnit">
<dt style="    display: inline;">Gender:</dt>
<dd style="    display: inline;">
<ul>
<li style="    display: inline;"><label for="ctrl_gender_male"><input type="radio" name="gender" value="male" checked="checked" id="ctrl_gender_male"> Male</label></li>
<li style="    display: inline;"><label for="ctrl_gender_female"><input type="radio" name="gender" value="female" id="ctrl_gender_female"> Female</label></li>

</ul>
</dd>
</dl>

</br>
<dl class="ctrlUnit">
<dt style="    display: inline;"><label for="ctrl_timezone">*Time zone:</label></dt>
<dd style="    display: inline;">
<select name="timezone" class="textCtrl AutoTimeZone OptOut" id="ctrl_timezone">
<option value="Pacific/Midway">(UTC-11:00) American Samoa</option>
<option value="Pacific/Apia">(UTC-11:00) Apia, Samoa</option>
<option value="Pacific/Honolulu">(UTC-10:00) Hawaii</option>
<option value="Pacific/Marquesas">(UTC-09:30) Marquesas Islands</option>
<option value="America/Anchorage">(UTC-09:00) Alaska</option>
<option value="America/Los_Angeles">(UTC-08:00) Pacific Time (US &amp; Canada)</option>
<option value="America/Santa_Isabel">(UTC-08:00) Baja California</option>
<option value="America/Tijuana">(UTC-08:00) Tijuana</option>
<option value="America/Denver">(UTC-07:00) Mountain Time (US &amp; Canada)</option>
<option value="America/Chihuahua">(UTC-07:00) Chihuahua, La Paz, Mazatlan</option>
<option value="America/Phoenix">(UTC-07:00) Arizona</option>
<option value="America/Chicago" selected="selected">(UTC-06:00) Central Time (US &amp; Canada)</option>
<option value="America/Belize">(UTC-06:00) Saskatchewan, Central America</option>
<option value="America/Mexico_City">(UTC-06:00) Guadalajara, Mexico City, Monterrey</option>
<option value="Pacific/Easter">(UTC-06:00) Easter Island</option>
<option value="America/New_York">(UTC-05:00) Eastern Time (US &amp; Canada)</option>
<option value="America/Havana">(UTC-05:00) Cuba</option>
<option value="America/Bogota">(UTC-05:00) Bogota, Lima, Quito</option>
<option value="America/Caracas">(UTC-04:30) Caracas</option>
<option value="America/Halifax">(UTC-04:00) Atlantic Time (Canada)</option>
<option value="America/Goose_Bay">(UTC-04:00) Atlantic Time (Goose Bay)</option>
<option value="America/Asuncion">(UTC-04:00) Asuncion</option>
<option value="America/Santiago">(UTC-04:00) Santiago</option>
<option value="America/Cuiaba">(UTC-04:00) Cuiaba</option>
<option value="America/La_Paz">(UTC-04:00) Georgetown, La Paz, Manaus, San Juan</option>
<option value="Atlantic/Stanley">(UTC-04:00) Falkland Islands</option>
<option value="America/St_Johns">(UTC-03:30) Newfoundland</option>
<option value="America/Argentina/Buenos_Aires">(UTC-03:00) Buenos Aires</option>
<option value="America/Argentina/San_Luis">(UTC-03:00) San Luis</option>
<option value="America/Argentina/Mendoza">(UTC-03:00) Argentina, Cayenne, Fortaleza</option>
<option value="America/Godthab">(UTC-03:00) Greenland</option>
<option value="America/Montevideo">(UTC-03:00) Montevideo</option>
<option value="America/Sao_Paulo">(UTC-03:00) Brasilia</option>
<option value="America/Miquelon">(UTC-03:00) Saint Pierre and Miquelon</option>
<option value="America/Noronha">(UTC-02:00) Mid-Atlantic</option>
<option value="Atlantic/Cape_Verde">(UTC-01:00) Cape Verde Is.</option>
<option value="Atlantic/Azores">(UTC-01:00) Azores</option>
<option value="Europe/London">(UTC) Dublin, Edinburgh, Lisbon, London</option>
<option value="Africa/Casablanca">(UTC) Casablanca</option>
<option value="Atlantic/Reykjavik">(UTC) Monrovia, Reykjavik</option>
<option value="Europe/Amsterdam">(UTC+01:00) Central European Time</option>
<option value="Africa/Algiers">(UTC+01:00) West Central Africa</option>
<option value="Africa/Windhoek">(UTC+01:00) Windhoek</option>
<option value="Africa/Tunis">(UTC+01:00) Tunis</option>
<option value="Europe/Athens">(UTC+02:00) Eastern European Time</option>
<option value="Africa/Johannesburg">(UTC+02:00) South Africa Standard Time</option>
<option value="Asia/Amman">(UTC+02:00) Amman</option>
<option value="Asia/Beirut">(UTC+02:00) Beirut</option>
<option value="Africa/Cairo">(UTC+02:00) Cairo</option>
<option value="Asia/Jerusalem">(UTC+02:00) Jerusalem</option>
<option value="Europe/Minsk">(UTC+02:00) Minsk</option>
<option value="Asia/Gaza">(UTC+02:00) Gaza</option>
<option value="Asia/Damascus">(UTC+02:00) Syria</option>
<option value="Africa/Nairobi">(UTC+03:00) Nairobi, Baghdad, Kuwait, Qatar, Riyadh</option>
<option value="Europe/Kaliningrad">(UTC+03:00) Kaliningrad</option>
<option value="Asia/Tehran">(UTC+03:30) Tehran</option>
<option value="Europe/Moscow">(UTC+04:00) Moscow, St. Petersburg, Volgograd</option>
<option value="Asia/Dubai">(UTC+04:00) Abu Dhabi, Muscat, Tbilisi</option>
<option value="Asia/Yerevan">(UTC+04:00) Yerevan</option>
<option value="Asia/Baku">(UTC+04:00) Baku</option>
<option value="Indian/Mauritius">(UTC+04:00) Mauritius</option>
<option value="Asia/Kabul">(UTC+04:30) Kabul</option>
<option value="Asia/Tashkent">(UTC+05:00) Tashkent, Karachi</option>
<option value="Asia/Kolkata">(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
<option value="Asia/Kathmandu">(UTC+05:45) Kathmandu</option>
<option value="Asia/Dhaka">(UTC+06:00) Astana, Dhaka</option>
<option value="Asia/Yekaterinburg">(UTC+06:00) Ekaterinburg</option>
<option value="Asia/Almaty">(UTC+06:00) Almaty, Bishkek, Qyzylorda</option>
<option value="Asia/Rangoon">(UTC+06:30) Yangon (Rangoon)</option>
<option value="Asia/Bangkok">(UTC+07:00) Bangkok, Hanoi, Jakarta</option>
<option value="Asia/Novosibirsk">(UTC+07:00) Novosibirsk</option>
<option value="Asia/Hong_Kong">(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
<option value="Asia/Krasnoyarsk">(UTC+08:00) Krasnoyarsk</option>
<option value="Asia/Singapore">(UTC+08:00) Kuala Lumpur, Singapore</option>
<option value="Australia/Perth">(UTC+08:00) Perth</option>
<option value="Asia/Irkutsk">(UTC+09:00) Irkutsk</option>
<option value="Asia/Tokyo">(UTC+09:00) Osaka, Sapporo, Tokyo</option>
<option value="Asia/Seoul">(UTC+09:00) Seoul</option>
<option value="Australia/Adelaide">(UTC+09:30) Adelaide</option>
<option value="Australia/Darwin">(UTC+09:30) Darwin</option>
<option value="Australia/Brisbane">(UTC+10:00) Brisbane, Guam</option>
<option value="Australia/Sydney">(UTC+10:00) Sydney, Melbourne, Hobart</option>
<option value="Asia/Yakutsk">(UTC+10:00) Yakutsk</option>
<option value="Pacific/Noumea">(UTC+11:00) Solomon Is., New Caledonia</option>
<option value="Asia/Vladivostok">(UTC+11:00) Vladivostok</option>
<option value="Pacific/Norfolk">(UTC+11:30) Norfolk Island</option>
<option value="Asia/Anadyr">(UTC+12:00) Anadyr, Kamchatka</option>
<option value="Pacific/Auckland">(UTC+12:00) Auckland, Wellington</option>
<option value="Pacific/Fiji">(UTC+12:00) Fiji</option>
<option value="Asia/Magadan">(UTC+12:00) Magadan</option>
<option value="Pacific/Chatham">(UTC+12:45) Chatham Islands</option>
<option value="Pacific/Tongatapu">(UTC+13:00) Nuku'alofa</option>
<option value="Pacific/Kiritimati">(UTC+14:00) Kiritimati</option>
</select>
</dd>
</dl>
<fieldset>
<dl class="ctrlUnit">
<dt style="    display: inline;">*Verification:</dt>
<dd style="    display: inline;">
<table id="keycaptcha_verification" style="    float: right;">
<tbody>
<tr>
<td>
<input type="hidden" name="capcode" id="capcode" value="false" />
<?php
if (!class_exists('KeyCAPTCHA_CLASS')) {
	// Replace '/home/path_to_keycaptcha_file/' with the real path to keycaptcha.php
	include('keycaptcha.php');
}
$kc_o = new KeyCAPTCHA_CLASS();
echo $kc_o->render_js();



?>




 </td></tr></tbody></table></dd>
</dl>
<dl class="ctrlUnit">
<dt style="    display: inline;">Recieve email as:</dt>
<dd style="    display: inline;">
<ul>
<li style="    display: inline;"><label for="ctrl_foramt_text"><input type="radio" name="format" value="text" checked= "checked" id="ctrl_foramt_text"> Text/plain</label></li>
<li style="    display: inline;"><label for="ctrl_foramt_html"><input type="radio" name="format" value="html" id="ctrl_foramt_html"> Text/html</label></li>
</fieldset>
<dl class="ctrlUnit submitUnit">
<dt style="    display: inline;"></dt>
<dd style="    display: inline;">
<ul>
<li>

<ul id="ctrl_agree_Disabler" disabled="true" class="disabled">
<li><input type="submit" value="Sign Up" id ="postbut" class="button" /></li>
</ul>
</li>
</ul>
</dd>
</dl>




</td>
<td width= "30%"><center>
<div style="float:bottom;"><img src="images/default_profile_picture.png" alt="asdfs" /></br>



<input type="file" name="file" id="file"  /> 
<br />
</form>
</div></td></tr></table>
<div id="azk44987" style="text-align: center;"></div>
</div>
<?php
}
require_once 'Footer.html'; 
?>