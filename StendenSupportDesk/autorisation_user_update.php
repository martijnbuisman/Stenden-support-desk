<?php
if ($user->hasPermission('admin')) {
//code
    if (Input::get('update')) {
        $id = Input::get('update');
        $userDB = $db->query("SELECT * FROM users WHERE id = '{$id}'");
        if ($userDB->count()) {
            echo "<h1>Gebruiker updaten</h1>";
            if (Input::exists()) {
                if (Token::check(Input::get('token'))) {
                    $validate = new Validate();
                    $validation = $validate->check($_POST, array(
                        'name' => array(
                            'required' => true,
                            'min' => 2,
                            'max' => 50
                        ),
                        'email' => array(
                            'required' => true,
                            'min' => 2,
                            'max' => 50,
                            'contain' => '@'
                        ),
                        'function' => array(
                            'required' => true,
                            'min' => 2,
                            'max' => 50
                        )
                    ));

                    if ($validation->passed()) {
                        try {
                            $db->update('users', $id, array(
                                'email' => Input::get('email'),
                                'name' => Input::get('name'),
                                'function' => Input::get('function')
                            ));

                            Session::flash('home', 'User succesfully updated!');
                            Redirect::to('index.php?page=autorisation_user_exist');
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
                <h1>Gebruiker updaten: <?php echo escape($userDB->first()->username); ?>
                    <span>Alle velden invullen</span>
                </h1>
                <label>
                    <span>Naam :</span>
                    <input id="name" type="text" name="name" placeholder="Voornaam Achternaam" value="<?php echo escape($userDB->first()->name); ?>"/>
                </label>

                <label>
                    <span>Email :</span>
                    <input id="email" type="email" name="email" placeholder="random@email.com" value="<?php echo escape($userDB->first()->email); ?>"/>
                </label>

                <label>
                    <span>Functie :</span>
                    <input id="function" type="text" name="function" placeholder="Functie naam" value="<?php echo escape($userDB->first()->function); ?>"/>
                </label>

                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"/>
                <label>
                    <span>&nbsp;</span> 
                    <input type="submit" class="button" value="Update" /> 
                </label>    
            </form>
            <?php
        } else {
            Session::flash('error', 'That user ID does not exist.');
            Redirect::to('index.php?page=autorisation_user_exist');
        }
    } else {
        Session::flash('error', 'That user ID does not exist.');
        Redirect::to('index.php?page=autorisation_user_exist');
    }
} else {
    Redirect::to('index.php');
}
?>