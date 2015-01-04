<?php require_once('Connections/conexion1.php'); ?>
<?php require_once('funcionespresentacion.php'); ?>
<?php $conexion1=conectarBBDD(); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
    session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
    $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
    //to fully log out a visitor we need to clear the session varialbles
    $_SESSION['MM_Username'] = NULL;
    $_SESSION['MM_UserGroup'] = NULL;
    $_SESSION['PrevUrl'] = NULL;
    unset($_SESSION['MM_Username']);
    unset($_SESSION['MM_UserGroup']);
    unset($_SESSION['PrevUrl']);

    $logoutGoTo = "login.php";
    if ($logoutGoTo) {
        header("Location: $logoutGoTo");
        exit;
    }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "error.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
    $MM_qsChar = "?";
    $MM_referrer = $_SERVER['PHP_SELF'];
    if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
    if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
    $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
    $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
    header("Location: ". $MM_restrictGoTo); 
    exit;
}
?>
<?php
$partido=  getNextPartido(1);
aGetHeader("Index", "index",TRUE);
/*if (isset($_GET['insertado'])){
    aGetMensajeError("Dorsal Insertado!!!", "Ya tienes tu dorsal, ahora a comprar la camiseta");
}*/
if (isset($_GET['votacion'])){
    aGetMensajeError("Votacion Efectuada!!!", "Gracias. Quedan Todavia por Votar ".$_GET['quedanjug']." jugadores. se notificará el MVP del partido una vez acabada la votacion");
}
if (isset($_GET['disponible'])){
    aGetMensajeError("Disponibilidad Confirmada!!!", "Si estas Disponible, ahora a esperar para entrar en la convocatoria. Si no estás Disponible...Eres un marica");
}
if (isset($_GET['posicion'])){
    aGetMensajeError("Posicion Insertada!!!", "Gracias por Participar también en la encuesta. Atento al Whatsapp para proximas noticias");
}
/*if ($_SESSION['MM_Username']=="jlotito")
    $admin=TRUE;
else
    $admin=FALSE;*/
aGetMain();
aGetFooter();
/*$nuevafecha = strtotime ('-2 day',strtotime ($partido['fecha']));
$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
aGetScriptCountdown($nuevafecha,"15:00:00");*/
aGetScriptanimacioncolor();
?>
       	