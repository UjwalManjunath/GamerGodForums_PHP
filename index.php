<?php
session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
require_once 'config/config.php';

require 'Header.php';
include 'signin.php';


if(isset($_SESSION['signedin']) && $_SESSION['signedin']==true && isset($_SESSION['user_suspended']) && $_SESSION['user_suspended']==1)
{
	echo '<div class="pageWidth">';
	echo '<div class="pageContent">';
	echo '<div class="titleBar">';
	echo '<div id="titleBar_Helper"><h1> <center>GAMER GOD - Error</center>';
	echo '</h1>';
	echo '</div>';
	echo '<div id="azk99838" style="text-align: center;"></div>';
	echo '<div class="errorOverlay">';
	echo '<a class="close OverlayCloser"></a>';
	echo '<div class="baseHtml"><center>';
	echo '<label for="ctrl_0" class="OverlayCloser">You have been temporarily banned.</label>';
	echo '<label for="ctrl_0" class="OverlayCloser">You do not have permission to view this page or perform this action.</label></center>';
	echo '</div>';
	echo '</div>';
	echo '<div id="azk44987" style="text-align: center;"></div>';
	echo '</div>';
	echo '</div>';
}
else
{
	if(($paginatevar = $conn->query("select no_of_posts from Settings_proj2 where id=1")))
	{
		$result = $paginatevar->fetch_row();
		$_SESSION['no_of_pages']=$result[0];
	}
	else
	{	echo "error: pagination variable set to default";
		$_SESSION['no_of_pages']=5;
	}
	$categories= $conn->query("Select c.cat_id,c.cat_name, c.cat_description From categories_proj4 c");
	$content = "";
	if(($categories) && mysqli_num_rows($categories) > 0) 
	{
		$content .= '<table id="background-image">';
		$content .= '<thead>';
		$content .= '<tr>';
		$content .= '<th scope="col"></th>';
		$content .= '<th scope="col">Forums</th>';
		$content .= '<th scope="col">Stats</th>';
		$content .= '<th scope="col">Last Post</th>';
		$content .= '</tr>';
		$content .= '</thead>';
		while ($row = $categories->fetch_assoc())	{
				
				$content .= '<tbody>';
				$content .= '<tr>';
				$content .= '<td  WIDTH="35px">';
				$content .= '<div class="imaaage">';
				$content .='<span class="avatarContainer">';
				$content .='<a href="#" class="avatar" data-avatarhtml="true"><img src="images/forum_new-48_2.png" width="48" height="48" alt="img"></a>';
				$content .='</span>';
				$content .='</div>';
				
				$content .= '</td>';
				$content .= '<td WIDTH="600px" >';
			
				$content .= '<div class="detailsss" style=" position: absolute; ">';
				$content .= '<div class="titleText">';
				$content .= '<h2 class="title">';
				$content .= '<a  class="anchor" href="view_forum.php?catid='.htmlspecialchars($row['cat_id']) . '">'.$row['cat_name'].'</a>';
				$content .= '</h2>';
				$content .= '<div class="secondRow" >';
				$content .= '<div class="faint" > ';
				$content .= '<a class="faint">'.htmlspecialchars($row['cat_description']) .'</a>';
				$content .= '</div>';
				$content .= '<div class="controls faint">';
				$content .= '</div>';
				$content .= '</div>';
				$content .= '</div>';
				$content .= '</div>';
				$content .= '</td>';
				
				$conn2=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or die ("Hey loser, check your server connection.");	
				$latest = $conn2->query("select count(*) as replies from topics_proj4 where topic_cat=".$row['cat_id']." and user_deleted=0");
				$reply = $latest->fetch_row();
				
				$content .= '<td WIDTH="100px" >';
				$content .= '<div style=" position: absolute; ">';
				$content .= '<a class="username">Threads:</a><a>'.$reply[0].'</a></br>';
				
				$latestpost = $conn2->query("select count(*) as replies from posts_proj4 where post_topic IN(select topic_id from topics_proj4 where topic_cat=".$row['cat_id'].") and user_removed=0");
				$replypost = $latestpost->fetch_row();
				
				$content .= '<a>Posts:</a><a>'.$replypost[0].'</a>';
				$content .= '</div>';
				$content .= '</td>';
				
				$lastby = $conn2->query("select topic_subject,user_name,DATE_FORMAT(topic_date,' %b %d %a, %Y %H:%i'),user_id from topics_proj4 join users_proj4 on(topic_by = user_id) where topic_cat=".$row['cat_id']." and user_status=0 order by topic_date DESC");
				$lastpostby = $lastby->fetch_row();
				
				$content .= '<td >';
				$content .= '<div class="detailsss" style=" position: absolute; ">';
				$content .= '<a href="#" class="username">'.$lastpostby[0].'</a></br>';
				
				if(mysqli_num_rows($lastby) > 0)
				{
					$content .= 'Posted By  <a href="member.php?id='.$lastpostby[3].'"class="mkk">'.$lastpostby[1].'</a></br>';
				}
				else
					$content .= 'No Posts  <a class="mkk">'.$lastpostby[1].'</a></br>';
					
				$content .= '<a class="mkk">'.$lastpostby[2].'</a>';
				$content .= '</div>';
				$content .= '</td >';
				$content .= '</tr >';
				$content .= '</tbody >';
				$conn2->close();
				$conn->close();
			}
			$content .= '</table>';               
			echo $content;
	} 
	else 
	{
		echo "Category error"; 
	}	
}
require_once 'Footer.html'; 
?>
