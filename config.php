<?php
// On démarre notre session PHP
session_start();
require('./vendor/autoload.php'); //ici on charge nos dépendances, la librairie 2fa auth 

try {
    $db = new PDO("mysql:host=localhost;dbname=double_auth", 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}