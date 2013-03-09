<script type="text/javascript">
	function showStuff(id) {
		document.getElementById("pcontent"+id).style.display='none';
		document.getElementById("text_editbox"+id).style.display='block';
 // alert(x.innerHTML);
		}
		
		function showStuff_topic(id) {
		document.getElementById("tcontent"+id).style.display='none';
		document.getElementById("text_edittopic"+id).style.display='block';
 // alert(x.innerHTML);
		}
</script>
<?php
session_start();
require_once 'config/config.php';
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
require_once 'helpers/validation.php';
require_once 'Header.php';
include 'signin.php';
$topicid = clean($_GET['topic_id']);
$tempfreeze=0;

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

	function edited_post($pid)
	{
		$conn1=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
		
		if($postedit= $conn1->query("select post_edited,post_edited_by from posts_proj4 where post_id =$pid") )
		if( mysqli_num_rows($postedit) >0)
		{
			$pedit = $postedit->fetch_row();
			$postedited = $pedit[0];
			//$posteditby = $pedit[1];
				$conn1->close();
			return $postedited;
		}
			$conn1->close();
		return 0;
	}


	function edited_message($tpid)
	{
		$conn1=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
		
		if($msgedit= $conn1->query("select topic_content_edited,topic_content_edited_by from topics_proj4 where topic_id =$tpid") )
		
		if( mysqli_num_rows($msgedit) >0)
		{
			$medit = $msgedit->fetch_row();
			$msgedited = $medit[0];
			$conn1->close();
			//$posteditby = $pedit[1];
			return $msgedited;
		}
		$conn1->close();
		return 0;
	}

	function edited_message_by($tpid)
	{
		$conn1=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
		
		if($msgedit= $conn1->query("select topic_content_edited,topic_content_edited_by from topics_proj4 where topic_id =$tpid") )
		if( mysqli_num_rows($msgedit) >0)
		{
			$medit = $msgedit->fetch_row();
			
			$msgeditby = $medit[1];
			$conn1->close();
			return $msgeditby;
		}
		$conn1->close();
		return 0;
	}



	function edited_post_by($pid)
	{
		$conn1=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
		
		if($postedit= $conn1->query("select post_edited,post_edited_by from posts_proj4 where post_id =$pid") )
		if( mysqli_num_rows($postedit) >0)
		{
			$pedit = $postedit->fetch_row();
			
			$posteditby = $pedit[1];
			$conn1->close();
			return $posteditby;
		}
		$conn1->close();
		return 0;
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

	$messagelist=$conn->query("select topic_subject, user_name,cat_name,DATE_FORMAT(user_date,' %d %M %Y ') as USERDATE from topics_proj4 join categories_proj4 join users_proj4 on(cat_id=topic_cat and topic_by=user_id) where topic_id=$topicid and user_deleted=0");
	$message = $messagelist->fetch_row();
	echo '<div class="titleBar">';
	echo '<div id="titleBar_Helper"><h1>'.$message[0].'</h1>';
	echo '</div>';
	echo '<p id="pageDescription" class="muted ">';
	echo 'Discussion in <a>'.$message[2].'</a>';
	echo ' started by <a class="titlebar1">'.$message[1].'</a>';
	echo ', <a >'.$message[3].'</a>';
	echo '</p></div>';

	include 'paginate_post.php';



	if(isset($_POST['update_pcontent']))
	{
			//	echo "set";
		
			$pstcnt = $_POST['post_content_text'];
			$pstid = $_POST['up_post'];
			//echo $pstid;
			$dat = date(' Y-m-d H:i:s');
			$usr= $_SESSION['user_name'];
			if( $res = $conn-> query("select * from topics_proj4 where topic_id IN (select post_topic from  posts_proj4 where post_id=$pstid)"))
			{
				if( mysqli_num_rows($res) >0)
				{
					if( $res = $conn->query("update posts_proj4 set post_content ='$pstcnt', post_edited='$dat' , post_edited_by='$usr' where post_id = $pstid"))
					{	
						echo "POST updated";
					}
					else
					{
						echo "updating error";
					}
				}else{
				?>
				<script type="text/javascript">
					window.alert("I am sorry the thread does not exist anymore!!")
					window.location = "https://mweigle418.cs.odu.edu/~umanjuna/proj4/index.php"
				</script>
				<?php
				}
			}
			
			
	}

	if(isset($_POST['update_tcontent']))
	{
			//echo "set";	
			$msgcnt = $_POST['message_content_text'];
			$msgid = $_POST['up_message'];
			//echo $pstid;
			$dat = date('Y-m-d H:i:s');
			$usr= $_SESSION['user_name'];
			
			if( $res = $conn->query("update topics_proj4 set topic_content ='$msgcnt', topic_content_edited='$dat' , topic_content_edited_by='$usr' where topic_id = $msgid"))
			{	
				echo "Message updated";
			}
			else
			{
				echo "updating error";
			}	
			
	}
		


	if(isset($_POST['edit_post']))
	{
	//	echo "set";	
		if($_REQUEST['delete_userpost'] == 'DELETE')
		{
			$pstid = $_POST['edit_post'];
			//echo $pstid;
			if( $res = $conn->query("delete from posts_proj4 where post_id = $pstid"))
			{	
				echo "deleted";
			}
		}	
	}


	$result = processPost(false);
	$messageBox = messageBox(false,$result['errorHTML'],$topicid); 
	if(($_SESSION['signedin'])==true)
	{
		$counter = $conn->query("select counter from topics_proj4 where topic_id =  '$topicid'");
		$coun = $counter->fetch_row();
		//echo $coun[0];
		$count= $coun[0]+1;
		$res=$conn -> query("update topics_proj4 set counter = '$count'  where topic_id='$topicid'");
		//echo "update";
	}



	$posts= $conn->query("select t.topic_subject,t.topic_content,DATE_FORMAT(t.topic_date,' %b %d %a, %Y %H:%i') as DATE,u.user_id, u.user_name,u.user_level,DATE_FORMAT(u.user_date,'  %d %M %Y ') as USERDATE,u.profileimage from topics_proj4 t join users_proj4 u on (t.topic_by = u.user_id) where t.topic_id = '$topicid' ") ;
	if(($posts) && mysqli_num_rows($posts)>0)
	{	
		
		
		if($page == 1)
		{
		$content ="";
		while($row = $posts->fetch_assoc())
		{
			$role = functionrole($row['user_id'],$row['user_level']);
			$content .= '<table class="tborder"  cellpadding="6" cellspacing="1" border="0" width="95%" align="center">';
			$content .= '<tbody><tr>';
			$content .= '<td class="thead">';
			$content .= '<div class="normal" style="float:right">';
			if(((isset($_SESSION['signedin'])) && ($_SESSION['signedin']==TRUE)))
			{
				//echo $row['user_id'];
				if(  ($_SESSION['user_id'] == $row['user_id']) || ($_SESSION['user_level'])=="1" || ($_SESSION['user_level'])=="2")
				{
					$content .= '<ol style="list-style: none; height: 24px;">';
					$content .= '<li style="display: inline;"> <button style = "  padding: 1 5 1 5px;"    onclick="showStuff_topic(\''.$topicid.'\')">Edit</button></li>'; 
					//$content .= '</div>';
				//	$content .= '<div class="normal" style="float:right; width:40px; height: 24px;">';  // ;
					$content .= '<form style = "width = 50px;" method="post" action=" "> ';
					//$content .= '<input style="border:none;padding:0px;margin: 2 0 0 0px;" name="delete_usermessage" value="DELETE" type="image" src="./images/delete_button.gif" width="39px" height="21px" >'; 
					$content .= '<input name="edit_message" type="hidden" id="edit_message" value='.$topicid.'>';
					$content .= '</form></ol>';	
					$content .= '</div>';
				}
				
				
			}	
			
			$content .= '</div>';
			$content .= '<div class="normal" style=" height: 24px; ">';
			$content .= '<a name="post1646867"><img class="inlineimg" src="images/post_old.gif" alt="Old" border="0" title="Old"></a>';
			$content .= '<a>'.htmlspecialchars($row['DATE']).'</a>';
			$content .='</div></td></tr>';
			$content .='<tr>';
			$content .='<td class="alt2" style="padding:0px">';
			$content .='<table cellpadding="0" cellspacing="6" border="0" width="100%">';
			$content .='<tbody><tr>';
			if( $row['profileimage']==NULL)
				$content .='<td class="alt2"><a href="member.php?id='.$row['user_id'].'"><img src="images/default_profile_picture.png" width="60" height="60" alt="abc avatar" border="0" title="abc avatar"></a></td>';
			else
				$content .='<td class="alt2"><a href="member.php?id='.$row['user_id'].'"><img src="images/uploads/profilepics/'.$row['profileimage'].'" width="60" height="60" alt="abc avatar" border="0" title="abc avatar"></a></td>';
			$content .='<td style="vertical-align:middle;" nowrap="nowrap">';
			$content .='<div id="postmenu">';
			$content .='<a class="bigusername" href="member.php?id='.$row['user_id'].'">'.htmlspecialchars($row['user_name']).'</a></br>';
			$content .='<a>'.$role.'</a>';
			$content .='</div>';
			$content .='</td>';
			$content .='<td width="100%">&nbsp;</td>';
			$content .='<td valign="top" nowrap="nowrap" style="vertical-align:top;">';
			$content .='<div class="smallfont" >';
			
			$content .=	'<div>Join Date: '.htmlspecialchars($row['USERDATE']).' </div>';
			$content .=	'<div>Last Post: '.user_latest_post_date($row['user_id']).' </div>';
			$content .=	'<div>Threads: '.thread_count($row['user_id']).'  </div>';
			$content .=	'<div>Posts: '.post_count($row['user_id']).' </div>';
			$content .='<div>   </div>';
			$content .='</div>';
			$content .='</td>';
			$content .='</tr>';
			$content .='</tbody></table>';
			$content .='</td>';
			$content .='</tr>';
			$content .='<tr>';
			$content .='<td class="alt1" id="td_post_1646867">';
		
			$content .='<hr size="1" style="color:#D1D1E1; background-color:#D1D1E1">';
			$content .='<div id="post_message1">';
		
			$content .='<label id= "tcontent'.$topicid.'" class="content" style="display:block;" >'.htmlspecialchars($row['topic_content']).'</label><br>';
			$content .=  '<form id="text_edittopic'.$topicid.'" style="display:none;" method="post" action=""> ';
			$content .= '<input name="up_message" type="hidden" id="up_message" value='.$topicid.'>';
			$content .= '<textarea  rows="4" cols="50" name="message_content_text">'.htmlspecialchars($row['topic_content']).'</textarea>';
			$content .= '<input  name="update_tcontent" type="submit" id="update" value="update">	</form><div>';
			/* displayin images thumbnails in message */
			$conn1=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
			
			if($topic_attach= $conn1->query("select message_attachment from  attachments_proj4 where message_id =$topicid") )
			{
				if( mysqli_num_rows($topic_attach) >0)
				{
					
					while($attach = $topic_attach->fetch_assoc())
					{
						if(isset($_SESSION['signedin']) && $_SESSION['signedin']==true )
						$content .= '<A HREF="images/uploads/postpics/'.$attach['message_attachment'].'" target=_"blank"> <IMG HEIGHT=50 WIDTH=50 SRC="images/uploads/postpics/'.$attach['message_attachment'].'"></A>';
						else
						$content .= '<A HREF="#" > <IMG HEIGHT=50 WIDTH=50 SRC="images/no_photo.png"></A>';
					}
				}
			}
			else
			{
				die("error");
			}
			
			$conn1->close();
			/* end */
			$content .= '</div>';
			
			if(edited_message($topicid))
			{
				$content .='<div style="float:right;">Post edited on '.edited_message($topicid).' by '.edited_message_by($topicid).'</div>';
				
			}	
			//$content .='<br>';*/
			
			$content .='</div>';
			$content .='<div style="margin-top: 10px" align="right">';
			
			$content .='</div>';
			$content .='</td>';
			$content .='</tr>';  
			$content .='</tbody></table>';
			
		}
		echo $content;
		}
		
		$content = "";

		$posts= $conn->query("select p.post_id,u.user_id,u.user_name,user_level,DATE_FORMAT(p.post_date,' %b %d %a, %Y %H:%i') as DATE,DATE_FORMAT(u.user_date,'  %d %M %Y ') as USERDATE,u.profileimage,p.post_content from topics_proj4 t join posts_proj4 p join users_proj4 u on (t.topic_id=p.post_topic and t.user_deleted=p.user_removed and p.post_topic = '$topicid' and u.user_id = p.post_by and p.user_removed='0') order by p.post_date LIMIT $start, $limit");
		if(($posts) && mysqli_num_rows($posts)>0)
		{
			while($row = $posts->fetch_assoc())
			{
			$role = functionrole($row['user_id'],$row['user_level']);
			$content .= '<table class="tborder"  cellpadding="6" cellspacing="1" border="0" width="95%" align="center">';
			$content .= '<tbody><tr>';
			$content .= '<td class="thead" ;>';
			$content .= '<div class="normal" style="float:right; width:82px; height: 24px;">';  // ;
			if(((isset($_SESSION['signedin'])) && ($_SESSION['signedin']==TRUE)))
			{
				//echo $row['user_id'];
				if(  ($_SESSION['user_id'] == $row['user_id']) || (($_SESSION['user_level'])=="1" )|| (($_SESSION['user_level'])=="2"))
				{
					$content .= '<ol style="list-style: none; height: 24px;">';
					$content .= '<li style="display: inline;"> <button style = "  padding: 1 5 1 5px;"    onclick="showStuff(\''.$row['post_id'].'\')">Edit</button></li>'; 
					//$content .= '</div>';
					$content .= '<div class="normal" style="float:right; width:40px; height: 24px;">';  // ;
					$content .= '<form style = "width = 50px;" method="post" action=" "> ';
					$content .= '<input style="border:none;padding:0px;margin: 2 0 0 0px;" name="delete_userpost" value="DELETE" type="image" src="./images/delete_button.gif" width="39px" height="21px" >'; 
					$content .= '<input name="edit_post" type="hidden" id="edit_post" value='.$row['post_id'].'>';
					$content .= '</form></ol>';	
					$content .= '</div>';
				}
			/*	else
				if(($_SESSION['user_level'])=="1" || ($_SESSION['user_level'])=="2")
				{
					$content .= '<form method="post" action=""> ';
					$content .= '<input name="del_post" type="hidden" id="del_post" value='.$row['post_id'].'>';
					$content .= '<input style="border:none;padding:0px; float:right;vertical-align:middle;margin: 5px;" name="delete" type="image" src="./images/delete_button.gif" width="30px" height="15px" >'; 
					$content .= '</form>';
				} */
				
			}	
			$content .= '</div>';
			$content .= '<div class="normal" style=" height: 24px;">';
			$content .= '<a name="post1646867"><img class="inlineimg" src="images/post_old.gif" alt="Old" border="0" title="Old"></a>';
			$content .= '<a>'.htmlspecialchars($row['DATE']).'</a>';
			$content .='</div></td></tr>';
			$content .='<tr>';
			$content .='<td class="alt2" style="padding:0px">';
			$content .='<table cellpadding="0" cellspacing="6" border="0" width="100%">';
			$content .='<tbody><tr>';
			if($row['profileimage']==NULL)
				$content .='<td class="alt2"><a href="member.php?id='.$row['user_id'].'"><img src="images/default_profile_picture.png" width="60" height="60" alt="abc avatar" border="0" title="abc avatar"></a></td>';
			else
				$content .='<td class="alt2"><a href="member.php?id='.$row['user_id'].'"><img src="images/uploads/profilepics/'.$row['profileimage'].'" width="60" height="60" alt="abc avatar" border="0" title="abc avatar"></a></td>';
			$content .='<td style="vertical-align:middle;" nowrap="nowrap">';
			$content .='<div id="postmenu_1646867">';
			$content .='<a class="bigusername" href="member.php?id='.$row['user_id'].'">'.htmlspecialchars($row['user_name']).'</a></br>';
			$content .='<a>'.$role.'</a>';
			$content .='</div>';
			$content .='</td>';
			$content .='<td width="100%">&nbsp;</td>';
			$content .='<td valign="top" style="vertical-align:top;" nowrap="nowrap">';
			$content .='<div class="smallfont">';
			
			
			
			
			$content .=	'<div>Join Date: '.htmlspecialchars($row['USERDATE']).'</div>';
			$content .=	'<div>Last Post:  '.user_latest_post_date($row['user_id']).'</div>';
			$content .=	'<div>Threads: '.thread_count($row['user_id']).'  </div>';
			$content .=	'<div>Posts: '.post_count($row['user_id']).' </div>';
			$content .='<div>   </div>';
			$content .='</div>';
			$content .='</td>';
			$content .='</tr>';
			$content .='</tbody></table>';
			$content .='</td>';
			$content .='</tr>';
			$content .='<tr>';
			$content .='<td class="alt1" id="td_post_1646867">';
			
			$content .='<hr size="1" style="color:#D1D1E1; background-color:#D1D1E1">';
			$content .='<div id="post_message1">';
			//$content .=htmlspecialchars($row['post_content']).'<br>';
			$content .='<label id= "pcontent'.$row['post_id'].'" class="content" style="display:block;" >'.htmlspecialchars($row['post_content']).'</label><br>';
			$content .=  '<form id="text_editbox'.$row['post_id'].'" style="display:none;" method="post" action=""> ';
			$content .= '<input name="up_post" type="hidden" id="up_post" value='.$row['post_id'].'>';
			$content .= '<textarea  rows="4" cols="50" name="post_content_text">'.htmlspecialchars($row['post_content']).'</textarea>';
			//$content .= '</td><td>';
			$content .= '<input  name="update_pcontent" type="submit" id="update" value="update">	</form><div>';
			/* displayin images thumbnails in message */
			$conn2=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
			$pid = $row['post_id'];
			if($post_attach= $conn2->query("select post_attachment from  attachments_proj4 where post_id =$pid") )
			{
				if( mysqli_num_rows($post_attach) >0)
				{
					while($attach1 = $post_attach->fetch_assoc())
					{
						if(isset($_SESSION['signedin']) && $_SESSION['signedin']==true )
						$content .= '<A HREF="images/uploads/postpics/'.$attach1['post_attachment'].'" target=_"blank"> <IMG HEIGHT=50 WIDTH=50 SRC="images/uploads/postpics/'.$attach1['post_attachment'].'"></A>';
						else
						$content .= '<A HREF="#" > <IMG HEIGHT=50 WIDTH=50 SRC="images/no_photo.png"></A>';
					}
				}
			}
			else
			{
				die("error");
			}
			
			$conn2->close();
			/* end */
			$content .= '</div>';
			
			
			
			//$content .='<br>';
			if(edited_post($row['post_id']))
			{
			//$content .='dasdsad';
			$content .='<div style="float:right;">Post edited on '.edited_post($row['post_id']).' by '.edited_post_by($row['post_id']).'</div>';
			//$content .='</div>';
			}
			$content .='</div>';
			$content .='<div style="margin-top: 10px" align="right">';
			
			$content .='</div>';
			$content .='</td>';
			$content .='</tr>';  
			$content .='</tbody></table>';
				
				
			}
			
		}
		
		echo $content;
		
		
		include 'paginate_post.php';
		echo '</br></br></br></br>';
		$conn1=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
		if($frozenthread= $conn1->query("select topic_frozen from topics_proj4 where topic_id=$topicid") )
		if( mysqli_num_rows($frozenthread) >0)
		{
			$froze = $frozenthread->fetch_row();
			
			$tempfreeze = $froze[0];
			
			
		}
		$conn1->close();
		
		
		
		
		if($tempfreeze==1)
		{
			echo '<p class="error">This thread had been closed. NO more posts allowed </p>';
		}
		else
		if(($_SESSION['signedin'])==true && $tempfreeze==0)
		{	
			echo '</br>';
			echo '</br>';
			echo '</br>';
			echo $messageBox;
		}
	}
	else
	{
		echo '<p class="error">There aren\'t any threads. Sorry! </p>';
	}
}
require_once 'Footer.html';
?>