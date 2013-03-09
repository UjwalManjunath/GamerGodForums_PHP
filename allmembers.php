<?php
session_start();
require_once 'config/config.php';
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
include 'Header.php';
include 'signin.php';

if(isset($_POST['subscribe_user']))
	{
		echo "clicked subscribe";
		if(isset($_POST['subscribe_to']))
		{
			$subscribed_to_user = $_POST['subscribe_to'];
			$subscribing_user = $_SESSION['user_id'];
			echo $_POST['subscribe_to'];
			$conn1=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
			
			if($su= $conn1->query("Insert into subscribers(Subscriber,subscribed_to) Values($subscribing_user,$subscribed_to_user)") )	
				echo "inserted";
			else
			echo "insert error";
				$conn1->close();
		}
		
	}
	
if(isset($_POST['unsubscribe_user']))
	{
		echo "clicked unsubscribe";
		if(isset($_POST['unsubscribe_from']))
		{
			$unsubscribed_from_user = $_POST['unsubscribe_from'];
			$unsubscribing_user = $_SESSION['user_id'];
			echo $_POST['unsubscribe_from'];
			$conn1=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
			
			if($su= $conn1->query("delete from subscribers where subscriber=$unsubscribing_user and subscribed_to=$unsubscribed_from_user") )	
				echo "deleted";
			else
			echo "delete error";
			$conn1->close();	
		}
		
	}


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

