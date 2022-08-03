<?php
require('./config.php');
// ici on va pouvoir créer un compte 
if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    var_dump($email);
    var_dump($password);

    // On insert dans la base de données le nouveau user 
    $q = $db->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
    $q->bindValue(':email', $email);
    $q->bindValue(':password', $password);
    $res = $q->execute();

    if ($res) {
        echo "Inscription réussie ! ";
    }
}
