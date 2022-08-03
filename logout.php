<?php
require('./config.php');
 
$_SESSION = [];
session_destroy();
header('location:/'); // on est redirigé vers l'accueil
exit();