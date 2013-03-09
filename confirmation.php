<?php
session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
include('Header.php');
include('config/config.php');

// Passkey that got from link 
$passkey=$_GET['passkey'];
$tbl_name1="temp_members_db";

// Retrieve data from table where row that match this passkey 
$sql1="SELECT * FROM $tbl_name1 WHERE confirm_code ='$passkey'";
$result1=$conn->query($sql1) or die ("error");

// If successfully queried 
if($result1){

// Count how many row has this passkey
$count=mysqli_num_rows($result1);


// if found this passkey in our database, retrieve data from table "temp_members_db"
if($count==1){

$rows=mysqli_fetch_array($result1);
$firstname=$rows['firstname'];
$lastname=$rows['lastname'];
$username=$rows['username'];
$email=$rows['email'];
$pass=$rows['password']; 
$gender=$rows['Gender'];
$imagename=$rows['profilepicture'];

$tbl_name2="users_proj4";

// Insert data that retrieves from "temp_members_db" into table "registered_members" 
if($imagename==""){
$imagename="NULL";

$sql2="INSERT INTO $tbl_name2(firstname, lastname, user_name, user_email, user_pass, user_date,user_gender,user_level,profileimage)VALUES('$firstname', '$lastname', '$username', '$email', '$pass', CURDATE(),'$gender',0,$imagename)";}
else
$sql2="INSERT INTO $tbl_name2(firstname, lastname, user_name, user_email, user_pass, user_date,user_gender,user_level,profileimage)VALUES('$firstname', '$lastname', '$username', '$email', '$pass', CURDATE(),'$gender',0,'$imagename')";
$result2=$conn->query($sql2) or die ("insertion error");
}// if not found passkey, display message "Wrong Confirmation code" 
else {
echo "Wrong Confirmation code";
}

// if successfully moved data from table"temp_members_db" to table "registered_members" displays message "Your account has been activated" and don't forget to delete confirmation code from table "temp_members_db"
if($result2){
$retval=shell_exec("mv ./images/uploads/temp/$imagename ./images/uploads/profilepics/"); 

echo "Your account has been activated";

// Delete information of this user from table "temp_members_db" that has this passkey 
$sql3="DELETE FROM $tbl_name1 WHERE confirm_code = '$passkey'";
$result3=$conn->query($sql3);

//header( 'Refresh: 5; url=index.php' ) ;

$results=$conn->query("SELECT	user_id,user_name,user_level FROM	users_proj4 where user_name = '$username' And user_pass = '$pass' ");
			$num_rows = mysqli_num_rows($results);
			if($num_rows == 0) 
			{
				echo "error occured";
			}
			else
			{
				$_SESSION['signedin'] =true;
				while ($row = $results->fetch_assoc())
				{
					$_SESSION['user_id'] 	= $row['user_id'];
					$_SESSION['user_name'] 	= $row['user_name'];
					$_SESSION['user_level'] = $row['user_level'];
				}
				echo $_SESSION['user_id'] ;
			}
			?>
			
<script type="text/javascript">
					
					window.location = "https://mweigle418.cs.odu.edu/~umanjuna/proj4/index.php"
				</script>
<?php
//header( 'Refresh: 5; url=index.php' ) ;
//set session variobles here to login
}

}
else
echo "error";

?>