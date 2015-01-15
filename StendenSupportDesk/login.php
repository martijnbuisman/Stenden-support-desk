<!--
Cas van Dinter
384755
-->
<?php
require 'core/init.php';
$user = new User();
if ($user->isLoggedIn()) {
    Redirect::to("index.php");
}
if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'Gebruikersnaam' => array(
                'required' => true
            ),
            'Wachtwoord' => array(
                'required' => true
            )
        ));

        if ($validation->passed()) {

            $remember = (Input::get('remember') === 'on') ? true : false;
            $login = $user->login(Input::get('Gebruikersnaam'), Input::get('Wachtwoord'), $remember);

            if ($login) {
                Redirect::to('index.php');
            } else {
                echo "<div class='error'>Logging in failed</div>";
            }
        } else {
            foreach ($validation->errors() as $error) {
                echo "<div class='error'>" . $error . "</div>";
            }
        }
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link href = "styles.css" type = "text/css" rel = "stylesheet"/>
        <title>Stenden SupportDesk || Login</title>
    </head>
    <body>
        <div id="header">
            <div id="headerContent">
                <h2 style="text-align: center"><a href='index.php'>Stenden eHelp</a></h2>
            </div>
        </div>
        <div id="container">
            <div id="message">
                <div id="loginTitle">
                    <h1>Login</h1>
                </div>
                <div id="form">
                    <form action="" method="post">
                        <div class="field">
                            <input type="text" name="Gebruikersnaam" id="Gebruikersnaam" value="<?php echo escape(Input::get('username')); ?>" placeholder="Gebruikersnaam"/>
                        </div>

                        <div class="field">
                            <input type="password" name="Wachtwoord" id="Wachtwoord" placeholder="Wachtwoord"/>
                        </div>

                        <div class="field">
                            <label for="remember">
                                <input type="checkbox" name="remember" id="remember"/>
                                Remember me
                            </label>
                        </div>

                        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"/>
                        <input type="submit" value="Log in" id="Button"/>
                    </form>
                    <br/>
                </div>
            </div>
        </div>
    </body>
</html>