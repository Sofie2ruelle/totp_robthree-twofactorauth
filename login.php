<?php
require('./config.php');
 
use RobThree\Auth\TwoFactorAuth; // ici j'appelle ma librairie (qui correspond au namespace)
 
if (!empty($_POST['email']) && !empty($_POST['password'])) {
    var_dump($_POST);
 
    $email = $_POST['email'];
    $password = $_POST['password'];
    $tfaCode = $_POST['tfa_code'];
 
    $q = $db->prepare('SELECT * FROM users WHERE email = :email');
    $q->bindValue('email', $email);
    $q->execute();
    $user = $q->fetch(PDO::FETCH_ASSOC);
    
    var_dump($user);
    
    if ($user) {
        $passwordHash = $user['password'];
        if (password_verify($password, $passwordHash)) {
            $tfa = new TwoFactorAuth(); // on instancie un objet ($tfa) qui va nous permettre d'utiliser l'authentification 2fa
            if (!$user['secret'] || $tfa->verifyCode($user['secret'], $tfaCode)){
                $_SESSION['user_id'] = $user['id']; // On stocke l'identifiant user dans une variable de session
                header('location:/profile.php'); // On redirige le user vers une page de profile 
                exit();
            } else {
                echo "Code 2FA invalide";
            }
        } else {
            echo "Identifiants invalides";
        }
    } else {
        echo "Identifiants invalides";
    }
}