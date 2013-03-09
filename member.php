<?php
session_start();
require_once 'config/config.php';
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'helpers/validation.php';
include 'Header_old.php';
include 'signin.php';



if(isset($_POST['submitnotify']))
{
$uid = $_SESSION['user_id'];
	if(isset($_POST['notify']))
	{
		if($_REQUEST['notify'] == 'NONE')
		{
			
			if( $res = $conn->query("update subscribers set notify=0 , notify_key=0 where subscriber = $uid"))
			{	
				echo "updated";
			}
			else
			{
				echo " none error";
			}
		
		}
		else
		if($_REQUEST['notify'] == 'ALL')
		{
			if( $res = $conn->query("update subscribers set notify=1, notify_key=0 where subscriber = $uid"))
			{	
				echo "updated";
			}
			else
			{
				echo " none error";
			}
		
		}
		else
		if($_REQUEST['notify'] == 'KEY')
		{
			if(isset($_POST['keywords']))
			{
				$key= addslashes($_POST['keywords']);
				echo $key;
				if( $res = $conn->query("update subscribers set notify=0, notify_key=1 , keywords='$key' where subscriber = $uid"))
				{	
					echo "updated";
				}
				else
				{
					echo " none error";
				}
					
			}
		}
	}
	
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
	
	function currentbadge($uid)
	{
		$cnt= post_count($uid);
		if($cnt <=8)
					$badge = '<a href="#"><img width="30" height="40" src="images/apprentice.png" alt="Apprentice"> </a>';
				else if($cnt <=16)
					$badge = '<a href="#"><img width="40" height="40" src="images/Warrior.png" alt="Warrior"> </a>';
				else if($cnt<=24)
					$badge = '<a href="#"><img width="40" height="40" src="images/Knight1.png" alt="Knight"> </a>';
				else if($cnt<=32)
					$badge = '<a href="#"><img width="40" height="40"  src="images/King1.png" alt="King"> </a>';
				else if($cnt<=40)
					$badge = '<a href="#"><img width="40" height="40" src="images/Demigod.png" alt="Demigod"> </a>';
				else
					$badge = '<a href="#"><img width="40" height="40" src="images/Ggod.png" alt="Ggod"> </a>';
	
	
	
	return $badge;
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
	$uid = $_GET['id'];
	$user_det= $conn->query("Select u.user_name,u.FirstName,u.LastName,u.user_level ,DATE_FORMAT(u.user_date,' %b %d , %Y ') as USERDATE,u.profileimage From users_proj4 u where u.user_id=$uid");
	if(($user_det) && mysqli_num_rows($user_det) > 0) 
	{	
		$user_detail=$user_det->fetch_assoc();
		
	

?>
<script type="text/javascript" >
 $(document).ready(function() { 
		
            $('#photoimg').live('change', function()			{ 
			           $("#preview12").html('');
			    $("#preview12").html('<img src="loader.gif" style=\" width:120px; margin-left:15px;\" alt="Uploading...."/>');
			$("#imageform").ajaxForm({target: '#preview12'}).submit();
		
			});
        }); 
</script>
<script>
function loadpic() {
    document.getElementById('photoimg').click();
};
</script>
<div id="contentuser" class="clearfixuser">
		<section id="leftuser">
			<div id="userStatsuser" class="clearfixuser">
				<div class="picuser">
					<div id="preview12" width="140" height="140">
					<?php
					if($user_detail['profileimage']==NULL)
						echo "<a href=\"#\"><img src=\"images/default_profile_picture.png\" width=\"140\" height=\"140\"></a> ";
					else
						echo "<a href=\"#\"><img src='images/uploads/profilepics/".$user_detail['profileimage']."' width=\"140\" height=\"140\"></a> ";
					?>
					 
					</div>
					<?php
					if(isset($_SESSION['signedin']) && $_SESSION['signedin']==true && $_SESSION['user_id'] == $uid )
					{
						echo "<form id=\"imageform\" action=\"ajaximage.php\" method=\"post\" enctype=\"multipart/form-data\" style=\"width:100px;\">";
						echo "<img src=\"images/uploadButton.png\" id=\"upfile1\" style=\"cursor:pointer; width:120px; margin-left:15px;\" onclick=\"loadpic()\"/>";
						echo "<input type=\"file\" name=\"photoimg\" id=\"photoimg\" style=\"border:none;margin:0px;width:100px ;display:none\" />";
						echo "</form>  ";
						
					}
					?>
				</div>
				
				<div class="datauser">
				<div style="float:right;"> 
					<h1> Rank: <?php echo functionrole($uid,$user_detail['user_level']) ?></h1>
					</div>
					<h1><?php echo ucwords($user_detail['FirstName'])." ".ucwords($user_detail['LastName'])?></h1>
					
					<h3>Norfolk, VA</h3>
					<h4>Registered: <?php echo $user_detail['USERDATE'] ?></h4>
					<div class="sepuser"></div>
					<ul class="numbersuser clearfixuser">
						<li><center>Thread Count<strong><?php echo thread_count($uid) ?></strong></center></li>
						<li><center>Post Count<strong><?php echo post_count($uid) ?></strong></center></li>
						<li><center>Current Badge<strong><?php echo currentbadge($uid) ?></strong></center></li>
					</ul>
				</div>
			</div>
			<div style="    padding: 20px;">
			<?php
			if(isset($_SESSION['signedin']) && $_SESSION['signedin']==true && $_SESSION['user_id'] == $uid )
			{
				$notifyme=$conn->query("select notify,notify_key,keywords from subscribers where subscriber=$uid");
				if($notifyme && mysqli_num_rows($notifyme)>0)
				{
					$notifyflags=$notifyme->fetch_row();
					if( $notifyflags[0] == 0 && $notifyflags[1] ==0)
					{
						?>
						<h1>Settings</h1>
						<p style="font-size:1.5em;">Notifications</p>
				
						<form style= "border:solid 1px;padding: 15px;" method="post" action="" >
						<b><input type="radio" name="notify" value="NONE" checked ="checked" onclick="this.form.keywords.disabled=true">Do not Disturb<br>
						<input type="radio" name="notify" value="ALL" onclick="this.form.keywords.disabled=true" >Notify me when any post is made<br>
						<input type="radio" name="notify" value="KEY"  onclick="this.form.keywords.disabled=false" >Notify me when post involving following keyword is made</br></b>
						<input type="text"  name="keywords" style=" width: 50%;" required="required" disabled>
						<input type="submit" name="submitnotify" value="submit" >
						</form>
						<?php
					}
					else if ( $notifyflags[0] == 1 && $notifyflags[1] ==0)
					{
						?>
						<h1>Settings</h1>
						<p style="font-size:1.5em;">Notifications</p>
				
						<form style= "border:solid 1px;padding: 15px;" method="post" action="" >
						<b><input type="radio" name="notify" value="NONE"  onclick="this.form.keywords.disabled=true">Do not Disturb<br>
						<input type="radio" name="notify" value="ALL" checked ="checked" onclick="this.form.keywords.disabled=true" >Notify me when any post is made<br>
						<input type="radio" name="notify" value="KEY"  onclick="this.form.keywords.disabled=false" >Notify me when post involving following keyword is made</br></b>
						<input type="text"  name="keywords" style=" width: 50%;" required="required" disabled>
						<input type="submit" name="submitnotify" value="submit" >
						</form>
						<?php
					}
					else if ( $notifyflags[0] == 0 && $notifyflags[1] ==1)
					{
						?>
						<h1>Settings</h1>
						<p style="font-size:1.5em;">Notifications</p>
				
						<form style= "border:solid 1px;padding: 15px;" method="post" action="" >
						<b><input type="radio" name="notify" value="NONE"  onclick="this.form.keywords.disabled=true">Do not Disturb<br>
						<input type="radio" name="notify" value="ALL" onclick="this.form.keywords.disabled=true" >Notify me when any post is made<br>
						<input type="radio" name="notify" value="KEY"  checked ="checked" onclick="this.form.keywords.disabled=false" >Notify me when post involving following keyword is made</br></b>
						<input type="text"  name="keywords" value="<?php echo $notifyflags[2]; ?>" style=" width: 50%;" required="required" >
						<input type="submit" name="submitnotify" value="submit" >
						</form>
						<?php
					}
					
					
				}	
				else
					{
						?>
						<h1>Settings</h1>
						<p style="font-size:1.5em;">Notifications</p>
				
						<form style= "border:solid 1px;padding: 15px;" method="post" action="" >
						<h1> you have not subscribed to any member <a href="allmembers.php">Subscribe Now </a></h1>
						</form>
						<?php
					
					
					}
			}			?>
			</div>
		</section>
		
		<section id="rightuser">
			<div class="gcontentuser">
				<div class="headuser"><h1>Badges</h1></div>
				<div class="boxyuser">
					<p>Keep posting to unlock badges!</p>
					
					<div class="badgeCountuser">
					<?php
						$cnt= post_count($uid);
						if($cnt>=0)
						echo '<a href="#"><img width="30" height="40" src="images/apprentice.png" alt="Apprentice"> </a>';
						if($cnt>8)
						echo '<a href="#"><img width="40" height="40" src="images/Warrior.png" alt="Warrioe"> </a>';
						if($cnt > 16)
						echo '<a href="#"><img width="40" height="40" src="images/Knight.png" alt="Knight"> </a>';
						if($cnt >24)
						echo '<a href="#"><img width="40" height="40" src="images/King.png" alt="King"> </a>';
						if($cnt >32)
						echo '<a href="#"><img width="40" height="40" src="images/Demigod.png" alt="Demigod"> </a>';
						if($cnt>40)
						echo '<a href="#"><img width="40" height="40" src="images/Ggod.png" alt="God"> </a>';
						?>
					</div>
					
					
				</div>
			</div>
			
			<div class="gcontentuser">
				<div class="headuser"><h1>Recent Posts</h1></div>
				<div class="boxyuser">
					<p>Last Posted on: <?php echo user_latest_post_date($uid) ?> </p>
					<p>Your Posts - <?php echo post_count($uid) ?> total</p>
					
					<?php
						if ($res = $conn->query("SELECT post_content,post_date FROM posts_proj4 WHERE post_by=$uid order by post_date desc LIMIT 0,6"))
						{
							if(mysqli_num_rows($res) > 0)
							{
								while($post_content=$res->fetch_assoc())
								{
									$dots ="...";
									echo '<div class="friendslistuser clearfixuser">';
									echo '<div class="frienduser">';
									echo '<a href="#"><img src="img/friend_avatar_default.jpg" width="20" height="20" alt="Jerry K."></a><span class="friendlyuser"><a href="#">'.substr($post_content['post_content'],0,25).$dots.'</a></span>';
									echo '</div>';
									echo '</div>';
								}
							}
							else
							{
								echo '<div class="friendslistuser clearfixuser">';
								echo '<div class="frienduser">';
								echo '<a href="#"><img src="img/friend_avatar_default.jpg" width="20" height="20" alt="Jerry K."></a><span class="friendlyuser"><a href="#">Jerry K.</a></span>';
								echo '</div>';
								echo '</div>';
							}
							
						}
					?>
				
					
					<span><a href="#">See all...</a></span>
				</div>
			</div>
		</section>
	</div>
<?php
}
}

require_once 'Footer.html'; 
?>