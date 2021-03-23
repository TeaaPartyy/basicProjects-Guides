<?php

session_start();
// verifying if input not empty
if (!empty($_POST['email']) && !empty($_POST['password'] && !empty($_POST['password_two']))) {

    // if not empty we automatically connect to the connect.php
    require('src/connect.php');

    $email        = htmlspecialchars($_POST['email']);
    $password     = htmlspecialchars($_POST['password']);
    $password_two = htmlspecialchars($_POST['password_two']);

    // verifying if password/emails are valid, if not we redirect everyone to register again
    //  we modify the url in the header in order to get a message in the url and automatically display it 
    if ($password != $password_two) {
        header('location: inscription.php?error=1&message=passwords not identical.');
        exit();
    }
    // Valid email verification
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('location: inscription.php?error=1&message=email not valid');
        exit();
    }

    // email not used validation

    $req = $db->prepare("SELECT count(*) AS emailnumber FROM users WHERE email =?");
    $req->execute(array($email));

    while ($email_verification = $req->fetch()) {
        if ($email_verification['emailnumber'] != 0) {
            header('location: inscription.php?error=1&message=Email already used');
            exit();
        }
    }
    // hashing the secret key for connexion
    $sec = sha1($email) . time();
    $sec = sha1($sec) . rand();

    // crypting the password
    $password =  sha1($password . "101");

    // adding the new user to the db

    $req = $db->prepare("INSERT INTO users (email,password,sec) VALUES (?,?,?)");
    $req->execute(array($email, $password, $sec));
    header('location: inscription.php?success=1');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="icon" type="image/pngn" href="pictures/favicon.png">
    <title>Netflix Simulation</title>
</head>

<body>
    <?php include('src/header.php') ?>

    <section>
        <div id="login-body">
            <h1>Sign Up</h1>
            <!-- Getting the error message from the "get" in the url and displaying the mesasge error -->
            <?php if (isset($_GET['error'])) {
                if (isset($_GET['message'])) {
                    echo '<div class="alert error">' . htmlspecialchars($_GET['message']) . '</div>';
                }
            } else if (isset($_GET['success'])) {
                echo '<div class= "alert success">Registration Successfull. <a href="index.php">Sign in
                </a>.</div>';
            }
            ?>

            <form method="POST" action="inscription.php">
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <input type="password" name="password_two" placeholder="Re-enter Password" />
                <button type="submit">Register</button>
            </form>

            <p class="grey">Already have an Account? <a href="index.php">Sign in.</a></p>
        </div>
    </section>

    <?php include('src/footer.php') ?>

</body>

</html>