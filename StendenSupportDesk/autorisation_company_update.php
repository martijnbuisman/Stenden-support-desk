<?php
///////////////////
//Cas van Dinter///
///////384755//////
///////////////////
if ($user->hasPermission('admin') || $user->hasPermission('werknemer')) {
//code
    if (Input::get('update')) {
        $id = Input::get('update');
        $company = $db->query("SELECT * FROM company WHERE id = '{$id}'");
        if ($company->count()) {
            echo "<h1>Bedrijf updaten</h1>";
            if (Input::exists()) {
                if (Token::check(Input::get('token'))) {
                    if (Input::get('selection') === 'yes') {
                        $group = 3;
                    } else {
                        $group = 4;
                    }
                    $validate = new Validate();
                    $validation = $validate->check($_POST, array(
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
                            $db->update('company', $id, array(
                                'adres' => Input::get('adres'),
                                'phone' => Input::get('phone'),
                                'email' => Input::get('email'),
                                'group_id' => $group
                            ));

                            Session::flash('home', 'Company succesfully updated!');
                            Redirect::to('index.php?page=autorisation_company_exist');
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
                <h1>Bedrijf updaten: <?php echo escape($company->first()->name); ?>
                    <span>Alle velden invullen</span>
                </h1>
                <label>
                    <span>Adresgegevens :</span>
                    <input id="adres" type="text" name="adres" placeholder="Voorbeeldstraat 69, 1337 LT Emmen" value="<?php echo escape($company->first()->adres); ?>"/>
                </label>

                <label>
                    <span>Telefoon :</span>
                    <input id="phone" type="text" name="phone" placeholder="0591486267" value="<?php echo escape($company->first()->phone); ?>"/>
                </label>

                <label>
                    <span>Your Email :</span>
                    <input id="email" type="email" name="email" placeholder="random@email.com" value="<?php echo escape($company->first()->email); ?>"/>
                </label>

                <label>
                    <span>Onderhouds Licentie :</span>
                    <select name="selection">
                        <option value="no">Nee</option>
                        <option value="yes" <?php if($company->first()->group_id === '3'){echo "selected='selected'";} ?>>Ja</option>
                    </select>
                </label>
                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"/>
                <label>
                    <span>&nbsp;</span> 
                    <input type="submit" class="button" value="Update" /> 
                </label>    
            </form>
            <?php
        } else {
            Session::flash('error', 'That company ID does not exist.');
            Redirect::to('index.php?page=autorisation_company_exist');
        }
    } else {
        Session::flash('error', 'That company ID does not exist.');
        Redirect::to('index.php?page=autorisation_company_exist');
    }
} else {
    Redirect::to('index.php');
}
?>