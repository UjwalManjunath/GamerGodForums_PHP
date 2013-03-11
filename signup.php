<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
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
	if($_POST['firstname']=="")
    {
		$err= "*firstname field  empty \r\n";
		$flag=1;
    }
	if($_POST['lastname']=="")
    {
		$err .="*lastname field  empty \r\n";
		$flag=1;
    }
	if($_POST['password']=="" || $_POST['password_confirm']=="" )
    {
		$err .="*password field  empty\r\n";
        $flag=1;
    }
    
	if(	$c = strcmp ( $_POST['password'] , $_POST['password_confirm'] )!=0 )
    {
        $err .= "*Passwords did not match\r\n";
        $flag=1;
	}
	if( $_FILES['photoimg']['size'] > 1024* 1024 )
	{
		$err .= "* image size too large\r\n";
		$flag=1;
	}
		
	if($_POST['timezone']=="")
    {
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
                $to=$email;
                $subject="Your confirmation link here";
                if($format == "text")
                {
                    $message="Your Comfirmation link \r\n";
                    $message.="Click on this link to activate your account \r\n";
                    $message.="https://mweigle418.cs.odu.edu/~umanjuna/proj4/confirmation.php?passkey=$confirm_code";
                    $headers .= 'From: <webmaster@gamer.com>' . "\r\n";
                }
                elseif( $format == "html")
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
    echo '<div class="pageContent">';
    echo '<div class="titleBar">';
    echo '<div id="titleBar_Helper"><h1>Sign Up';
    echo '</h1>';
    echo '</div>';
    echo '</div>';
    echo '<div id="azk99838" style="text-align: center;"></div>';
    echo '<table width="100%">';
    echo '<tr>';
    echo '<td width= "65%" style="    vertical-align: top;">';
    echo '<form action="#" method="post" class="xenForm AutoValidator"  enctype="multipart/form-data">';
    echo '<dl class="ctrlUnit">';
    echo '<dt style="    display: inline;"><label for="ctrl_username">*FirstName:</label></dt>';
    echo '<dd style="    display: inline;">';
    echo '<input type="text" name="firstname" value="'.$_POST['firstname'].'" class="textCtrl" id="ctrl_frstname" autofocus="true" autocomplete="off">';
    echo '</dd>';
    echo '</dl>';
    echo '<dl class="ctrlUnit">';
    echo '<dt style="    display: inline;"><label for="ctrl_username">*LastName:</label></dt>';
    echo '<dd style="    display: inline;">';
    echo '<input type="text" name="lastname" value="'.$_POST['lastname'].'" class="textCtrl" id="ctrl_lstname" autofocus="true" autocomplete="off">';
    echo '</dd>';
    echo '</dl>';
    echo '<dl class="ctrlUnit">';
    echo '<dt style="    display: inline;"><label for="ctrl_email">*Email:</label></dt>';
    echo '<dd style="    display: inline;"><input type="email" name="email" value="'.$_POST['email'].'" dir="ltr" class="textCtrl" id="ctrl_email"></dd>';
    echo '</dl>';
    echo '<fieldset>';
    echo '<dl class="ctrlUnit">';
    echo '<dt style="    display: inline;"><label for="ctrl_password">*Password:</label></dt>';
    echo '<dd style="    display: inline;"><input type="password" name="password" class="textCtrl OptOut" id="ctrl_password" autocomplete="off"></dd>';
    echo '</dl>';
    echo '<dl class="ctrlUnit">';
    echo '<dt style="    display: inline;"><label for="ctrl_confirm_password">*Confirm Password:</label></dt>';
    echo '<dd style="    display: inline;">';
    echo '<input type="password" name="password_confirm" class="textCtrl OptOut" id="ctrl_confirm_password">';
    echo '<p class="explain" >                       Enter your password in the first box and confirm it in the second.</p>';
    echo '</dd>';
    echo '</dl>';
    echo '</fieldset>';

    echo '<dl class="ctrlUnit">';
    echo '<dt style="    display: inline;">Gender:</dt>';
    echo '<dd style="    display: inline;">';
    echo '<ul>';
    echo '<li style="    display: inline;"><label for="ctrl_gender_male"><input type="radio" name="gender" value="male" checked="checked" id="ctrl_gender_male"> Male</label></li>';
    echo '<li style="    display: inline;"><label for="ctrl_gender_female"><input type="radio" name="gender" value="female" id="ctrl_gender_female"> Female</label></li>';

    echo '</ul>';
    echo '</dd>';
    echo '</dl>';

    echo '</br>';
    echo '<dl class="ctrlUnit">';
    echo '<dt style="    display: inline;"><label for="ctrl_timezone">*Time zone:</label></dt>';
    echo '<dd style="    display: inline;">';
    echo '<select name="timezone" class="textCtrl AutoTimeZone OptOut" id="ctrl_timezone">';
    echo '<option value="Pacific/Midway">(UTC-11:00) American Samoa</option>';
    echo '<option value="Pacific/Apia">(UTC-11:00) Apia, Samoa</option>';
    echo '<option value="Pacific/Honolulu">(UTC-10:00) Hawaii</option>';
    echo '<option value="Pacific/Marquesas">(UTC-09:30) Marquesas Islands</option>';
    echo '<option value="America/Anchorage">(UTC-09:00) Alaska</option>';
    echo '<option value="America/Los_Angeles">(UTC-08:00) Pacific Time (US &amp; Canada)</option>';
    echo '<option value="America/Santa_Isabel">(UTC-08:00) Baja California</option>';
    echo '<option value="America/Tijuana">(UTC-08:00) Tijuana</option>';
    echo '<option value="America/Denver">(UTC-07:00) Mountain Time (US &amp; Canada)</option>';
    echo '<option value="America/Chihuahua">(UTC-07:00) Chihuahua, La Paz, Mazatlan</option>';
    echo '<option value="America/Phoenix">(UTC-07:00) Arizona</option>';
    echo '<option value="America/Chicago" selected="selected">(UTC-06:00) Central Time (US &amp; Canada)</option>';
    echo '<option value="America/Belize">(UTC-06:00) Saskatchewan, Central America</option>';
    echo '<option value="America/Mexico_City">(UTC-06:00) Guadalajara, Mexico City, Monterrey</option>';
    echo '<option value="Pacific/Easter">(UTC-06:00) Easter Island</option>';
    echo '<option value="America/New_York">(UTC-05:00) Eastern Time (US &amp; Canada)</option>';
    echo '<option value="America/Havana">(UTC-05:00) Cuba</option>';
    echo '<option value="America/Bogota">(UTC-05:00) Bogota, Lima, Quito</option>';
    echo '<option value="America/Caracas">(UTC-04:30) Caracas</option>';
    echo '<option value="America/Halifax">(UTC-04:00) Atlantic Time (Canada)</option>';
    echo '<option value="America/Goose_Bay">(UTC-04:00) Atlantic Time (Goose Bay)</option>';
    echo '<option value="America/Asuncion">(UTC-04:00) Asuncion</option>';
    echo '<option value="America/Santiago">(UTC-04:00) Santiago</option>';
    echo '<option value="America/Cuiaba">(UTC-04:00) Cuiaba</option>';
    echo '<option value="America/La_Paz">(UTC-04:00) Georgetown, La Paz, Manaus, San Juan</option>';
    echo '<option value="Atlantic/Stanley">(UTC-04:00) Falkland Islands</option>';
    echo '<option value="America/St_Johns">(UTC-03:30) Newfoundland</option>';
    echo '<option value="America/Argentina/Buenos_Aires">(UTC-03:00) Buenos Aires</option>';
    echo '<option value="America/Argentina/San_Luis">(UTC-03:00) San Luis</option>';
    echo '<option value="America/Argentina/Mendoza">(UTC-03:00) Argentina, Cayenne, Fortaleza</option>';
    echo '<option value="America/Godthab">(UTC-03:00) Greenland</option>';
    echo '<option value="America/Montevideo">(UTC-03:00) Montevideo</option>';
    echo '<option value="America/Sao_Paulo">(UTC-03:00) Brasilia</option>';
    echo '<option value="America/Miquelon">(UTC-03:00) Saint Pierre and Miquelon</option>';
    echo '<option value="America/Noronha">(UTC-02:00) Mid-Atlantic</option>';
    echo '<option value="Atlantic/Cape_Verde">(UTC-01:00) Cape Verde Is.</option>';
    echo '<option value="Atlantic/Azores">(UTC-01:00) Azores</option>';
    echo '<option value="Europe/London">(UTC) Dublin, Edinburgh, Lisbon, London</option>';
    echo '<option value="Africa/Casablanca">(UTC) Casablanca</option>';
    echo '<option value="Atlantic/Reykjavik">(UTC) Monrovia, Reykjavik</option>';
    echo '<option value="Europe/Amsterdam">(UTC+01:00) Central European Time</option>';
    echo '<option value="Africa/Algiers">(UTC+01:00) West Central Africa</option>';
    echo '<option value="Africa/Windhoek">(UTC+01:00) Windhoek</option>';
    echo '<option value="Africa/Tunis">(UTC+01:00) Tunis</option>';
    echo '<option value="Europe/Athens">(UTC+02:00) Eastern European Time</option>';
    echo '<option value="Africa/Johannesburg">(UTC+02:00) South Africa Standard Time</option>';
    echo '<option value="Asia/Amman">(UTC+02:00) Amman</option>';
    echo '<option value="Asia/Beirut">(UTC+02:00) Beirut</option>';
    echo '<option value="Africa/Cairo">(UTC+02:00) Cairo</option>';
    echo '<option value="Asia/Jerusalem">(UTC+02:00) Jerusalem</option>';
    echo '<option value="Europe/Minsk">(UTC+02:00) Minsk</option>';
    echo '<option value="Asia/Gaza">(UTC+02:00) Gaza</option>';
    echo '<option value="Asia/Damascus">(UTC+02:00) Syria</option>';
    echo '<option value="Africa/Nairobi">(UTC+03:00) Nairobi, Baghdad, Kuwait, Qatar, Riyadh</option>';
    echo '<option value="Europe/Kaliningrad">(UTC+03:00) Kaliningrad</option>';
    echo '<option value="Asia/Tehran">(UTC+03:30) Tehran</option>';
    echo '<option value="Europe/Moscow">(UTC+04:00) Moscow, St. Petersburg, Volgograd</option>';
    echo '<option value="Asia/Dubai">(UTC+04:00) Abu Dhabi, Muscat, Tbilisi</option>';
    echo '<option value="Asia/Yerevan">(UTC+04:00) Yerevan</option>';
    echo '<option value="Asia/Baku">(UTC+04:00) Baku</option>';
    echo '<option value="Indian/Mauritius">(UTC+04:00) Mauritius</option>';
    echo '<option value="Asia/Kabul">(UTC+04:30) Kabul</option>';
    echo '<option value="Asia/Tashkent">(UTC+05:00) Tashkent, Karachi</option>';
    echo '<option value="Asia/Kolkata">(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>';
    echo '<option value="Asia/Kathmandu">(UTC+05:45) Kathmandu</option>';
    echo '<option value="Asia/Dhaka">(UTC+06:00) Astana, Dhaka</option>';
    echo '<option value="Asia/Yekaterinburg">(UTC+06:00) Ekaterinburg</option>';
    echo '<option value="Asia/Almaty">(UTC+06:00) Almaty, Bishkek, Qyzylorda</option>';
    echo '<option value="Asia/Rangoon">(UTC+06:30) Yangon (Rangoon)</option>';
    echo '<option value="Asia/Bangkok">(UTC+07:00) Bangkok, Hanoi, Jakarta</option>';
    echo '<option value="Asia/Novosibirsk">(UTC+07:00) Novosibirsk</option>';
    echo '<option value="Asia/Hong_Kong">(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>';
    echo '<option value="Asia/Krasnoyarsk">(UTC+08:00) Krasnoyarsk</option>';
    echo '<option value="Asia/Singapore">(UTC+08:00) Kuala Lumpur, Singapore</option>';
    echo '<option value="Australia/Perth">(UTC+08:00) Perth</option>';
    echo '<option value="Asia/Irkutsk">(UTC+09:00) Irkutsk</option>';
    echo '<option value="Asia/Tokyo">(UTC+09:00) Osaka, Sapporo, Tokyo</option>';
    echo '<option value="Asia/Seoul">(UTC+09:00) Seoul</option>';
    echo '<option value="Australia/Adelaide">(UTC+09:30) Adelaide</option>';
    echo '<option value="Australia/Darwin">(UTC+09:30) Darwin</option>';
    echo '<option value="Australia/Brisbane">(UTC+10:00) Brisbane, Guam</option>';
    echo '<option value="Australia/Sydney">(UTC+10:00) Sydney, Melbourne, Hobart</option>';
    echo '<option value="Asia/Yakutsk">(UTC+10:00) Yakutsk</option>';
    echo '<option value="Pacific/Noumea">(UTC+11:00) Solomon Is., New Caledonia</option>';
    echo '<option value="Asia/Vladivostok">(UTC+11:00) Vladivostok</option>';
    echo '<option value="Pacific/Norfolk">(UTC+11:30) Norfolk Island</option>';
    echo '<option value="Asia/Anadyr">(UTC+12:00) Anadyr, Kamchatka</option>';
    echo '<option value="Pacific/Auckland">(UTC+12:00) Auckland, Wellington</option>';
    echo '<option value="Pacific/Fiji">(UTC+12:00) Fiji</option>';
    echo '<option value="Asia/Magadan">(UTC+12:00) Magadan</option>';
    echo '<option value="Pacific/Chatham">(UTC+12:45) Chatham Islands</option>';
    echo '<option value="Pacific/Tongatapu">(UTC+13:00) Nuku\'alofa</option>';
    echo '<option value="Pacific/Kiritimati">(UTC+14:00) Kiritimati</option>';
    echo '</select>';
    echo '</dd>';
    echo '</dl>';
    echo '<fieldset>';
    echo '<dl class="ctrlUnit">';
    echo '<dt style="    display: inline;">*Verification:</dt>';
    echo '<dd style="    display: inline;">';
    echo '<table id="keycaptcha_verification" style="    float: right;">';
    echo '<tbody>';
    echo '<tr>';
    echo '<td>';
    echo '<input type="hidden" name="capcode" id="capcode" value="false" />';

    if (!class_exists('KeyCAPTCHA_CLASS'))
    {
        // Replace '/home/path_to_keycaptcha_file/' with the real path to keycaptcha.php
        include('keycaptcha.php');
    }
    $kc_o = new KeyCAPTCHA_CLASS();
    echo $kc_o->render_js();
    echo ' </td></tr></tbody></table></dd>';
    echo '</dl>';
    echo '<dl class="ctrlUnit">';
    echo '<dt style="    display: inline;">Recieve email as:</dt>';
    echo '<dd style="    display: inline;">';
    echo '<ul>';
    echo '<li style="    display: inline;"><label for="ctrl_foramt_text"><input type="radio" name="format" value="text" checked= "checked" id="ctrl_foramt_text"> Text/plain</label></li>';
    echo '<li style="    display: inline;"><label for="ctrl_foramt_html"><input type="radio" name="format" value="html" id="ctrl_foramt_html"> Text/html</label></li>';
    echo '</fieldset>';
    echo '<dl class="ctrlUnit submitUnit">';
    echo '<dt style="    display: inline;"></dt>';
    echo '<dd style="    display: inline;">';
    echo '<ul>';
    echo '<li>';
    echo '<ul id="ctrl_agree_Disabler" disabled="true" class="disabled">';
    echo '<li><input type="submit" value="Sign Up" id ="postbut" class="button" /></li>';
    echo '</ul>';
    echo '</li>';
    echo '</ul>';
    echo '</dd>';
    echo '</dl>';
    echo '</td>';
    echo '<td width= "30%"><center>';
    echo '<div style="float:bottom;"><img src="images/default_profile_picture.png" alt="asdfs" /></br>';
    echo '<input type="file" name="file" id="file"  />';
    echo '<br />';
    echo '</form>';
    echo '</div></td></tr></table>';
    echo '<div id="azk44987" style="text-align: center;"></div>';
    echo '</div>';
}
require_once 'Footer.html'; 
?>