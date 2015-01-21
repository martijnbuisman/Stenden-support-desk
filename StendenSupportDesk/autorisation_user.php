<?php
if ($user->hasPermission('admin')) {
    //code
    echo "<h1>Autorisatie -> Gebruiker</h1>";
    echo "<a class='buttonLink' href='index.php?page=autorisation_user_new'>Nieuw</a><br/><br/>";
    echo "<a class='buttonLink' href='index.php?page=autorisation_user_exist'>Bestaand</a>";
} else {
    Redirect::to('index.php');
}
?>