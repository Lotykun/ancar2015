<?php require_once('Connections/conexion1.php'); ?>
<?php require_once('funcionespresentacion.php'); ?>
<?php $conexion1=conectarBBDD(); ?>
<?php
$idpartido=  getNextPartido(1);

$listaConvocados=getConvocadosNextPartido($idpartido);
insertarConvocados($idpartido,$listaConvocados);
enviarCorreoDisponibles($idpartido,$listaConvocados);
?>

