<?php

// checking if a shortcut is already received and directing the user to the url

if (isset($_GET['q'])) {

    $shortcut = htmlspecialchars($_GET['q']);
    echo $shortcut;
    $db = new PDO('mysql:host=localhost;dbname=url_shortener;charset=utf8', 'root', '');

    $stt = 'SELECT * FROM links WHERE shortcut=? ';
    $req = $db->prepare($stt);
    $req->execute(array($shortcut));
    while ($result = $req->fetch()) {
        header('location:' . $result['url']);
        exit;
    }
}

// verifying if the input is url
if (isset($_POST['url'])) {

    $url = $_POST['url'];

    // verify is the url given has not already been shortcutted
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        header('location: ../?error=true&message= url not valid');
        exit();
    }

    // creating the shortcut based on the function crypt of php
    $shortcut = crypt($url, rand());

    // connecting to the db
    $db = new PDO('mysql:host=localhost;dbname=url_shortener;charset=utf8', 'root', '');

    // preparing the query and executing it (storing in the db)
    $stt = 'INSERT INTO links (url,shortcut) VALUES (?,?) ';
    $req = $db->prepare($stt);
    $req->execute(array($url, $shortcut));

    // redirects you to the main page again after saving the shortcut
    // my project is in a folder called shorten_link please modify the redirection accordingly
    header('location: ../shorten_link/?short=' . $shortcut);
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>URl Shortener</title>
    <link rel="icon" type="image/png" href="pictures/logo.png">
</head>

<body>
    <section class="hello">
        <div>
            <h1>URL too long? Shorten it</h1>
            <h2>Copy your link below and modify it instantly </h2>
        </div>

        <form method="POST" action="../shorten_link/">
            <input id="1" type="url" name="url" placeholder="Shorten your link">
            <input type="submit" value="Shorten">
        </form>
        <div class="center">
            <div id="result">
                <?php if (isset($_GET['error']) && isset($_GET['message'])) {
                ?>
                    <b><?php echo htmlspecialchars($_GET['message']);  ?></b>

                <?php
                } else if (isset($_GET['short'])) {
                ?>
                    <b>new URL : https://localhost/shorten_link/?q=<?php echo htmlspecialchars($_GET['short']); ?> </b>

                <?php
                }
                ?>

            </div>
        </div>
    </section>



    <footer>
        <img src="pictures/logo.png" alt="logo" id="logo"><br>
        Free Source Project <br>
        Developped by Hamza Slaoui <br>
        <a href="https://www.linkedin.com/in/slaouihabibhamza/">Contact</a> <a href="https://github.com/TeaaPartyy/basicProjectsAndGuides/tree/main/shorten_link">Source Code</a>
    </footer>
</body>

</html>
