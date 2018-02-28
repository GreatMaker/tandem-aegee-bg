<?php
/*
 * Utility functions
 * 
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

function checkEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);;
}

function fix_facebook_link(&$str)
{
    if (($pos = strrpos($str, '/')) != FALSE)
        $str = substr($str, $pos + 1);
    else if (($pos = strrpos($str, '=')) != FALSE)
        $str = substr($str, $pos + 1);
    else
    {
        return;
    }

    // recursive fix
    fix_facebook_link($str);
}

function requireToVar($file)
{
    ob_start();
    require($file);
    return ob_get_clean();
}

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++)
    {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
?>
