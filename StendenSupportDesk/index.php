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
        <div class="wrapper">
            <nav class="vertical">
                <ul>
                    <li>
                        <a href="#">Home</a>
                        <div>
                            <ul>
                                <li><a href="#">Index</a></li>
                                <li><a href="#">About</a></li>
                                <li><a href="#">Corporate</a></li>
                                <li><a href="#">Contact</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#">Instellingen</a>
                        <div>
                            <ul>
                                <li><a href="#">Autorisatie</a></li>
                                <li><a href="#">Systeem instellingen</a></li>
                                <li><a href="#">Archief</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#">FAQ</a>
                        <div>
                            <ul>
                                <li><a href="#">Ticket aanmaken</a></li>
                                <li><a href="#">Ticket inzien</a></li>
                                <li><a href="#">Ticket sluiten</a></li>
                                <li><a href="#">Contact met medewerker</a></li>                                
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
        <?php
        if ($user->hasPermission('admin')) {
            if (Session::exists('home')) {
                echo "<div class='succes'><p>" . Session::flash('home') . "</p></div>";
            }
            echo "<h1>Autorisatie</h1>";
            echo "<h1>Systeem Instellingen</h1>";
            if (Input::get('page') === "newuser") {
                if (Input::exists()) {
                    if (Token::check(Input::get('token'))) {

                        $validate = new Validate();
                        $validation = $validate->check($_POST, array(
                            'username' => array(
                                'required' => true,
                                'min' => 2,
                                'max' => 20,
                                'unique' => 'users'
                            ),
                            'name' => array(
                                'required' => true,
                                'min' => 2,
                                'max' => 50
                            ),
                            'password' => array(
                                'required' => true,
                                'min' => 6,
                            ),
                            'passwordRepeat' => array(
                                'required' => true,
                                'matches' => 'password',
                            ),
                            'mail' => array(
                                'required' => true,
                                'min' => 2,
                                'max' => 50,
                                'contain' => '@'
                            )
                        ));

                        if ($validation->passed()) {
                            //register user
                            $user = new User();

                            $salt = Hash::salt(32);

                            try {
                                $user->create(array(
                                    'username' => Input::get('username'),
                                    'password' => Hash::make(Input::get('password'), $salt),
                                    'mail' => Input::get('mail'),
                                    'salt' => $salt,
                                    'name' => Input::get('name'),
                                    'joined' => date('Y-m-d H:i:s'),
                                    'IconPath' => 'icons/default.png',
                                    'group_id' => 1
                                ));

                                Session::flash('home', 'You registered succesfully!');
                                Redirect::to('index.php');
                            } catch (Exception $ex) {
                                die($ex->getMessage());
                            }
                        } else {
                            //output errors
                            //print_r($validation->errors());
                            foreach ($validation->errors() as $error) {
                                echo "<div class='error'>" . $error . "</div>";
                            }
                        }
                    }
                }
                ?>
                <div id="container">
                    <div id="message">
                        <h2>Nieuwe gebruiker</h2>
                        <br/>
                        <div id="form">
                            <form action="" method="post">
                                <div class="field">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>"/>
                                </div>
                                <div class="field">
                                    <label for="name">Full Name</label>
                                    <input type="text" name="name" id="name" value="<?php echo escape(Input::get('name')); ?>"/>
                                </div>
                                <div class="field">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" id="password" value="<?php echo escape(Input::get('password')); ?>"/>
                                </div>
                                <div class="field">
                                    <label for="passwordRepeat">Repeat Password</label>
                                    <input type="password" name="passwordRepeat" id="passwordRepeat" value="<?php echo escape(Input::get('passwordRepeat')); ?>"/>
                                </div>
                                <div class="field">
                                    <label for="mail">E-Mail</label><br/>
                                    <input type="email" name="mail" id="mail" value="<?php echo escape(Input::get('mail')); ?>"/>
                                </div>

                                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"/>
                                <input type="submit" value="Toevoegen" id="Button"/>
                            </form>
                            <br/>
                        </div>
                    </div>
                </div>
                <?php
            }
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
        ?>
    </body>
</html>