<?php
///////////////////
//Cas van Dinter///
///////384755//////
///////////////////
function escape($string){
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}