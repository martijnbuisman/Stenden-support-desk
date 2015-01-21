<?php
if ($user->hasPermission('admin')) {
    //code
    echo "<h1>Autorisatie</h1>";
    echo "<a class='buttonLink' href='index.php?page=autorisation_user'>Gebruiker</a><br/><br/>";
    echo "<a class='buttonLink' href='index.php?page=autorisation_company'>Bedrijf</a>";
} else {
    Redirect::to('index.php');
}
if ($user->hasPermission('admin') && Input::get('pages')) {
    if (Input::get('pages') === 'user') {
        echo "<h2>Gebruiker</h2>";
        if (!Input::get('subpage')) {
            echo "<a class='buttonLink' href='autorisation.php?page=user&subpage=new'>Nieuw</a><br/><br/>";
            echo "<a class='buttonLink' href='autorisation.php?page=user&subpage=exist'>Bestaand</a>";
        }
        if (Input::get('subpage') === 'new') {
            ?>
            <h1>Nieuwe gebruiker</h1>
            <br/>
            <div id="form" style="text-align: right; width: 50%;">
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
                    <input type="submit" value="Toevoegen" id="Button"/>
                </form>
                <br/>
            </div>
            <?php
        }
        if (Input::get('subpage') === 'exist') {
            echo "Zoek";
        }
    } else if (Input::get('page') === 'company') {
        echo "<h2>Bedrijf</h2>";
        if (!Input::get('subpage')) {
            echo "<a class='buttonLink' href='autorisation.php?page=company&subpage=new'>Nieuw</a><br/><br/>";
            echo "<a class='buttonLink' href='autorisation.php?page=company&subpage=exist'>Bestaand</a>";
        }
        //////////////////
        //Create Company//
        //////////////////
        if (Input::get('subpage') === 'new') {
            if (Input::exists()) {
                if (Token::check(Input::get('token'))) {
                    if (Input::get('onderhoudslicentie') === 'yes') {
                        $group = 4;
                    } else {
                        $group = 3;
                    }
                    $validate = new Validate();
                    $validation = $validate->check($_POST, array(
                        'name' => array(
                            'required' => true,
                            'min' => 2,
                            'max' => 50,
                            'unique' => 'company'
                        ),
                        'onderhoudslicentie' => array(
                            'required' => true
                        )
                    ));

                    if ($validation->passed()) {
                        //register user
                        $user = new User();

                        try {
                            $user->create('company', array(
                                'name' => Input::get('name'),
                                'group_id' => $group
                            ));

                            Session::flash('home', 'Company has been created!');
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
            <h1>Nieuw bedrijf</h1>
            <br/>
            <div id="form" style="text-align: left; width: 50%; margin: 5px;">
                <form action="" method="post">
                    <div class="field">
                        <label for="name">Bedrijfsnaam</label><br/>
                        <input type="text" name="name" id="name" value="<?php echo escape(Input::get('name')); ?>"/>
                    </div>
                    <div class="field">
                        Onderhouds Licentie<br/>
                        <select name="onderhoudslicentie" id="onderhoudslicentie"> 
                            <option value="no" >Nee</option>
                            <option value="yes" >Ja</option>
                        </select>
                    </div>
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"/>
                    <input type="submit" value="Toevoegen" id="Button"/>
                </form>
                <br/>
            </div>
            <?php
        }
        if (Input::get('subpage') === 'exist') {
            echo "Zoek";
        }
    }
}
if ($user->hasPermission('admin')) {
//                if (Session::exists('home')) {
//                    echo "<div class='succes'><p>" . Session::flash('home') . "</p></div>";
//                }
//                echo "<h1>Autorisatie</h1>";
//                echo "<h1>Systeem Instellingen</h1>";
    if (Input::get('page') === "new") {
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
}
?>