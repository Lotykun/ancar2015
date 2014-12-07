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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    
    if (comprobarDorsal($_POST['tdorsal'])){
        $updateSQL = sprintf("UPDATE jugador SET dorsal=%s WHERE jugador_id=%s",
            GetSQLValueString($_POST['tdorsal'], "int"),
            GetSQLValueString($idjugador, "int"));
        mysql_select_db($database_conexion1, $conexion1);
        $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
        
        $updateGoTo = "index.php?insertado=1";
        if (isset($_SERVER['QUERY_STRING'])) {
            $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
            $updateGoTo .= $_SERVER['QUERY_STRING'];
        }
        header(sprintf("Location: %s", $updateGoTo));
    }
    else{
        $dorsalpiyao=true;
    }
}
?>
<?php
$query_jugadores_reg = "SELECT * FROM jugador WHERE dorsal IS NOT NULL ORDER BY dorsal ASC";
$jugadores_reg = mysql_query($query_jugadores_reg, $conexion1) or die(mysql_error());

aGetHeader("Seleccionar Dorsal","seleccionardorsal",FALSE);

if (tieneDorsal($idjugador)){
    aGetMensajeError("ERROR!!!", "Ya tienes Dorsal Asignado...", "Volver", "index.php");
    $dorsalesocupados=aGetDorsalesAsignados($jugadores_reg);
}
else{
    if ($dorsalpiyao){
        aGetMensajeError("ERROR!!!", "Lo Siento, mientras te lo pensabas, otro usuario cogio este dorsal.");
    }
    $dorsalesocupados=aGetDorsalesAsignados($jugadores_reg);
    aGetSeleccionarDorsal($dorsalesocupados);
}
aGetFooter();

mysql_free_result($jugadores_reg);
?>