if(isset($_SESSION['signedin']) && $_SESSION['signedin']==true && isset($_SESSION['user_suspended']) && $_SESSION['user_suspended']==1)
{
	?>
	<div class="pageWidth">
<div class="pageContent">
 
 
<div class="titleBar">
<div id="titleBar_Helper"><h1> <center>GAMER GOD - Error</center>
</h1>
</div>
</div>
<div id="azk99838" style="text-align: center;"></div>
 
<div class="errorOverlay">
<a class="close OverlayCloser"></a>
<div class="baseHtml"><center>
<label for="ctrl_0" class="OverlayCloser">You have been temporarily banned.</label>
<label for="ctrl_0" class="OverlayCloser">You do not have permission to view this page or perform this action.</label></center>
</div>
</div>
<div id="azk44987" style="text-align: center;"></div>
</div>
</div>
<?php
}
else
	{
		echo '<div class="titleBar">';
		echo '<div id="titleBar_Helper"><h1>Registered Members</h1>';
		echo '</div>';
		echo '<p id="pageDescription" class="muted baseHtml">This is a list all members registered at Gamer God Forum</p>';
		echo '</div>';
		
		$userlist=$conn->query("select u.firstname,u.lastname,u.user_name,u.user_id,u.profileimage From users_proj4 u where user_status=0 order by firstname,lastname ");
		$content = "";
		if (($userlist ) && mysqli_num_rows($userlist) > 0)
		{
			$content .= '<div>';
			// section right
			$content .= '<div id="rightuser" style="float:right;">';
			$content .= '<div class="gcontentuser" >';
			$content .= '	<div class="headuser"><h1>Highest-Posting Members</h1></div>';
			$content .= '<div class="boxyuser">';
			$content .= '	<div class="badgeCountuser">';
			$content .= '</div>';
			$content .= 	'</div>';
			$content .= '</div>';
			$content .= '<div class="gcontentuser">';
			$content .= '<div class="headuser"><h1>Newest Members</h1></div>';
			$content .= '<div class="boxyuser">';
			$content .= 	'<p> </p>';
			$content .= '<p></p>';
			$content .= '<div class="friendslistuser clearfixuser"><div class="frienduser"></div></div>				';
			$content .= '<span><a href="#"></a></span>';
			$content .= '</div>';
			$content .= '</div>';
			$content .= 	'</div>';
			// section right end
			
			$content .= '<table id="background-image"style="width:70%;">';
				 while ($row = $userlist->fetch_assoc()) 
				 {	
					$content .= '<tbody>';
					$content .= '<tr>';
					$content .= '<td  WIDTH="35px">';
					$content .= '<div class="imaaage">';
					$content .='<span class="avatarContainer">';
					if( $row['profileimage'] == NULL)
					$content .='<a href="member.php?id='.htmlspecialchars($row['user_id']) . '" class="avatar" data-avatarhtml="true"><img src="images/default_profile_picture.png" width="48" height="48" alt="'.htmlspecialchars($row['user_name']).'"></a>';
					else
					$content .='<a href="member.php?id='.htmlspecialchars($row['user_id']) . '" class="avatar" data-avatarhtml="true"><img src="images/uploads/profilepics/'.$row['profileimage'].'" width="48" height="48" alt="'.htmlspecialchars($row['user_name']).'"></a>';
					$content .='</span>';
					$content .='</div>';
					
					$content .= '</td>';
					$content .= '<td WIDTH="600px" >';
				
					$content .= '<div class="detailsss" style=" position: absolute; ">';
					$content .= '<div class="titleText">';
					$content .= '<h2 class="title">';
					$content .= '<a  class="anchor" href="member.php?id='.htmlspecialchars($row['user_id']) . '">'.ucfirst($row['firstname']).' '.ucfirst($row['lastname']).'</a>';
					$content .= '</h2>';
					$content .= '<div class="secondRow" >';
					
					$content .= '<div class="faint" > Threads: '.thread_count($row['user_id']).'  Posts: '.post_count($row['user_id']).' ';
					
					$content .= '</div>';
					$content .= '<div class="controls faint">';
					$content .= '</div>';
					$content .= '</div>';
					$content .= '</div>';
					$content .= '</div>';
					$content .= '</td>';
					if(isset($_SESSION['signedin']) && $_SESSION['signedin']==true && isset($_SESSION['user_id']) && $_SESSION['user_id']!=$row['user_id'] )
					{
						$uid= $_SESSION['user_id'];
						$sid= $row['user_id'];
						$list_subs = $conn->query("select subscribed_to from subscribers where Subscriber=$uid and subscribed_to=$sid");
						if($list_subs && mysqli_num_rows($list_subs)>0)
						{
						$subscribed_list = $list_subs->fetch_row();
							
								if( $row['user_id'] == $subscribed_list[0])
								{
									$content .= '<td style="vertical-align: middle;" > ';
									$content .= '<form method="post" action="">';
									$content .= '<input type="hidden" name="unsubscribe_from" value="'.htmlspecialchars($row['user_id']) . '">';
									$content .= '<input style="cursor:pointer;border:none;padding:0px;float:right;" name="unsubscribe_user" value="unSubscribe" type="image" src="./images/unsubscribe.png"  height = "30px" >';
									//$content .= '<img  height = "30px" src= "images/subscribe.jpg"  style="cursor:pointer" alt= "subscribe" />';
									$content .= '</form>';
									$content .= '</td>';
									
								}
								
							
						}else
						{
							$content .= '<td style="vertical-align: middle;" > ';
							$content .= '<form method="post" action="">';
							$content .= '<input type="hidden" name="subscribe_to" value="'.htmlspecialchars($row['user_id']) . '">';
							$content .= '<input style="cursor:pointer;border:none;padding:0px;float:right;" name="subscribe_user" value="Subscribe" type="image" src="./images/subscribe.jpg"  height = "30px" >';
							//$content .= '<img  height = "30px" src= "images/subscribe.jpg"  style="cursor:pointer" alt= "subscribe" />';
							$content .= '</form>';
							$content .= '</td>';
						
						}
						
						
					}
					else
					{
						$content .= '<td >';
						$content .= '</td>';
					}
					$content .= '</tr>';
					
					$content .= '</tbody >';
			
				
				}
				$content .= '</table>'; 
				//////////////////////////////////////////////////
			
	$content .= '</div>';
	
				echo $content;
		}
	
	}



require_once 'Footer.html'; 
?>