<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../page_class.php';

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// Page generator class
$page = new page_class();

// Reload false
$ret = array('reload' => 'false');

// Controllo parametro funzione
if (!isset($_POST['func']))
    $ret['error'] = _("Funzione non definita");

// Commutazione attivazione utente
if ($_POST['func'] == "tandem_logout")
{
	// logout
	$page->logout();

	// force reload
	$ret['reload'] = 'true';
}

$ret['func'] = $_POST['func'];

// Invio dati
echo json_encode($ret);
?>
