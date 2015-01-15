<?php
require_once 'core/init.php';

$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to("login.php");
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link href = "styles.css" type = "text/css" rel = "stylesheet"/>
        <title>Stenden SupportDesk</title>
    </head>
    <body>
        <div id="header">
            <div id="headerContent">
                <div id="headerHome">
                    <h2 style="text-align: center"><a href='index.php'>Stenden eHelp</a></h2>
                </div>
                <div id="headerAccount">
                    <p>Hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a>! <a href="logout.php">Logout</a></p>
                </div>
            </div>
        </div>
        <?php
        if($user->hasPermission('admin')){
            echo "<h1>Autorisatie</h1>";
            echo "<h1>Systeem Instellingen</h1>";
        }else if($user->hasPermission('werknemer')){
            echo "<h1>Archief</h1>";
            echo "<h1>FAQ inzien</h1>";
            echo "<h1>Vraag toevoegen</h1>";
            echo "<h1>Vraag verwijderen</h1>";
            echo "<h1>Ticket inzien/aannemen</h1>";
            echo "<h1>Ticket sluiten/archiveren</h1>";
            echo "<h1>Ticket archief inzien</h1>";
            echo "<h1>Ticket archief aanpassen</h1>";
        }else if($user->hasPermission('ol')){
            echo "<h1>FAQ inzien</h1>";
            echo "<h1>Ticket aanmaken</h1>";
            echo "<h1>Contact met medewerker</h1>";
        }else if($user->hasPermission('gb')){
            echo "<h1>FAQ inzien</h1>";
        }
        ?>
    </body>
</html>