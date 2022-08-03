<?php
require('./config.php');

// Ici on affiche le profil du user une fois connecté
// On crée ici une instance pour utiliser le bon namespace en PHP pour accéder à la librairie tfa
use RobThree\Auth\TwoFactorAuth;
$tfa = new TwoFactorAuth(); // ici on crée un objet qui va permettre la double auth (tfa : two factor authentication)

$secret = $tfa->createSecret(); // on crée notre code secret (en utilisant la méthode de la librairie)

if (empty($_SESSION['tfa_secret'])) { // si la variable de session est vide alors on va crée un nouveau secret 
    $_SESSION['tfa_secret'] = $tfa->createSecret(); // on stocke notre secret dans une variable SESSION pour éviter que le code change au rechargement de la page
}
$secret = $_SESSION['tfa_secret']; // ici on génère notre secret dans notre session

if (empty($_SESSION['user_id'])) { // Si on a pas de user connecté on est redirigé vers la page d'accueil
    header('location:/');
    exit();
}

if (!empty($_POST['tfa_code'])) {
    if ($tfa->verifyCode($secret, $_POST['tfa_code'])) { // VerifyCode généré par la librairie, pour checker le code saisie par le user par rapport à un code secret : le code secret $secret et celui du user $_POST
        echo "Code valide";
        // Si le code est valide, on le stocke dans la base de données
        $q = $db->prepare('UPDATE users SET secret = :secret WHERE id = :id'); // id du user
        $q->bindValue('secret', $secret);
        $q->bindValue('id', $_SESSION['user_id']);
        $q->execute();
    } else {
        echo "Code invalide";
    }
}

// ici on va chercher notre user dans la base de donnée suite à la requête sa requete
$userReq = $db->prepare('SELECT * FROM users WHERE id = :id');
$userReq->bindValue('id', $_SESSION['user_id']);
$userReq->execute();
$user = $userReq->fetch(PDO::FETCH_ASSOC);

?>

<h1>Votre profil</h1>

<a href="/logout.php">Déconnexion</a>
<?php var_dump($user) ?>

<h2>Activation Double Authentification</h2>

<?php if (!$user['secret']) : ?> <!-- si on n'a pas de secret enregistré dans la bdd pour ce user -->
    <p>Code secret : <?= $secret ?></p> <!-- on utilise ici notre variable de session -->
    <p>QR Code :</p>
    <img src="<?= $tfa->getQRCodeImageAsDataUri('TOTP', $secret) ?>"> <!-- On récupère notre QrCode sous forme de data uri (c'est à dire une longue chaine de caractères qui est la source de notre image ), on entre en paramètre le nom de notre site, et en second notre secret qu'on a crée -->
    <form method="POST">
        <input type="text" placeholder="Vérification Code" name="tfa_code">
        <button type="submit">Valider</button>
    </form>
<?php else : ?> <!-- si on a déjà une double authentification on affichera : -->
    <p>2FA activée</p>
<?php endif ?>