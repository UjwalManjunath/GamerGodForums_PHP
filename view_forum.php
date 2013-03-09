<?php
session_start();
require_once 'config/config.php';
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
include 'Header.php';
include 'signin.php';
$cat_id= $_GET['catid'];

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
	$forumlist=$conn->query("select cat_name, cat_description from categories_proj4 where cat_id=$cat_id");
	$forum = $forumlist->fetch_row();
	echo '<div class="titleBar">';
	echo '<div id="titleBar_Helper"><h1>'.$forum[0].'</h1>';
	echo '</div>';
	echo '<p id="pageDescription" class="muted baseHtml">'.$forum[1].'</p>';
	echo '</div>';

	include 'paginate_forum.php';

	if(isset($_POST['delete']))
	{
		//echo "in";
	$category = $_POST['del_thread'];
	//echo $category;
	if( $res = $conn->query("delete from topics_proj4 where topic_id = $category"))
	{
	//echo "deleted";
	}
	}

	if(isset($_POST['freeze']))
	{
		//echo "in";
		$category = $_POST['del_thread'];
	//echo $category;
		if( $res = $conn->query("update topics_proj4 set topic_frozen=1 where topic_id = $category"))
		{
			echo "frozen";
		}
	}


	if(isset($_POST['unfreeze']))
	{
		//echo "in";
		$category = $_POST['del_thread'];
	//echo $category;
		if( $res = $conn->query("update topics_proj4 set topic_frozen=0 where topic_id = $category"))
		{
			echo "unfrozen";
		}
	}

	if(($_SESSION['signedin'])==true)
	  {
	echo '<div class="newThread">'
				  .'<a href="post_message.php?id='
				  .htmlspecialchars($cat_id) . '">Create a new thread</a>'
				  .'</div>';  
	} 
	//$latest = $conn->query("select count(*)-1 as replies from posts_proj4 where post_topic=1000");
	$threads=$conn->query("select t.topic_id, t.topic_subject , DATE_FORMAT(t.topic_date,' %b %d %a, %Y %H:%i') as DATE,t.counter, u.user_name,u.user_id,u.profileimage\n"
		. "	 From topics_proj4 t join users_proj4 u on (t.topic_by = u.user_id)\n"
		. "	 where t.topic_cat = '$cat_id' and user_deleted='0' order by t.topic_date LIMIT $start, $limit");
		$content = "";
		if (($threads ) && mysqli_num_rows($threads) > 0)
		{
			
				 $content .= '<table id="background-image">';
				 $content .= '<thead>';
				 $content .= '<tr>';
				 
				 $content .= '<th scope="col"></th>';
				 $content .= '<th scope="col">Thread</th>';
				 $content .= '<th scope="col">Stats</th>'; 	
				  $content .= '<th scope="col">Last Post</th>'; 	
				  $content .= '<th scope="col"></th>';
				 $content .= '</tr>';
				 $content .= '</thead>';
				 
				 while ($row = $threads->fetch_assoc()) {	
				/*	$content .= '<tbody>';
					$content .= '<tr>';
					$content .= '<td>';
					$content .= '<a href="view_thread.php?topic_id=';
					$content .= htmlspecialchars($row['topic_id']) . '">'.$row['topic_subject'].'</a>';
					$content .= '</td>';
					$content .= '<td>'.htmlspecialchars($row['topic_date']).'</td>';
					$content .= '<td>'.htmlspecialchars($row['user_name']).'</td>';
					$content .= '</tr>';
					$content .= '</tbody>';*/
					$$content .= '<tbody>';
					$content .= '<tr>';
					$content .= '<td  WIDTH="35px">';
					$content .= '<div class="imaaage">';
					$content .='<span class="avatarContainer">';
					if( $row['profileimage'] == NULL)
					$content .='<a href="#" class="avatar" data-avatarhtml="true"><img src="images/default_profile_picture.png" width="48" height="48" alt="'.htmlspecialchars($row['user_name']).'"></a>';
					else
					$content .='<a href="#" class="avatar" data-avatarhtml="true"><img src="images/uploads/profilepics/'.$row['profileimage'].'" width="48" height="48" alt="'.htmlspecialchars($row['user_name']).'"></a>';
					$content .='</span>';
					$content .='</div>';
					
					$content .= '</td>';
					$content .= '<td WIDTH="600px" >';
				
					$content .= '<div class="detailsss" style=" position: absolute; ">';
					$content .= '<div class="titleText">';
					$content .= '<h2 class="title">';
					$content .= '<a  class="anchor" href="view_thread.php?topic_id='.htmlspecialchars($row['topic_id']) . '">'.$row['topic_subject'].'</a>';
					$content .= '</h2>';
					$content .= '<div class="secondRow" >';
					$content .= '<div class="faint" > Started By  ';
					$content .= '<a href="member.php?id='.$row['user_id'].'" class="username" title="Thread starter">'.htmlspecialchars($row['user_name']).'</a>,';
					$content .= '<a class="faint">'. htmlspecialchars($row['DATE']).'</a>';
					$content .= '</div>';
					$content .= '<div class="controls faint">';
					$content .= '</div>';
					$content .= '</div>';
					$content .= '</div>';
					$content .= '</div>';
					$content .= '</td>';
					$conn1=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");	
					$latest = $conn1->query("select count(*) as replies from posts_proj4 where post_topic=".$row['topic_id']." and user_removed=0");
					$reply = $latest->fetch_row();
					$content .= '<td WIDTH="90px" >';
					$content .= '<div style=" position: absolute; ">';
					$content .= '<a class="username">Replies:</a><a>'.$reply[0].'</a></br>';

					$content .= '<a>Views:</a><a>'.$row['counter'].'</a>';
					$content .= '</div>';
					$content .= '</td>';
					$lastby = $conn1->query("select user_name,DATE_FORMAT(post_date,' %b %d %a, %Y %H:%i'),user_id from posts_proj4 join users_proj4 on(post_by = user_id) where post_topic=".$row['topic_id']." and user_status=0 order by post_date DESC");
					$lastpostby = $lastby->fetch_row();
					$content .= '<td >';
					$content .= '<div class="detailsss" style=" position: absolute; ">';
					$content .= '<a href="member.php?id='.$lastpostby[2].'" class="username">'.$lastpostby[0].'</a></br>';
				
					$content .= '<a class="mkk">'.$lastpostby[1].'</a>';
					$content .= '</div>';
					$content .= '</td >';
					if(($_SESSION['user_level'])=="1" || ($_SESSION['user_level'])=="2"){
					$content .= '<td>';
					$content .= '<form method="post" action=""> ';
					$content .= '<input name="del_thread" type="hidden" id="del_thread" value='.$row['topic_id'].'>';
					$content .= '<input name="delete" type="submit" id="delete" value="Delete">'; 
					$conn1=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");
					if($frozenthread= $conn1->query("select topic_frozen from topics_proj4 where topic_id=".$row['topic_id'].""))
					if( mysqli_num_rows($frozenthread) >0)
					{
						$froze = $frozenthread->fetch_row();
						
						$tempfreeze = $froze[0];
					}
					else
					{
						echo "topc_frozen count error";
					}
					
					$conn1->close();
					if($tempfreeze == 0)
					$content .= '<input name="freeze" type="submit" id="freeze" value="Freeze">'; 
					else
					$content .= '<input name="unfreeze" type="submit" id="unfreeze" value="Release">'; 
					$content .= '</form>';
					$content .= '</td>';
					}
					else
					{
						$content .= '<td></td>';
					}
					$content .= '</tr >';
					$content .= '</tbody >';
					$conn1->close();
				
				}
				$content .= '</table>';  
				echo $content;
		}
		else
		{
			echo '<p class="error">There aren\'t any threads. Sorry! </p>';   
		}

		include 'paginate_forum.php';
	if(($_SESSION['signedin'])==true)
	  {
	echo '<div class="newThread">'
				  .'<a href="post_message.php?id='
				  .htmlspecialchars($cat_id) . '">Create a new thread</a>'
				  .'</div>';  
	}
}
	
require_once 'Footer.html'; 
?>