<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'config/config.php';

function thread_count($uid)
{
	$conn1=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
		
	if($threadcnt= $conn1->query("select count(*) from  topics_proj4 where topic_by =$uid") )
	if( mysqli_num_rows($threadcnt) >0)
	{
		$tc = $threadcnt->fetch_row();
		$threadcount = $tc[0];
	}
	else
	{
		$threadcount = 0;
	}
	$conn1->close();
	return $threadcount;
		
}

function functionrole($uid,$userlvl)
{
	if($userlvl=="1")
	{	
		$role = "Admin";
	}
	else 
		if($userlvl=="2")
		{
			$role="Moderator";
		}
		else
		{
			$cnt=post_count($uid);
			if($cnt <=8)
				$role = "Apprentice";
			else if($cnt <=16)
				$role = "Warrior";
			else if($cnt<=24)
				$role = "Knight";
			else if($cnt<=32)
				$role = "King";
			else if($cnt<=40)
				$role = "Demi God";
			else
				$role = "Gamer God";
		}
		return $role;
}

function post_count($uid)
{
	$conn1=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
			
	if($postcnt= $conn1->query("select count(*) from posts_proj4 where post_by =$uid") )
	if( mysqli_num_rows($postcnt) >0)
	{
		$pc= $postcnt->fetch_row();
		$postcount = $pc[0];
	}
	else
	{
		$postcount = 0;
	}
	$conn1->close();
	return $postcount;
		
}

function user_latest_post_date($uid)
{
	$conn1=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
	
	if($result= $conn1->query("SELECT DATE_FORMAT(MAX(post_date),' %b %d , %Y %H:%i') from posts_proj4 where post_by=$uid order by post_date" ))
	{
		if( mysqli_num_rows($result) >0)
		{
			$lastdatepost=$result->fetch_row();
			$lastdate = $lastdatepost[0];
		}
		else
		{
			$lastdate="No Posts";
		}
	}
	return $lastdate;
	
}



?>

<html>
<head>
<style>
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, font, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td {
	margin: 0;
	padding: 0;
	border: 0;
	outline: 0;
	font-weight: inherit;
	font-style: inherit;
	font-size: 100%;
	font-family: inherit;
	vertical-align: baseline;
}
#background-image
{
	background: #D1D1E1;
	font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
	font-size: 12px;
	margin:35px 35px 0 35px;
	width: 93%;
	text-align: left;
	border-collapse: collapse;
	border-radius:25px;
	/*background: url('table-images/blurry.jpg') 330px 59px no-repeat;*/
}
#background-image th
{
	padding: 12px;
	font-weight: normal;
	font-size: 20px;
	color: #339;
}
#background-image td
{
	padding: 9px 12px;
	color: #669;
	border-top: 1px solid #fff;
}
#background-image tfoot td
{
	font-size: 11px;
}
#background-image tbody td
{
	background: url('./stylesheets/table-images/back.png');
	
}
* html #background-image tbody td
{
	/* 
	   ----------------------------
		PUT THIS ON IE6 ONLY STYLE 
		AS THE RULE INVALIDATES
		YOUR STYLESHEET
	   ----------------------------
	*/
	filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='./stylesheets/table-images/back.png',sizingMethod='crop');
	background: none;
}	
#background-image tbody tr:hover td
{
	color: #339;
	background: none;
}
.anchor{
font-size:20px;
text-decoration:none;

padding:0px 0px 50px 0px;
}
.username{
font-size:12px;
}

    .detailsss
    {
      
      
       
        height: 20px;
    }

</style>
<script src="./js/jquery-latest.js"></script>
<script>
function myFunction(id) {
document.getElementById
$("#user"+id).toggle("fast");
};    
</script>
</head>
<body>
<?php
//echo $_POST['userid'];


