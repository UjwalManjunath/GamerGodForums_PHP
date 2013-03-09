<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'config/config.php';
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
	
#test{
background:#dad;
font-weight:bold;
font-size:16px; }

</style>
<script src="./js/jquery-latest.js"></script>

<script>
function myFunction(id) {
document.getElementById
$("#bekaar"+id).toggle("fast");
};    
</script>

</head>
<body>

<?php
//echo $_POST['del_catid'];
if(isset($_POST['del_catid']))
{
	//echo "in";
    $category = $_POST['del_catid'];
    //echo $category;
    if( $res = $conn->query("delete from categories_proj4 where cat_id = $category"))
    {
        echo "deleted";
    }
}

if((isset($_POST['catsub'])) && (isset($_POST['update_catid'])) && (isset($_POST['catdesc'])))
{
    $catident= 	$_POST['update_catid'];
    $catsubject = $_POST['catsub'];
    $catdescrip = $_POST['catdesc'];
    //echo $catsubject;
    if( $res = $conn->query("update categories_proj4 Set cat_name= '$catsubject', cat_description= '$catdescrip' where cat_id=$catident"))
    {
        echo "updated";
    }
}

if((isset($_POST['catsubject'])) &&  (isset($_POST['catdescription'])))
{
    $catsubj = $_POST['catsubject'];
    $catdescr = $_POST['catdescription'];
    if( $res = $conn->query("Insert into categories_proj4 (cat_name, cat_description)VALUES ('$catsubj', '$catdescr')"))
    {
        echo "New Forum created";
    }
}

$categories= $conn->query("Select c.cat_id,c.cat_name, c.cat_description From categories_proj4 c");
$content = "";
if(($categories) && mysqli_num_rows($categories) > 0)
{
    $content .= '<table id="background-image">';
    $content .= '<thead>';
    $content .= '<tr>';
    $content .= '<th scope="col">Title</th><th></th>';
    $content .= '<th scope="col"><button>+</button></td>';
    $content .= '</tr>';
    $content .= '</thead>';
    while ($row = $categories->fetch_assoc())
    {
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
        $content .= '<a  class="anchor" href="#">'.$row['cat_name'].'</a>';
        $content .= '<div id="slide1" style="background:#de9a44; margin:3px; width:400px;height:100px; display:none; float:left;"> </div>';
        $content .= '</h2>';
        $content .= '<div class="secondRow" >';
        $content .= '<div class="faint" width="400px" style="padding=30px;"> ';
        //$content .= '<a href="#" class="username" title="Thread starter">'.htmlspecialchars($row['user_name']).'</a>,';
        $content .= '<a class="faint" >'.htmlspecialchars($row['cat_description']) .'</a>';
        $content .= '</div>';
        $content .= '<div class="controls faint">';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</td>';
        $content .= '<td width="50px"> <button onclick=myFunction(\''.$row['cat_id'].'\')>Edit</button> </td>';
        $content .= '<td>';
        $content .= '<form method="post" action=""> ';
        $content .= '<input name="del_catid" type="hidden" id="del_catid" value='.$row['cat_id'].'>';
        $content .= '<input name="delete" type="submit" id="delete" value="Delete">';
        $content .= '</form>';
        $content .= '</td>';
        $content .= '</tr >';
        $content .= '<tr style="display:none;" id="bekaar'.$row['cat_id'].'"><td >';
        $content .= '</td><td>';
        $content .=  '<form method="post" action=""> ';
        $content .= '<input name="update_catid" type="hidden" id="update_catid" value='.$row['cat_id'].'>';
        $content .= 'Category Name: <textarea rows="5" cols="20" name="catsub">'.$row['cat_name'].'</textarea>';
        $content .= 'Description: <textarea rows="5" cols="20" name="catdesc">'.$row['cat_description'].'</textarea>';
        $content .= '</td><td><input name="update" type="submit" id="update" value="update">	</form></td><td></td></tr >';
        $content .= '</tbody >';
        $conn2->close();
    }
    $content .= '</table>';
    echo $content;
}
else
{
    echo "NO threads";
}

echo '</br>';
echo '</br>';
echo '<table id = "addnew" style="display:none;"  >';
echo '<tr>';
echo '<form  style="display:none;" method="post" action="">';
echo '<td valign="center">';

echo '<label>Name:</label></td>';
echo '<td ><textarea rows="3" cols="40" name="catsubject"></textarea></td></tr>';
echo '<tr>';
echo '<td valign="center"><label> Description: </label></td>';
echo '<td valign="bottom"><textarea rows="3" cols="40" name="catdescription"></textarea></td>';
echo '</tr>';
echo '<tr><td></td><td><center><input name="addforum" type="submit" id="Add" value="Add new forum"></center></td></tr>';
echo '</table>';

echo '</form>';
echo '<script>';
echo '$("button").click(function () {$("#addnew").toggle("slow");});';
echo '</script>';
echo '</body>';
echo '</html>';
?>