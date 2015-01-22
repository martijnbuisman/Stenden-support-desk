<?php
///////////////////
//Cas van Dinter///
///////384755//////
///////////////////
if ($user->hasPermission('admin') || $user->hasPermission('werknemer')) {
    //code
    if (Input::exists()) {
        if (Token::check(Input::get('token'))) {
            if (Input::get('selection') === 'yes') {
                $group = 3;
            } else {
                $group = 4;
            }
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'name' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 50,
                    'unique' => 'company'
                ),
                'adres' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 40
                ),
                'phone' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 16
                ),
                'email' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 30
                ),
                'selection' => array(
                    'required' => true
                )
            ));

            if ($validation->passed()) {
                try {
                    $user->create('company', array(
                        'name' => Input::get('name'),
                        'adres' => Input::get('adres'),
                        'phone' => Input::get('phone'),
                        'email' => Input::get('email'),
                        'group_id' => $group
                    ));

                    Session::flash('home', 'Company has been created!');
                    Redirect::to('index.php?page=autorisation_company_new');
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
    echo "<h1>Autorisatie -> Bedrijf -> Nieuw</h1>";
    ?>
    <form action="" method="post" class="basic-grey">
        <h1>Nieuw bedrijf toevoegen 
            <span>Alle velden invullen</span>
        </h1>
        <label>
            <span>Bedrijfsnaam :</span>
            <input id="name" type="text" name="name" placeholder="Company name" value="<?php echo escape(Input::get('name')); ?>"/>
        </label>

        <label>
            <span>Adresgegevens :</span>
            <input id="adres" type="text" name="adres" placeholder="Voorbeeldstraat 69, 1337 LT Emmen" value="<?php echo escape(Input::get('adres')); ?>"/>
        </label>

        <label>
            <span>Telefoon :</span>
            <input id="phone" type="text" name="phone" placeholder="0591486267" value="<?php echo escape(Input::get('phone')); ?>"/>
        </label>

        <label>
            <span>Your Email :</span>
            <input id="email" type="email" name="email" placeholder="random@email.com" value="<?php echo escape(Input::get('email')); ?>"/>
        </label>

        <label>
            <span>Onderhouds Licentie :</span>
            <select name="selection">
                <option value="no">Nee</option>
                <option value="yes">Ja</option>
            </select>
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