<?php
/*
 * Utility functions
 */

function checkEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);;
}
?>
