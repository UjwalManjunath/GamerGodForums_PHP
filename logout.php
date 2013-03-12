<?php
session_start();
session_destroy();
setcookie('keep_meuser', '', time() - 1*24*60*60);
setcookie('keep_mepass', '', time() - 1*24*60*60);
header( 'Location: index.php' )
?>
