<?php
session_start();
session_destroy();
setcookie('keep_meuser', '', time() - 1*24*60*60);
setcookie('keep_mepass', '', time() - 1*24*60*60);
header( 'Location: https://mweigle418.cs.odu.edu/~umanjuna/proj4/index.php' )
?>
