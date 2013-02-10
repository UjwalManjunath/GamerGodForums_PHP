<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="description" content="Demo of a Sliding Login Panel using jQuery 1.3.2" />
  	<meta name="keywords" content="jquery, sliding, toggle, slideUp, slideDown, login, login form, register" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />	

	<!-- stylesheets -->
  	<link rel="stylesheet" href="stylesheets/style.css" type="text/css" media="screen" />
  	<link rel="stylesheet" href="stylesheets/slide.css" type="text/css" media="screen" />
	<link href="stylesheets/div.css" rel="stylesheet" type="text/css" media="screen"/>
	<link href="stylesheets/global.css" rel="stylesheet" type="text/css" media="screen"/>
	
  	<!-- PNG FIX for IE6 -->
  	<!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
	<!--[if lte IE 6]>
		<script type="text/javascript" src="js/pngfix/supersleight-min.js"></script>
	<![endif]-->
	 
    <!-- jQuery - the core -->
	<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>
	<!-- Sliding effect -->
	<script src="js/slide.js" type="text/javascript"></script>
		<script src="js/jquery-latest.js" type="text/javascript"></script>
		
</head>
<body>
<!-- Panel -->
<div id="header_wrap">
<div id="toppanel">
	<div id="panel">
		<div class="content clearfix">
			<div class="left">
				<h1>Welcome</h1>
				<h2>Please Login</h2>		
			</div>
			<div class="left">
				<!-- Login Form -->
				<form class="clearfix" action="" method="post">
					<h1>Member Login</h1>
					<label class="grey" for="log">Username:</label>
<?php
					if((isset($_COOKIE['username'])) )
					{
						echo "<input class=\"field\" type=\"text\" name=\"user\" id=\"user\" value=".$_COOKIE['username']." size=\"23\" />";
					}
					else
					{
						echo "<input class=\"field\" type=\"text\" name=\"user\" id=\"user\" value=\"\" size=\"23\" />";
					}
					//<input class="field" type="text" name="user" id="user" value=".$_COOKIE['username']." size="23" />
?>
					<label class="grey" for="pwd">Password:</label>
<?php
					if((isset($_COOKIE['password'])))
					{
						echo "<input class=\"field\" type=\"password\" name=\"pass\" id=\"pass\" value=".$_COOKIE['password']." size=\"23\" />";
					}
					else
					{
						echo "<input class=\"field\" type=\"password\" name=\"pass\" id=\"pass\" value=\"\" size=\"23\" />";
					}
					
					//<input class="field" type="password" name="pass" id="pass" value=".$_COOKIE['password']." size="23" />
?>
					<ul>
					<li><input name="rememberme" id="rememberme" type="checkbox"  value="forever" /> Remember me</li>
					<li><input name="loggedin" id="loggedin" type="checkbox"   value="foreva" /> Stay Logged in</li></ul>
        			<div class="clear"></div>
					<input type="submit" name="submit" value="Sign in" class="bt_login" />
					<a class="lost-pwd" href="lostpassword.php">Lost your password?</a>
				</form>
			</div>
			<div class="left right">			
				<!-- Register Form -->
				<form action="signup.php" method="post">
					<h1>Not a member yet? Sign Up!</h1>				
					<label class="grey" for="signup">Username:</label>
					<input class="field" type="text" name="signup" id="signup" value="" size="23" />
					<label class="grey" for="email">Email:</label>
					<input class="field" type="text" name="email" id="email" size="23" />
					<label>A password will be e-mailed to you.</label>
					<input type="submit" name="submit" value="Register" class="bt_register" />
				</form>
			</div>
		</div>
</div> <!-- /login -->	

	<!-- The tab on top -->	
	<div class="tab">
		<ul id="login12" class="login">
			<li class="left">&nbsp;</li>
			<li id= "welcome">Hello Guest!</li>
			<li class="sep">|</li>
			<li id="toggle">
				<a id="open" class="open" href="#">Log In | Register</a>
				<a id="close" style="display: none;" class="close" href="#">Close Panel</a>			
			</li>
			
			<li class="right">&nbsp;</li>
		</ul> 
	</div> <!-- / top -->
	
</div> 

<body>


<div id="header">
<div id="site_logo"></div>
</div> 

</div>
<div id= "menu_wrap"><div id = "menu">
<ul>
<li><a href="index.php">Forums</a></li>
<li><a href="allmembers.php">Members</a></li>
<li><a href="search.php">Search</a></li>
<div id="Settingsuser"  >
</div>
</ul>
</div>
</div></div>
<div id="content_wrap">
<div id="content">

<div class="main1" id="main1">
<div class="main" id="main">
	