<?php
if ($user->hasPermission('admin')) {
    //code
    echo "<h1>Autorisatie -> Gebruiker -> Nieuw</h1>";
    echo "<a class='buttonLink' href='index.php?page=autorisation_user_new_admin'>Admin</a><br/><br/>";
    echo "<a class='buttonLink' href='index.php?page=autorisation_user_new_employee'>Werknemer</a><br/><br/>";
    echo "<a class='buttonLink' href='index.php?page=autorisation_user_new_customer'>Klant</a>";
} else {
    Redirect::to('index.php');
}
?>