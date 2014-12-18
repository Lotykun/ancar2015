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
$partido=  getNextPartido(1);
$idpartido=$partido['idpartido'];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    
    $from = "no-reply@ancar2015.com";
    $headers = "From:" . $from;
    $subject= "Disponibilidad ".$_SESSION['MM_Username']." Jornada ".$idpartido;
    
    if (isset($_POST['tbutton1'])){
        $button=1;
        $message  = 'DISPONIBLE' . "\r\n";
    }
    else{
        $button=0;
        $message  = 'NO DISPONIBLE' . "\r\n";
    }
    $updateSQL = sprintf("UPDATE relacion_jug_disponibilidad SET disponible=%s WHERE idjugador=%s AND idpartido=%s",
        GetSQLValueString($button, "int"),
        GetSQLValueString($idjugador, "int"),
        GetSQLValueString($partido['idpartido'], "int"));
    mysql_select_db($database_conexion1, $conexion1);
    $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());


    $disponibilidadResto = getDisponibilidadOtrosJugadores($idpartido, $idjugador);
    $row_reg = mysql_fetch_assoc($disponibilidadResto);
    $message .= "DISPONIBILIDAD RESTO JUGADORES HASTA EL MOMENTO:" . "\r\n";
    do{
        /*$message .= $jugador['nombre'].' '.$jugador['apellidos'].', Dorsal: '.$jugador['dorsal'].', Disponible: '.$jugador['disponible']."\r\n";*/
        if (isset($row_reg['disponible'])){
            if ($row_reg['disponible']==1){
                $disponible="DISPONIBLE";
            }
            else{
                $disponible="NO DISPONIBLE";
            }
        }
        else{
            $disponible="NULL";
        }
        $message .= $row_reg['dorsal'].' '.$row_reg['nombre'].' '.$row_reg['apellidos'].', '.$disponible."\r\n";
    
    } while ($row_reg = mysql_fetch_assoc($disponibilidadResto));
    
    enviarCorreo($subject, $message, $headers);
    
    $updateGoTo = "index.php?disponible=1";
    if (isset($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        $updateGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $updateGoTo));
}
?>
<?php

aGetHeader("Disponibilidad","disponibilidad",FALSE);

if (tieneDisponibilidad($idjugador,$idpartido)){
    if (isset($_GET['modificarDisponibilidad'])){
        aGetSeleccionarDisponibilidad($partido);
    }
    else{
        aGetMensajeError("ERROR!!!", "Ya has confirmado la disponibilidad para este partido, pero la puedes modificar...", "Modificar", "disponibilidad.php?modificarDisponibilidad");
    }
}
else{
    aGetSeleccionarDisponibilidad($partido);
}
aGetFooter();
$nuevafecha = strtotime ('-2 day',strtotime ($partido['fecha']));
$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
aGetScriptCountdown($nuevafecha,"15:00:00");
/*aGetScriptCountdown("2014-10-24","15:00:00");*/

mysql_free_result($jugadores_reg);
?>

