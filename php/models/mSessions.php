<?php
 
 function setSessionUser($u)
 {
    $_SESSION['userLogged'] = $u;
    $_SESSION['isLogged'] = true;
 }

function isLogged()
{
    return $_SESSION['isLogged'];
}

?>