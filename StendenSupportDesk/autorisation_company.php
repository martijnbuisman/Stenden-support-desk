<?php
///////////////////
//Cas van Dinter///
///////384755//////
///////////////////
if ($user->hasPermission('admin') || $user->hasPermission('werknemer')) {
    //code
    echo "<h1>Autorisatie -> Bedrijf</h1>";
    echo "<a class='buttonLink' href='index.php?page=autorisation_company_new'>Nieuw</a><br/><br/>";
    echo "<a class='buttonLink' href='index.php?page=autorisation_company_exist'>Bestaand</a>";
} else {
    Redirect::to('index.php');
}
?>