<?php
///////////////////
//Cas van Dinter///
///////384755//////
///////////////////
if ($user->hasPermission('admin') || $user->hasPermission('werknemer')) {
    //code
    echo "<h1>Autorisatie -> Gebruiker -> Nieuw</h1>";
    if ($user->hasPermission('admin')) {
        echo "<a class='buttonLink' href='index.php?page=autorisation_user_new_admin'>Admin</a><br/><br/>";
        echo "<a class='buttonLink' href='index.php?page=autorisation_user_new_employee'>Werknemer</a><br/><br/>";
    }
    echo "<a class='buttonLink' href='index.php?page=autorisation_user_new_customer'>Klant</a>";
} else {
    Redirect::to('index.php');
}
?>