<?php require_once('Connections/conexion1.php'); ?>
<?php require_once('funcionespresentacion.php'); ?>
<?php $conexion1=conectarBBDD(); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
    if (PHP_VERSION < 6) {
        $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    }

    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
    switch ($theType) {
        case "text":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;    
        case "long":
        case "int":
            $theValue = ($theValue != "") ? intval($theValue) : "NULL";
        break;
        case "double":
            $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
        break;
        case "date":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;
        case "defined":
            $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
        break;
    }
  return $theValue;
}
}
?>
<?php
if (!isset($_SESSION)) {
    session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
    $isValid = False; 

    if (!empty($UserName)) { 
        $arrUsers = Explode(",", $strUsers); 
        $arrGroups = Explode(",", $strGroups); 
        if (in_array($UserName, $arrUsers)) { 
            $isValid = true; 
        } 
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
mysql_select_db($database_conexion1, $conexion1);
$query = "SELECT * FROM jugador WHERE usuario='".$_SESSION['MM_Username']."'";
$reg = mysql_query($query, $conexion1) or die(mysql_error());
$row_reg = mysql_fetch_assoc($reg);
$totalRows_reg = mysql_num_rows($reg);
$idjugador=$row_reg['jugador_id'];
$idpartido=$_GET['idpartido'];
$partido=getInfoPartido($idpartido);
$restjugadoresvotar=getFaltanJugvotar($idpartido);

if($restjugadoresvotar==0){
    $votacionabierta=0;
}
else{
    $votacionabierta=1;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    
    $from = "no-reply@ancar2015.com";
    $to = "jlotito@opensistemas.com, lotybaink@hotmail.com";
    $headers = "From:" . $from;
    $subject="Votacion ".$_SESSION['MM_Username']." Jornada ".$idpartido;
    $message  = 'Votaciones' . "\r\n";
    
    foreach ($_POST as $key => $value) {
        
        if (strstr($key, 'tselec')!=FALSE){
            $rest = substr($key, 6);
            $updateSQL = sprintf("INSERT INTO votaciones (idcompeticion, idpartido, idvotante, idvotado, votacion) VALUES (1, %s, %s, %s, %s)",
                GetSQLValueString($idpartido, "int"),
                GetSQLValueString($idjugador, "int"),
                GetSQLValueString($rest, "int"),
                GetSQLValueString($value, "int"));
            mysql_select_db($database_conexion1, $conexion1);
            $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
            
            $updateSQL = sprintf("UPDATE relacion_jug_partido SET valoracion=(SELECT avg(votacion) FROM votaciones WHERE idcompeticion=1 AND idpartido=%s AND idvotado=%s) WHERE idjugador=%s AND idpartido=%s",
                GetSQLValueString($idpartido, "int"),
                GetSQLValueString($rest, "int"),
                GetSQLValueString($rest, "int"),
                GetSQLValueString($idpartido, "int"));
            mysql_select_db($database_conexion1, $conexion1);
            $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
            
            $message .= 'idjugador='.$rest.' votacion='.$value."\r\n";
        }
    }
    
    $updateSQL = sprintf("UPDATE relacion_jug_partido SET yavotado=1 WHERE idjugador=%s AND idpartido=%s",
            GetSQLValueString($idjugador, "int"),
            GetSQLValueString($idpartido, "int"));
    mysql_select_db($database_conexion1, $conexion1);
    $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
    
    enviarCorreo($to,$subject, $message, $headers);
    $restjugadoresvotar=getFaltanJugvotar($idpartido);
    $updateGoTo = "index.php?votacion=1&quedanjug=".$restjugadoresvotar;
    if (isset($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        $updateGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $updateGoTo));
}
?>
<?php
aGetHeader("Votacion","votacion",FALSE);
if ($votacionabierta){
    if (jugConvocado($idjugador,$idpartido)){
        if (yaVotado($idjugador,$idpartido)){
            aGetMensajeError("ERROR!!!", "Ya has votado para este partido... Quedan por Votar ".$restjugadoresvotar." juagdores", "Volver", "index.php");
            aGetFichaDelPartido($partido,true);
        }
        else{
            aGetVotacionesDelPartido($idjugador,$partido);
            aGetFichaDelPartido($partido,false);
        }
    }
    else{
        aGetMensajeError("ERROR!!!", "No Puedes Votar en este partido al no haber estado convocado para el mismo", "Volver", "index.php");
        aGetFichaDelPartido($partido);
    }
}
else{
    $idjugadormvp=getMVP($idpartido);
    aGetDatosPartido($partido,$idjugadormvp);
}
aGetFooter();
?>

