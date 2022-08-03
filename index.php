<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>totp</title>
</head>
<body>

<form method="POST" action="register.php">
    <input name="email" type="email" placeholder="Email" /><br />
    <input name="password" type="password" placeholder="Mot de passe" /><br />
    <button type="submit">Inscription</button>
</form>

<hr>

<form method="POST" action="login.php">
    <input name="email" type="email" placeholder="Email" /><br />
    <input name="password" type="password" placeholder="Mot de passe" /><br />
    <input type="text" placeholder="Code 2FA" name="tfa_code" /><br />
    <button type="submit">Connexion</button>
</form>

</body>
</html>


