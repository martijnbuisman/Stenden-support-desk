<!--
Cas van Dinter
384755
-->
<?php

require_once 'core/init.php';

$user = new User();
$user->logout();

Redirect::to('index.php');