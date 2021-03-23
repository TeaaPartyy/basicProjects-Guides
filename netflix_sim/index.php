<?php

session_start();

// if user already is connected, no need for them to see the sign up form

if (!empty($_POST['email']) && !empty($_POST['password'])) {

    require('src/connect.php');

    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // verifying the email address format

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('location: index.php?error=1&message=email not valid');
        exit();
    }

    // crypting the password    
    $password =  sha1($password . "101");

    // verifying if email address if already used

    $req = $db->prepare("SELECT count (*) AS nbemail FROM users WHERE email = ?");
    $req->execute(array($email));

    while ($email_verificiation = $req->fetch()) {
        if ($email_verificiation['nbemail'] != 1) {
            header('location: index.php?error=1&message=email or password incorrect');
            exit();
        }
    }

    // verifying the password
    $req = $db->prepare("SELECT * FROM users where email = ?");
    $req->execute(array($email));

    while ($user = $req->fetch()) {

        if ($password == $user['password']) {
            $_SESSION['connect'] = 1;
            $_SESSION['email'] = $user['email'];

            // creating cookie to use the "remember me" checkbox and automatically connect

            header('location: index.php?success=1');
            exit();
        } else {
            header('location: index.php?error=1&message=Email or password incorrect');
            exit();
        }
    }
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
        <div id='login-body'>

            <!-- if there is already a session created (successfully connected we direct the user to another side) -->

            <?php
            if (isset($_SESSION['connect'])) {

                if (isset($_GET['success'])) {
                    echo '<div class = "alert success"> Successfully Connected </div>';
                }
            ?>
                <h1>Welcome</h1>
                <p>Which show do you want to watch today?</p>
                <small><a href="logout.php">Sign out.</a></small>
            <?php
                // else, the user needs to sign in
            } else { ?>

                <h1>Sign In</h1>

                <?php
                if (isset($_GET['error'])) {
                    if (isset($_GET['message'])) {
                        echo '<div class = "alert error">' . htmlspecialchars($_GET['message']) . '</div>';
                    }
                }
                ?>

                <form method="POST" action="index.php">
                    <input type="email" name="email" placeholder="Email" required />
                    <input type="password" name="password" placeholder="Password" required />
                    <button type="submit"> Connect</button>
                    <label id="option">
                        <input type="checkbox" name="auto" checked /> Remember me
                    </label>
                </form>
                <p class="grey">New to Netflix? <a href="inscription.php">Sign up now.</a>.</p>
            <?php
            }
            ?>

        </div>
    </section>

    <?php include('src/footer.php') ?>
</body>

</html>