<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
session_start();
require_once 'config/config.php';
require_once 'Header.php';
include 'signin.php';


if(isset($_SESSION['signedin']) && $_SESSION['signedin']==true && isset($_SESSION['user_suspended']) && $_SESSION['user_suspended']==1)
{

    echo '<div class="pageWidth">';
    echo '<div class="pageContent">';
    echo '<div class="titleBar">';
    echo '<div id="titleBar_Helper"><h1> <center>GAMER GOD - Error</center>';
    echo '</h1>';
    echo '</div>';
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
    ?>
<style>
.panelsearch {
    background: #E4E7F5 url(images/gradient_panel.gif) repeat-x top left;
    color: black;
    padding: 10px;
    border: 2px outset;
}

.searchResult {
    border-bottom: 1px solid #D9D7D7;
    padding: 0;
    padding-bottom: 10px;
    overflow: hidden;
    zoom: 1;
}

.button {
    font: 11px verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif;
}

.tcat {
    background: #5C7099 url(images/gradients/gradient_thead.gif) repeat-x top left;
    color: white;
    font: bold 10pt verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif;
}

.thead {
    background: #5C7099 url(images/gradients/gradient_thead.gif) repeat-x top left;
    color: white;
    font: bold 11px tahoma, verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif;
}

Style Attribute {
    text-align: left;
}

.pagesearch {
    color: black;
}

tbody {
    display: table-row-group;
    vertical-align: middle;
    border-color: inherit;
}

.panelsearch {
    color: black;
}

.fieldset, .fieldset td, .fieldset p, .fieldset li {
    font-size: 11px;
}

fieldset {
    display: block;
    -webkit-margin-start: 2px;
    -webkit-margin-end: 2px;
    -webkit-padding-before: 0.35em;
    -webkit-padding-start: 0.75em;
    -webkit-padding-end: 0.75em;
    -webkit-padding-after: 0.625em;
    border: 2px groove threedface;
    border-image: initial;
}

li {
    list-style: none;
}

li {
    display: list-item;
    text-align: -webkit-match-parent;
}

ol {
    display: block;
    list-style-type: decimal;
    -webkit-margin-before: 1em;
    -webkit-margin-after: 1em;
    -webkit-margin-start: 0px;
    -webkit-margin-end: 0px;
    -webkit-padding-start: 40px;
}

.primaryContent {
    background-image: url('styles/aurora/xenforo/white_trans_bg.gif');
    padding: 10px;
    border-bottom: 1px solid #D9D7D7;
}

.searchResult .main {
    padding: 5px;
    margin-left: 56px;
}

.searchResult .avatar {
    float: left;
    margin: 5px 0;
}

.avatar img, .avatar .img, .avatarCropper{
    background-color: #FCFFFF;
    padding: 2px;
    border: 1px solid #BBB;
    border-radius: 4px;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    -khtml-border-radius: 4px;
}

.primaryContent a {
    color: #56575A;
}

.searchResult .titleText {
    overflow: hidden;
    zoom: 1;
    margin-bottom: 5px;
}

.searchResult .contentType {
    float: right;
    color: #C8C8C8;
    font-weight: bold;
    font-size: 11px;
}

.searchResult .title {
    font-size: 12px;
    font-family: Verdana, Tahoma, "Times New Roman", Times, serif;
    float: left;
    text-shadow: #CCC 0px 0px 0px;
}

.searchResult .snippet {
    margin-top: 5px;
    font-size: 11pt;
    font-family: Verdana, Tahoma, "Times New Roman", Times, serif;
    line-height: 1.2;
    font-size: 11px;
    font-style: italic;
}

blockquote {
    display: block;
    -webkit-margin-before: 1em;
    -webkit-margin-after: 1em;
    -webkit-margin-start: 40px;
    -webkit-margin-end: 40px;
}

.searchResult .snippet a {
    color: #141414;
    text-decoration: none;
    font-size: 11px;
}

.searchResult .meta a {
    color: #646464;
}

.searchResult .meta {
    font-size: 11px;
    color: #969696;
}

.searchResult .meta {
    margin-bottom: 2px;
    font-size: 11px;
    color: #969696;
    overflow: hidden;
    zoom: 1;
}
</style>

<?php
    if(isset($_POST['query']) && isset($_POST['forumchoice']))
    {
        $term=$_POST['query'];
        $var= $_POST['forumchoice'];
        $username=$_POST['searchuser'];

        if ($var == "0")
        {
            if(isset($_POST['searchuser']) && $_POST['searchuser']!="")
            {
                
                $query = "select * from categories_proj4 join topics_proj4 on cat_id=topic_cat\n"
                . "JOIN posts_proj4 on topic_id=post_topic join users_proj4 on user_id = post_by\n"
                . "where match(post_content) against('$term' IN BOOLEAN MODE) and user_name like '$username' ";
            
            }
            else
            {
                $query = "select * from categories_proj4 join topics_proj4 on cat_id=topic_cat\n"
                . "JOIN posts_proj4 on topic_id=post_topic  join users_proj4 on user_id = post_by\n"
                . "where match(post_content) against('$term' IN BOOLEAN MODE)";
            }
        }
        else
        {
            if(isset($_POST['searchuser']) && $_POST['searchuser']!="")
            {
                
                $query = "select * from categories_proj4 join topics_proj4 on cat_id=topic_cat\n"
                . "JOIN posts_proj4 on topic_id=post_topic join users_proj4 on user_id = post_by\n"
                . "where match(post_content) against('$term' IN BOOLEAN MODE) and user_name like '$username' and cat_id=$var ";
            }
            else
            {
                $query = "select * from categories_proj4 join topics_proj4 on cat_id=topic_cat\n"
                . "JOIN posts_proj4 on topic_id=post_topic  join users_proj4 on user_id = post_by\n"
                . "where match(post_content) against('$term' IN BOOLEAN MODE) and cat_id=$var";
            }
        }
        $sql = $conn->query($query) or die("error");
        echo '<div id="titleBar_Helper"><h1>Search Results for Query: '.$term.'</a>';
        echo '</h1>';
        echo '</div>';
        $anymatches=mysqli_num_rows($sql);
        if ($anymatches == 0)
        {
            echo "Sorry, but we can not find any results<br><br>"; 
        }
        else
        {	   
            echo '<ol class="searchResultsList">';
            while ($row = $sql->fetch_assoc())
            {//looks into database
                $image_loc = $row['profileimage'];
                $uid = $row['user_id'];
                $cid = $row['cat_id'];
                $tid = $row['topic_id'];
                echo '<li  class="searchResult  primaryContent" style="           width: 900px;" >';
                if($image_loc ==NULL)
                {
                    echo '<div class=" posterAvatar"><a href="member.php?id='.$uid.'" class="avatar" ><img src="images/default_profile_picture.png" width="48" height="48" ></a></div>';
                }
                else
                {
                    echo '<div class=" posterAvatar"><a href="member.php?id='.$uid.'" class="avatar" ><img src="images/uploads/profilepics/'.$image_loc.'" width="48" height="48" ></a></div>';
                }
                echo '<div>';
                echo '<div class="titleText">';
                echo '<span class="contentType" >Post</span>';
                echo '<h3 class="title"><a href="view_thread?topic_id='.$tid.'" style="    font-size: 1.0em;">'.$row['topic_subject'].'</a></h3>';
                echo '</div>';
                echo '<blockquote class="snippet">';
                echo '<a href="view_thread?topic_id='.$tid.'" style="    font-size: 1.3em;">'.$row['post_content'].'</a>';
                echo '</blockquote>';
                echo '<div class="meta" style="    font-size: .9em;">';
                echo 'Post by: <a href="member.php?id='.$uid.'" class="username">'.$row['user_name'].'</a>';
                echo '&nbsp&nbsp&nbspIn forum: <a href="view_forum.php?catid='.$cid.'">'.$row['cat_name'].'</a>';
                echo '</div>';
                echo '</div>';
                echo '</li>';
            }
            echo '</ol>';
                //counts the number or results - give them a message if there wasn't any
            $anymatches=mysqli_num_rows($sql);
            if ($anymatches == 0)
            {
                echo "Sorry, but we can not find any results<br><br>";
            }
                
        }
    }
    else
    {
        echo '<form method="post" action="" >';
        echo '<table class="tborder" cellpadding="6" cellspacing="1" border="0" width="85%" align="center">';
        echo '<tbody><tr>';
        echo '<td class="tcat" style="    height: 22px;">Search Forums';
        echo '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td class="panelsurround" align="center">';
        echo '<table class="panelsearch" cellpadding="0" cellspacing="3" border="0" width="100%">';
        echo '<tbody><tr>';
        echo '<td align="left" valign="top" width="50%">';
        echo '<fieldset class="fieldset" style="margin:0px">';
        echo '<legend style="font-size: 2em;">Search by Keyword</legend>';
        echo '<table cellpadding="0" cellspacing="3" border="0">';
        echo '<tbody><tr>';
        echo '<td colspan="2">';
        echo '<div style="font-size: 1.3em;">Keyword(s):</div>';
        echo '<div><input type="text" class="bginput" name="query" size="35" value="" style="width:350px; font-size:1.5em;" required="required"></div>';
        echo '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>';
        echo '</select>';
        echo '</td>';
        echo '</tr>';
        echo '</tbody></table>';
        echo '</fieldset>';
        echo '</td>';
        echo '<td align="left" valign="top" width="50%">';
        echo '<fieldset class="fieldset" style="margin:0px">';
        echo '<legend style="    font-size: 2em;">Search by User Name</legend>';
        echo '<table cellpadding="0" cellspacing="3" border="0">';
        echo '<tbody><tr>';
        echo '<td colspan="2">';
        echo '<div style="font-size: 1.3em;">User Name:</div>';
        echo '<div id="userfield">';
        echo '<input type="text" class="bginput" name="searchuser" id="userfield_txt" size="35" value="" style="width:350px;font-size:1.5em;" autocomplete="off">';
        echo '</div>';
        echo '<div id="userfield_menu" class="vbmenu_popup" style="display: none; position: absolute; z-index: 50;"></div>';
        echo '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>';
        echo '</td>';
        echo '<td><label for="cb_exactname"></td>';
        echo '</tr>';
        echo '</tbody></table>';
        echo '</fieldset>';
        echo '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td align="left" valign="top" colspan="2">';
        echo '</td>';
        echo '</tr>';
        echo '</tbody></table>';
        echo '<div align="left">';
        echo '<div class="thead" style="padding:6px">Search Options';
        echo '</div>';
        echo '<div id="collapseobj_search_adv" style="">';
        echo '<table class="panel" cellpadding="0" cellspacing="3" border="0" width="100%">';
        echo '<tbody><tr valign="top">';
        echo '<td width="50%">';
        echo '<fieldset class="fieldset" style="margin:0px">';
        echo '<legend style="    font-size: 2em;">Search in Forum(s)</legend>';
        echo '<div style="padding:3px">';
        echo '<div>';
        echo '<select style="width:100%" name="forumchoice" size="17" >';
        echo '<option value="0" selected="selected">Search All Forums</option>';
        $query = "select * from categories_proj4";
        $sql = $conn->query($query) or die("error");
        while ($row = $sql->fetch_assoc())
        {//looks into database
            echo '<option value="'.$row['cat_id'].'" >'.$row['cat_name'].'</option>';
        }
        echo '</select>';
        echo '</div>';
        echo '</div>';
        echo '</fieldset>';
		echo '</td>';
		echo '<td width="50%">';
		echo '</td>';
        echo '</tr>';
        echo '</tbody></table>';
        echo '</div>';
        echo '</div>';
        echo '<div style="margin-top:6px">';
		echo '<input type="submit" class="button" name="dosearch" value="Search Now" >';
		echo '<input type="reset" class="button" value="Reset Fields"  ">';
        echo '</div>';
        echo '</td>';
        echo '</tr>';
        echo '</tbody></table>';
    }
}
require_once 'Footer.html';
?>