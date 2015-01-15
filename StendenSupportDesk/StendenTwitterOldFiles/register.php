<!--
Cas van Dinter
384755
-->
<?php
require_once 'core/init.php';

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
<html>
    <head>
        <meta charset="UTF-8">
        <link href = "styles.css" type = "text/css" rel = "stylesheet"/>
        <title>Stenden Twitter || Register</title>
    </head>
    <body>
        <div id="header">
            <div id="headerContent">
                <h2 style="text-align: center"><a href='index.php'>Home</a></h2>
            </div>
        </div>
        <div id="container">
            <div id="message">
                <h1>Register</h1>
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
                            <label for="mail">E-Mail</label>
                            <input type="email" name="mail" id="mail" value="<?php echo escape(Input::get('mail')); ?>"/>
                        </div>
                        
                        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"/>
                        <input type="submit" value="Register" id="Button"/>
                    </form>
                    <br/>
                </div>
            </div>
        </div>

    </body>
</html>