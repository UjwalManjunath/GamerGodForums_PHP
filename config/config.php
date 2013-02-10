<?php

define('SQL_HOST','localhost');
define('SQL_USER','Yourmysqlusername');
define('SQL_PASS','Yourmysqlpassword');
define('SQL_DB','NameofDatabse');



$conn=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or
  die ("Hey loser, check your server connection.");		
?>