<?php
///////////////////
//Cas van Dinter///
///////384755//////
///////////////////
if ($user->hasPermission('admin') || $user->hasPermission('werknemer')) {
    //code
    echo "<h1>Autorisatie</h1>";
    echo "<a class='buttonLink' href='index.php?page=autorisation_user'>Gebruiker</a><br/><br/>";
    echo "<a class='buttonLink' href='index.php?page=autorisation_company'>Bedrijf</a>";
} else {
    Redirect::to('index.php');
}
?>