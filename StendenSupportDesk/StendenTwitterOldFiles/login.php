<!--
Cas van Dinter
384755
-->
<?php
require 'core/init.php';

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'required' => true
            ),
            'password' => array(
                'required' => true
            )
        ));

        if ($validation->passed()) {
            $user = new User();

            $remember = (Input::get('remember') === 'on') ? true : false;
            $login = $user->login(Input::get('username'), Input::get('password'), $remember);

            if ($login) {
                Redirect::to('index.php');
            } else {
                echo "<div class='error'>Loggin in failed</div>";
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
        <title>Stenden Twitter || Login</title>
    </head>
    <body>
        <div id="header">
            <div id="headerContent">
                <h2 style="text-align: center"><a href='index.php'>Home</a></h2>
            </div>
        </div>
        <div id="container">
            <div id="message">
                <h1>Login</h1>
                <br/>
                <div id="form">
                <form action="" method="post">
                    <div class="field">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username"/>
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password"/>
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