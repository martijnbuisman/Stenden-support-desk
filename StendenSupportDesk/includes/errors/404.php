<?php
require_once 'core/init.php';

$user = new User();
?>

<html>
    <head>
        <meta charset="UTF-8">
        <link href = "styles.css" type = "text/css" rel = "stylesheet"/>
        <title>Stenden Twitter || Error</title>
    </head>
    <body>
        <div id="header">
            <div id="headerContent">
                <div id="headerHome">
                    <a href='index.php'><img src="icons/logo.jpg" width="180px" height="40px"/></a>
                </div>
                <div id="headerAccount">
                    <?php
                    if ($user->isLoggedIn()) {
                        ?>
                        <p>Hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a>! <a href="logout.php">Logout</a></p>
                        <?php
                    } else {
                        echo "<a href='login.php'>Log in</a> || <a href='register.php'>Register</a></p>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <div id="container">
            <div id="message">
                <h1>Error</h1>
                <hr><br/>
                <h3>Oops, that page was not found!</h3>
                <br/>
            </div>
        </div>
    </body>
</html>