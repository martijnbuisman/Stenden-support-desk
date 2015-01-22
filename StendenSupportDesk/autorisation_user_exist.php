<?php
///////////////////
//Cas van Dinter///
///////384755//////
///////////////////
if ($user->hasPermission('admin') || $user->hasPermission('werknemer')) {
    //code
    echo "<h1>Autorisatie -> Gebruiker -> Bestaand</h1>";
    ?>
    <form action="" method="post" class="basic-grey">
        <h1>Zoeken 
            <span>Vul de naam in van de gebruiker.</span>
        </h1>
        <label>
            <span>Naam :</span>
            <input id="name" type="text" name="name" placeholder="Name" value="<?php echo escape(Input::get('name')); ?>"/>
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
        $usersDB = $db->query("SELECT * FROM users WHERE name LIKE '%{$name}%'");
        if ($usersDB->count()) {
            ?>
            <h1>Resultaten:</h1>
            <table class="contentTable">
                <tr>
                    <th width="20%">
                        Naam
                    </th>
                    <th width="20%">
                        Gebruikersnaam
                    </th>
                    <th width="20%">
                        Mail
                    </th>
                    <th width="30%">
                        Bedrijfsnaam
                    </th>
                    <th width="30%">
                        Functie
                    </th>
                    <th width="20%">
                        Actie
                    </th>
                </tr>


                <?php
                foreach ($usersDB->results() as $userDB) {
                    $companyName = $db->query("SELECT * FROM company WHERE id = '{$userDB->company_id}'");
                    if (!$companyName->count()) {
                        $companyName = "<font color='red'>Removed</font>";
                    } else {
                        $companyName = $companyName->first()->name;
                    }
                    echo "<tr>";
                    echo "<td><b>{$userDB->name}</b></td>";
                    echo "<td>{$userDB->username}</td>";
                    echo "<td>{$userDB->email}</td>";
                    echo "<td>{$companyName}</td>";
                    echo "<td>{$userDB->function}</td>";
                    if ($userDB->company_id !== '1' && $userDB->company_id !== '2' || $user->hasPermission('admin')) {
                        echo "<td><a class='ButtonUpdate' href='index.php?page=autorisation_user_update&update={$userDB->id}'>Update</a>"
                        . "<a class='ButtonDelete' href='index.php?page=autorisation_user_exist&delete={$userDB->id}'>Verwijderen</a></td>";
                    } else {
                        echo "<td></td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<h2>Geen gebruikers gevonden</h2>";
            }
        }
    } else {
        Redirect::to('index.php');
    }
    ?>