<?php
///////////////////
//Cas van Dinter///
///////384755//////
///////////////////
if ($user->hasPermission('admin') || $user->hasPermission('werknemer')) {
    //code
    if (Input::get('delete')) {
        $id = Input::get('delete');
        if ($id !== '1' && $id !== '2') {
            $existCompany = $db->query("SELECT * FROM company WHERE id = '{$id}'");
            if ($existCompany->count()) {
                $db->query("DELETE FROM company WHERE id = '{$id}'");
                Session::flash('home', 'Company succesfully removed.');
                Redirect::to('index.php?page=autorisation_company_exist');
            } else {
                Session::flash('error', 'That Company ID does not exist.');
                Redirect::to('index.php?page=autorisation_company_exist');
            }
        } else {
            Session::flash('error', 'You can not delete StendenSupportDesk jackass');
            Redirect::to('index.php?page=autorisation_company_exist');
        }
    }

    echo "<h1>Autorisatie -> Bedrijf -> Bestaand</h1>";
    ?>

    <form action="" method="post" class="basic-grey">
        <h1>Zoeken 
            <span>Vul de naam in van het bedrijf.</span>
        </h1>
        <label>
            <span>Bedrijfsnaam :</span>
            <input id="name" type="text" name="name" placeholder="Company name" value="<?php echo escape(Input::get('name')); ?>"/>
        </label>

        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"/>
        <label>
            <span>&nbsp;</span> 
            <input type="submit" class="button" value="Zoeken" /> 
        </label>    
    </form>
    <?php
    if (Input::exists()) {

        $name = Input::get('name');
        $companys = $db->query("SELECT * FROM company WHERE name LIKE '%{$name}%'");
        if ($companys->count()) {
            ?>
            <h1>Resultaten:</h1>
            <table class="contentTable">
                <tr>
                    <th width="20%">
                        Bedrijfsnaam
                    </th>
                    <th width="30%">
                        Adresgegevens
                    </th>
                    <th width="20%">
                        Telefoon
                    </th>
                    <th width="10%">
                        Email
                    </th>
                    <th width="20%">
                        Onderhouds Licentie
                    </th>
                    <th width="20%">
                        Actie
                    </th>
                </tr>


                <?php
                foreach ($companys->results() as $company) {
                    if ($company->group_id > 2) {
                        $company->group_id === '3' ? $ol = 'Ja' : $ol = 'Nee';
                        echo "<tr>";
                        echo "<td><b>{$company->name}</b></td>";
                        echo "<td>{$company->adres}</td>";
                        echo "<td>{$company->phone}</td>";
                        echo "<td>{$company->email}</td>";
                        echo "<td>{$ol}</td>";
                        echo "<td><a class='ButtonUpdate' href='index.php?page=autorisation_company_update&update={$company->id}'>Update</a><a class='ButtonDelete' href='index.php?page=autorisation_company_exist&delete={$company->id}'>Verwijderen</a></td>";
                        echo "</tr>";
                    }
                }
            } else {
                echo "<h2>Geen bedrijven gevonden</h2>";
            }
        }
    } else {
        Redirect::to('index.php');
    }
    ?>