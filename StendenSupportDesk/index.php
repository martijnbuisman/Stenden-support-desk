<?php
require_once 'core/init.php';

$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to("login.php");
}
$db = DB::getInstance();
if (Session::exists('home')) {
    echo "<div class='succes'><p>" . Session::flash('home') . "</p></div>";
}
if (Session::exists('error')) {
    echo "<div class='error'><p>" . Session::flash('error') . "</p></div>";
}
$companyExist = $db->query("SELECT * FROM company WHERE id = '" . $user->data()->company_id . "'");
if (!$companyExist->count()) {
    Session::flash('companyError', 'Company does not exist anymore');
    Redirect::to('logout.php');
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
                    <a href="logout.php">Logout</a></p>
                </div>
            </div>
        </div>
        <div class="wrapper">
            <div class="ProfileContainer">
                <div class="ProfileContainerPic">
                    <img src="<?php echo escape($user->data()->IconPath); ?>" class="ProfilePic"/>
                </div>
                <h2><?php echo escape($user->data()->name); ?></h2>
                <h3><?php
                    $company = $db->query("SELECT * FROM company WHERE id = '{$user->data()->company_id}'");
                    echo escape($db->query("SELECT name FROM groups WHERE id = '" . escape($company->first()->group_id) . "'")->first()->name);
                    ?> </h3>
            </div>
            <nav class="vertical">
                <ul>
                    <li>
                        <a href="index.php">Home</a>
                    </li>
                    <?php if ($user->hasPermission('admin') || $user->hasPermission('werknemer')) { ?>
                        <li>
                            <a href="#">Instellingen</a>
                            <div>
                                <ul>
                                    <?php if ($user->hasPermission('admin')) { ?>
                                        <li><a href="index.php?page=autorisation">Autorisatie</a></li>
                                        <li><a href="#">Systeem instellingen</a></li>
                                    <?php } ?>
                                    <li><a href="#">Archief</a></li>
                                </ul>
                            </div>
                        </li>
                    <?php } ?>
                    <li>
                        <a href="index.php?page=faq">FAQ</a>
                        <div>
                            <ul>
                                <li><a href="index.php?page=faq_archief">FAQ archief</a></li>                              
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#">Tickets</a>
                        <div>
                            <ul>
                                <li><a href="#">Ticket aanmaken</a></li>
                                <li><a href="#">Ticket inzien</a></li>
                                <li><a href="#">Ticket sluiten</a></li>                             
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#">Ticket archief</a>
                        <div>
                            <ul>
                                <li><a href="#">Ticket archief inzien</a></li>
                                <li><a href="#">Ticket archief aanpassen</a></li>                           
                            </ul>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="content">
            <?php
            $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
            if (!empty($page)) {
                $page .= '.php';
                if (file_exists($page) && is_readable($page)) {
                    require_once($page);
                } else {
                    Redirect::to('index.php');
                }
            } else {
                if ($user->hasPermission('admin')) {
                    echo "<h1>Autorisatie</h1>";
                    echo "<h1>Systeem Instellingen</h1>";
                } else if ($user->hasPermission('werknemer')) {
                    echo "<h1>Archief</h1>";
                    echo "<h1>FAQ inzien</h1>";
                    echo "<h1>Vraag toevoegen</h1>";
                    echo "<h1>Vraag verwijderen</h1>";
                    echo "<h1>Ticket inzien/aannemen</h1>";
                    echo "<h1>Ticket sluiten/archiveren</h1>";
                    echo "<h1>Ticket archief inzien</h1>";
                    echo "<h1>Ticket archief aanpassen</h1>";
                } else if ($user->hasPermission('ol')) {
                    echo "<h1>FAQ inzien</h1>";
                    echo "<h1>Ticket aanmaken</h1>";
                    echo "<h1>Contact met medewerker</h1>";
                } else if ($user->hasPermission('gb')) {
                    echo "<h1>FAQ inzien</h1>";
                }
            }
            ?>
        </div>
    </body>
</html>