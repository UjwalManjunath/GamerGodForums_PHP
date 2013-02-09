<?php

define('SQL_HOST','localhost');
define('SQL_USER','a9102828_ujwal');
define('SQL_PASS','muddu_1990');
define('SQL_DB','a9102828_forum');

define('RE_PUBLIC_KEY','6Le_0dYSAAAAAELkEUYyqKHgEechNzjdD_mS53Ri');
define('RE_PRIVATE_KEY','6Le_0dYSAAAAALr91d8LtqutMxiYNh1aGtMJVgsl');

$conn=new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB) or
  die ("Hey loser, check your server connection.");		
?>