if(isset($_POST['dropdown']))
{
	//echo "in";	
	$userid = $_POST['update_userid'];
	$userlvl = $_POST['upd_userlvl'];
	if($res = $conn->query("select count(*) from users_proj4 where user_level=$userlvl and user_status=0"))
	{
		$count= $res->fetch_row();
		if($userlvl == "1")
		{
			if($count[0] == "1" )
			{	
				//echo " Forum must have atleast 1 ADMIN . Delete Unsuccessful ";
				exit(" Forum must have atleast 1 ADMIN . update Unsuccessful ");
			}
		}
		//echo $userid;
		$usrlevel= $_POST['dropdown'];
		if( $res = $conn->query("update users_proj4 set user_level=$usrlevel  where user_id=$userid"))
		{
			echo "updated";
		}
	}
}

if(isset($_POST['suspend']))
{
	$userid= $_POST['sus_userid'];
	$usrlvl = $_POST['sus_userlvl'];
	if($_POST['suspend'] == "Suspend")
	{
		if( $usrlvl!= 1 || $usrlvl!=2)
		{
			if( $res = $conn->query("update users_proj4 set user_suspended=1  where user_id=$userid"))
			{
				echo "user suspended";
				if( $res = $conn->query("select user_email from users_proj4 where user_id=$userid"))
					{
						$temp = $res->fetch_row();
						$email = $temp[0];
						//echo "deleted";
						$to = $email;
						$subject = "Gamer god Forums";
						$message = "Your account has been Temporarily Suspended due to suspicious activity from your profile. You cannot any operations until suspension is lifted.";
						$from = "<webmaster@gamer.com>";
						$headers = "From:" . $from;
						mail($to,$subject,$message,$headers);
					}
					else
					{
					echo "error in retreiving email";
					}
			}
			else
			{
			echo " updating suspend error";
			}
		}
		else
		{
			echo "Admin or Moderator cannot be suspended";
		}
	}
	else if ($_POST['suspend'] == "UnSuspend")
	{
		if( $res = $conn->query("update users_proj4 set user_suspended=0  where user_id=$userid"))
			{
				echo "Suspension on profile Lifted";
				if( $res = $conn->query("select user_email from users_proj4 where user_id=$userid"))
					{
						$temp = $res->fetch_row();
						$email = $temp[0];
						//echo "deleted";
						$to = $email;
						$subject = "Gamer god Forums";
						$message = "The suspension placed on your account is now lifted. welcome Back to GAMER GOD FORUMS";
						$from = "<webmaster@gamer.com>";
						$headers = "From:" . $from;
						mail($to,$subject,$message,$headers);
					}
					else
					{
					echo "error in retreiving email";
					}
			}
			else
			{
			echo " updating suspend error";
			}
	}
}
	


	

if(isset($_POST['del_userid']) && isset($_POST['del_userlvl']))
{
	//echo "in";
$userdel = $_POST['del_userid'];
$usrlev =  $_POST['del_userlvl'];

	if($res = $conn->query("select count(*) from users_proj4 where user_level=$usrlev and user_status=0"))
	{
		$countad = $res->fetch_row();
		if($usrlev == "1")
		{
			if($countad[0] == "1" )
			{	
				//echo " Forum must have atleast 1 ADMIN . Delete Unsuccessful ";
				exit(" Forum must have atleast 1 ADMIN . Delete Unsuccessful ");
			}
		}
		echo $userdel;
		if($res = $conn->query("update users_proj4 set user_status=1 where user_id=$userdel"))
		{
			echo "table user updated";
			if($res = $conn->query ("update topics_proj4 set user_deleted=1 where topic_by=$userdel"))
			{
				echo "table topics updated";
				if($res= $conn->query ("update posts_proj4 set user_removed=1 where post_by=$userdel"))
				{
					echo "table post updated";
					
					
					if( $res = $conn->query("select user_email from users_proj4 where user_id=$userdel"))
					{
						$temp = $res->fetch_row();
						$email = $temp[0];
						echo "deleted";
						$to = $email;
						$subject = "Gamer god Forums";
						$message = "Your account has been removed from the forum due to suspicious activity from your profile";
						$from = "<webmaster@gamer.com>";
						$headers = "From:" . $from;
						mail($to,$subject,$message,$headers);
							
						//	echo "Mail Sent.";
						//if( $res = $conn->query("delete from users_proj4 where user_id=$userdel"))
						//{
							echo "User deleted";
				//		}
					}
				}
			}
		}
	}
}


