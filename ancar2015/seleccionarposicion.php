<?php require_once('Connections/conexion1.php'); ?>
<?php require_once('funcionespresentacion.php'); ?>
<?php $conexion1=conectarBBDD(); ?>
<?php
$insertOK=false;
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
$query = "SELECT * FROM jugador WHERE usuario='".$_SESSION['MM_Username']."'";
$reg = mysql_query($query, $conexion1) or die(mysql_error());
$row_reg = mysql_fetch_assoc($reg);
$totalRows_reg = mysql_num_rows($reg);
$idjugador=$row_reg['jugador_id'];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    $conexion1=conectarBBDD();
    
    $insertSQL1 = sprintf("INSERT INTO demarcacion_jugador_relacion (iddemarcacion, idjugador) VALUES (%s, %s)",GetSQLValueString($_POST['tseleccion1'], "int"),GetSQLValueString($idjugador, "int"));
    $Result1 = mysql_query($insertSQL1, $conexion1) or die(mysql_error());

    $insertSQL2 = sprintf("INSERT INTO demarcacion_jugador_relacion (iddemarcacion, idjugador) VALUES (%s, %s)",GetSQLValueString($_POST['tseleccion2'], "int"),GetSQLValueString($idjugador, "int"));
    $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error());
    
    $insertSQL3 = sprintf("INSERT INTO demarcacion_jugador_relacion (iddemarcacion, idjugador) VALUES (%s, %s)",GetSQLValueString($_POST['tseleccion3'], "int"),GetSQLValueString($idjugador, "int"));
    $Result3 = mysql_query($insertSQL3, $conexion1) or die(mysql_error());
    
    $insertOK=true;
    /*$insertGoTo = "index.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $insertGoTo));*/
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
    $conexion1=conectarBBDD();
    
    $insertSQL1 = sprintf("INSERT INTO relacion_jugador_encuesta (iddemarcacion, idjugador2) VALUES (%s, 1)",GetSQLValueString($_POST['tseleccion4'], "int"));
    $Result1 = mysql_query($insertSQL1, $conexion1) or die(mysql_error());

    $insertSQL2 = sprintf("INSERT INTO relacion_jugador_encuesta (iddemarcacion, idjugador2) VALUES (%s, 2)",GetSQLValueString($_POST['tseleccion5'], "int"));
    $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error());
    
    $insertSQL3 = sprintf("INSERT INTO relacion_jugador_encuesta (iddemarcacion, idjugador2) VALUES (%s, 3)",GetSQLValueString($_POST['tseleccion6'], "int"));
    $Result3 = mysql_query($insertSQL3, $conexion1) or die(mysql_error());
    
    $insertSQL4 = sprintf("INSERT INTO relacion_jugador_encuesta (iddemarcacion, idjugador2) VALUES (%s, 4)",GetSQLValueString($_POST['tseleccion7'], "int"));
    $Result3 = mysql_query($insertSQL4, $conexion1) or die(mysql_error());

    $insertGoTo = "index.php?posicion=1";
    /*if (isset($_SERVER['QUERY_STRING'])) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= $_SERVER['QUERY_STRING'];
    }*/
    header(sprintf("Location: %s", $insertGoTo));
}

?>
<?php

aGetHeader("Seleccionar Posicion","seleccionarposicion",FALSE);

if (isset($_GET['encuesta'])){
    $jugadores_reg=getJugadoresEncuesta();
    aGetEncuesta($jugadores_reg);
}
if ($insertOK==1){
    aGetMensajeError("DATO CORRECTO", "Gracias, ahora ponte en forma, que esto empieza...");
    aGetSeleccionarPosicionEncuesta();
}
if ($insertOK==0){
    $resultado=tienePosicion($idjugador);
    
    if (tienePosicion($idjugador)){
        if (isset($_GET['modificar'])){
            aGetSeleccionarPosicion();
        }
        else{
            aGetMensajeError("ERROR!!!", "Ya tienes Posicion Asignada, pero la puedes modificar...", "Modificar", "index.php");
        }
    }
    else{
        aGetSeleccionarPosicion();
        
    }
}
$jugadores_reg=getJugadoresDemarcaciones();
aGetPosicionesAsignadas($jugadores_reg);
aGetFooter();
$insertOK=0;
mysql_free_result($jugadores_reg);
?>
