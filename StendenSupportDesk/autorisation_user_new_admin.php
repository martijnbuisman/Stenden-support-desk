<?php
///////////////////
//Cas van Dinter///
///////384755//////
///////////////////
if ($user->hasPermission('admin')) {
    //code
    echo "<h1>Autorisatie -> Gebruiker -> Nieuw -> Admin</h1>";

    if (Input::exists()) {
        if (Token::check(Input::get('token'))) {

            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 50
                ),
                'username' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 20,
                    'unique' => 'users'
                ),                
                'password' => array(
                    'required' => true,
                    'min' => 6,
                ),
                'passwordRepeat' => array(
                    'required' => true,
                    'matches' => 'password',
                ),
                'email' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 50,
                    'contain' => '@'
                )
            ));

            if ($validation->passed()) {
                //register user
                
                $salt = Hash::salt(32);

                try {
                    $user->create('users', array(
                        'username' => Input::get('username'),
                        'password' => Hash::make(Input::get('password'), $salt),
                        'email' => Input::get('email'),
                        'salt' => $salt,
                        'name' => Input::get('name'),
                        'joined' => date('Y-m-d H:i:s'),
                        'IconPath' => 'icons/default.png',
                        'company_id' => 1,
                        'function' => 'Admin'
                    ));

                    Session::flash('home', 'Administrator succesfully created!');
                    Redirect::to('index.php?page=autorisation_user_new_admin');
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
    <form action="" method="post" class="basic-grey">
        <h1>Nieuwe Administrator toevoegen
            <span>Alle velden invullen</span>
        </h1>
        <label>
            <span>Naam :</span>
            <input id="name" type="text" name="name" placeholder="Voornaam Achternaam" value="<?php echo escape(Input::get('name')); ?>"/>
        </label>

        <label>
            <span>Gebruikersnaam :</span>
            <input id="username" type="text" name="username" placeholder="gebruikersnaam" value="<?php echo escape(Input::get('username')); ?>"/>
        </label>

        <label>
            <span>Wachtwoord :</span>
            <input id="password" type="password" name="password" placeholder="wachtwoord"/>
        </label>
        <label>
            <span>Wachtwoord herhalen :</span>
            <input id="passwordRepeat" type="password" name="passwordRepeat" placeholder="wachtwoord herhalen"/>
        </label>

        <label>
            <span>Email :</span>
            <input id="email" type="email" name="email" placeholder="random@email.com" value="<?php echo escape(Input::get('email')); ?>"/>
        </label>

        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"/>
        <label>
            <span>&nbsp;</span> 
            <input type="submit" class="button" value="Submit" /> 
        </label>    
    </form>
    <?php
} else {
    Redirect::to('index.php');
}
?>