$categories= $conn->query("Select user_suspended,u.user_id,u.user_name, u.user_level From users_proj4 u where u.user_status=0");
	$content = "";
	if(($categories) && mysqli_num_rows($categories) > 0) 
	{
		$content .= '<table id="background-image">';
		$content .= '<thead>';
		$content .= '<tr>';
		$content .= '<th scope="col">Users</th>';
		$content .= '</tr>';
		$content .= '</thead>';
		while ($row = $categories->fetch_assoc())	{
			/*	$content .= '<tbody>';
				$content .= '<tr>';
				$content .= '<td>';
				$content .= '<a href="view_forum.php?catid=';
				$content .= htmlspecialchars($row['cat_id']) . '">'.$row['cat_name'].'</a>';
				$content .= '</td>';
				$content .= '</tr>';
				$content .= '</tbody>';   */
				
				$content .= '<tbody>';
				$content .= '<tr>';
				$content .= '<td  WIDTH="35px">';
				$content .= '<div class="imaaage">';
				$content .='<span class="avatarContainer">';
				$content .='<a href="javascript:parent.window.location.href=\'https://mweigle418.cs.odu.edu/~umanjuna/proj4/member.php?id='.$row['user_id'].'\';" class="avatar" data-avatarhtml="true"><img src="images/default_profile_picture.png" width="48" height="48" alt="img"></a>';
				$content .='</span>';
				$content .='</div>';
				
				$content .= '</td>';
				$content .= '<td WIDTH="500px" >';
			
				$content .= '<div class="detailsss" style=" position: absolute; ">';
				$content .= '<div class="titleText">';
				$content .= '<h2 class="title">';
				$content .= '<a  class="anchor" href="javascript:parent.window.location.href=\'https://mweigle418.cs.odu.edu/~umanjuna/proj4/member.php?id='.$row['user_id'].'\';">'.$row['user_name'].'</a>';
				$content .= '<div id="slide1" style="background:#de9a44; margin:3px; width:400px;height:100px; display:none; float:left;"> </div>';
				$content .= '</h2>';
				$content .= '<div class="secondRow" >';
				$content .= '<div class="faint" > ';
				if($row['user_level']=='1')
				{
					$role="ADMIN";
					$nextrole="MODERATOR";
					$nextroleval="2";
					$nextrole1="USER";
					$nextroleval1="0";
					
				}
				else if($row['user_level']=='2') 
				{
				$role="MODERATOR";
				$nextrole="ADMIN";
					$nextroleval="1";
					$nextrole1="USER";
					$nextroleval1="0";
				}
				else 
				{
				
				$role="USER";
				
				$nextrole="ADMIN";
					$nextroleval="1";
					$nextrole1="MODERATOR";
					$nextroleval1="2";
					}
				//$content .= '<a href="#" class="username" title="Thread starter">'.htmlspecialchars($row['user_name']).'</a>,';
				$content .= '<a class="faint">'.$role.'</a>';
				$content .= '</div>';
				$content .= '<div class="controls faint">';
				$content .= '</div>';
				$content .= '</div>';
				$content .= '</div>';
				$content .= '</div>';
				$content .= '</td><td></td>';
				//$content .= '<td width="50px"> <button onclick=myFunction(\''.$row['user_id'].'\')>Stats</button> </td>';
				$content .= '<td width = "200px"> <button onclick=myFunction(\''.$row['user_id'].'\')>Stats</button> ';
			
					$content .= '</td>';
				$content .= '</tr >';
				$content .= '<tr style="display:none;" id="user'.$row['user_id'].'"><td width="100px" >';
				$content .= '</td><td>';
				$content .= '<table>';
				$conn1=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
	
				if($result= $conn1->query("SELECT user_id,user_level,FirstName,LastName,DATE_FORMAT(user_date,' %b %d , %Y %H:%i') as UserDate from users_proj4 where user_id = ".$row['user_id']."" ))
				{
					$row2 = $result->fetch_assoc();
					$content .= '<tr><td>';
					$content .= 'FirstName: </td><td> '.$row2['FirstName'].'</td></tr>';
					$content .= '<tr><td>';
					$content .= 'LastName:  </td><td>'.$row2['LastName'].'</td></tr>';
					$content .= '<tr><td>';
					$content .= 'Join Date:  </td><td>'.$row2['UserDate'].'</td></tr>';
					$content .= '<tr><td>';
					$content .= 'Rank:  </td><td>'.functionrole($row['user_id'],$row['user_level']).'</td></tr>';
					$content .= '<tr><td>';
					$content .= 'Threads Started: </td><td>'.thread_count($row2['user_id']).'</td></tr>';
					$content .= '<tr><td>';
					$content .= 'No of Posts: </td><td>'.post_count($row2['user_id']).'</td></tr>';
					$content .= '<tr><td>';
					$content .= 'Date of Lastpost: </td><td>'.user_latest_post_date($row2['user_id']).'</td></tr>';
				}
				if((isset($_SESSION['user_level'])) && (($_SESSION['user_level']=="1") ))
				{
					$content .= '<tr><td>';
					$content .=  '<form method="post" action=""> ';
					$content .= '<input name="update_userid" type="hidden" id="update_userid" value='.$row['user_id'].'>';
					$content .= '<input name="upd_userlvl" type="hidden" id="upd_userlvl" value='.$row['user_level'].'>';
					$content .= 'ROLE: <select name="dropdown" value="options">';
					$content .= '<option value"">'.$role.'</option>';
					$content .= '<option value="'.$nextroleval.'">'.$nextrole.'</option>';
					$content .= '<option value="'.$nextroleval1.'">'.$nextrole1.'</option>';
					$content .= '</SELECT></td><td>';
					$content .= '<input name="update" type="submit" id="update" value="update">'; 
					$content .= '</form></td></tr>';
				}
				
				//$content .=	'<div>Join Date:  </div>';
				//$content .=	'<div>Last Post:  </div>';
				//$content .=	'<div>Threads:  </div>';
				//$content .=	'<div>Posts:  </div>';
				$content .= '</table>'; 	
				$content .= '</td><td style=" vertical-align: middle; ">';
				if( $row['user_level'] == 0)
				{
					$content .= '<form method="post" action=""> ';
					$content .= '<input name="sus_userid" type="hidden" id="sus_userid" value='.$row['user_id'].'>';
					$content .= '<input name="sus_userlvl" type="hidden" id="sus_userlvl" value='.$row['user_level'].'>';
					if($row['user_suspended']==0)
					$content .= '<input name="suspend" type="submit" id="suspend" value="Suspend">'; 
					else
					$content .= '<input name="suspend" type="submit" id="suspend" value="UnSuspend">'; 
					$content .= '</form></td>';
				}
				
				if((isset($_SESSION['user_level'])) && (($_SESSION['user_level']=="1") ))
				{
					$content .= '<td style=" vertical-align: middle; ">';
					$content .= '<form method="post" action=""> ';
					$content .= '<input name="del_userid" type="hidden" id="del_userid" value='.$row['user_id'].'>';
					$content .= '<input name="del_userlvl" type="hidden" id="del_userlvl" value='.$row['user_level'].'>';
					$content .= '<input name="delete" type="submit" id="delete" value="Delete">'; 
				}
				$content .= '</form></td><td></td>';
				$content .= '</tr></tbody >';
							//	$conn2->close();
			}
			$content .= '</table>';               
			echo $content;

	} 
	else 
	{
		echo "Category error"; 
	}			
	
	?>

</body>
</html>