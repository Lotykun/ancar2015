<?php require_once('db_config.php'); ?>
<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
error_reporting(E_ALL ^ E_DEPRECATED);
function comprobarDorsal($theValue) {    
    $conexion1=conectarBBDD();
    
    $query = sprintf("SELECT * FROM jugador WHERE dorsal=%s",GetSQLValueString($theValue, "int"));
    $reg = mysql_query($query, $conexion1);
    $row_reg = mysql_fetch_assoc($reg);
    if ($row_reg){
        $resultado=FALSE;
    }
    else{
        $resultado=TRUE;
    }
    return $resultado;
}
function tieneDorsal($id_jugador) {
    $conexion1=conectarBBDD();
    
    $query = 'SELECT * FROM jugador WHERE jugador_id='.$id_jugador;
    $reg = mysql_query($query, $conexion1);
    $row_reg = mysql_fetch_assoc($reg);
    if ($row_reg['dorsal']!=NULL){
        $resultado=TRUE;
    }
    else{
        $resultado=FALSE;
    }
    return $resultado;
}
function tienePosicion($id_jugador) {
    $conexion1=conectarBBDD();
    
    $query = 'SELECT * FROM demarcacion_jugador_relacion WHERE idjugador='.$id_jugador;
    $reg = mysql_query($query, $conexion1);
    $row_reg = mysql_fetch_assoc($reg);
    if ($row_reg['idrelacion']!=NULL){
        $resultado=TRUE;
    }
    else{
        $resultado=FALSE;
    }
    return $resultado;
}
function tieneDisponibilidad($idjugador,$idpartido) {
    $conexion1=conectarBBDD();
    
    $query = 'SELECT * FROM relacion_jug_disponibilidad WHERE idjugador='.$idjugador.' AND idpartido='.$idpartido;
    $reg = mysql_query($query, $conexion1);
    $row_reg = mysql_fetch_assoc($reg);
    if ($row_reg['disponible']!=NULL){
        $resultado=TRUE;
    }
    else{
        $resultado=FALSE;
    }
    return $resultado;
}
function getAllPositions(){
    
    $conexion1=conectarBBDD();
    $query = 'SELECT * FROM demarcaciones LIMIT 21';
    $reg = mysql_query($query, $conexion1);
    return $reg;
}
function getJugadoresDemarcaciones(){
    $conexion1=conectarBBDD();

    $query_jugadores_reg = "SELECT jugador_id,nombre,apellidos,dorsal,demarcacion,iconodemarc,ejemplo FROM jugador,demarcacion_jugador_relacion,demarcaciones WHERE jugador.jugador_id=demarcacion_jugador_relacion.idjugador AND demarcaciones.iddemarcacion=demarcacion_jugador_relacion.iddemarcacion ORDER BY dorsal ASC, idrelacion";
    $jugadores_reg = mysql_query($query_jugadores_reg, $conexion1) or die(mysql_error());
    
    return $jugadores_reg;
}
function getArrayEncuesta(){
    $conexion1=conectarBBDD();
    
    $query = "SELECT relacion_jugador_encuesta.iddemarcacion,relacion_jugador_encuesta.idjugador2,jugadores_encuesta.nombre,jugadores_encuesta.apellidos,demarcaciones.demarcacion,demarcaciones.iconodemarc FROM relacion_jugador_encuesta,demarcaciones,jugadores_encuesta WHERE relacion_jugador_encuesta.iddemarcacion=demarcaciones.iddemarcacion AND relacion_jugador_encuesta.idjugador2=jugadores_encuesta.idjugador ORDER BY idjugador2, relacion_jugador_encuesta.iddemarcacion";
    $reg = mysql_query($query, $conexion1);
    $row_reg = mysql_fetch_assoc($reg);
    $votaciones=array();
    $jugador=array();
    $valor=1;
    do{
        $idjugador_act=$row_reg['idjugador2'];
        $iddemarcacion_act=$row_reg['iddemarcacion'];
        
        if ($idjugador_act==$idjugador_pre){
            if ($iddemarcacion_act==$iddemarcacion_pre){
                $valor++;
            }
            else{
                $votaciones[$iddemarcacion_pre]=$valor;
                $valor=1;
            }
        }
        else{
            if (isset($idjugador_pre)){
                $votaciones[$iddemarcacion_pre]=$valor;
                $valor=1;
                arsort($votaciones);
                $jugador[$idjugador_pre]=$votaciones;
                $votaciones=array();
            }
        }
        $idjugador_pre=$idjugador_act;
        $iddemarcacion_pre=$iddemarcacion_act;
        
    }while ($row_reg = mysql_fetch_assoc($reg));
    $votaciones[$iddemarcacion_pre]=$valor;
    arsort($votaciones);
    $jugador[$idjugador_pre]=$votaciones;
    return $jugador;
}
function getNextPartido($idcompeticion){
        
    $conexion1=conectarBBDD();

    $query_reg = "SELECT * FROM partido,campos WHERE partido.idcompeticion=".$idcompeticion." AND partido.fecha > now() AND partido.idcampo=campos.idcampo ORDER by partido.fecha limit 1";
    $reg = mysql_query($query_reg, $conexion1) or die(mysql_error());
    $row_reg = mysql_fetch_assoc($reg);
    return $row_reg;
}
function calcula_tiempo($start_time, $end_time) { 
    $total_seconds = strtotime($end_time) - strtotime($start_time); 
    
    /*$dias = floor ( $total_seconds / (3600*24) );
    $horas = (( $total_seconds / 3600 )% 3600);
    $minutes = ( ( $total_seconds / 60 ) % 60 );
    $seconds = ( $total_seconds % 60 );*/
    $dias = floor ($total_seconds / 86400);
    $horas = (($total_seconds/3600)%24);
    $minutes = (($total_seconds/60)%60);
    $seconds = ($total_seconds%60);
     
    $time['dias'] = str_pad( $dias, 2, "0", STR_PAD_LEFT );
    $time['horas'] = str_pad( $horas, 2, "0", STR_PAD_LEFT );
    $time['minutes'] = str_pad( $minutes, 2, "0", STR_PAD_LEFT );
    $time['seconds'] = str_pad( $seconds, 2, "0", STR_PAD_LEFT );
     
    //$time               = implode( ':', $time );
     
    return $time;
}
function getConvocadosNextPartido($idpartido){
    $conexion1=conectarBBDD();
    
    $query = "SELECT relacion_jugador_encuesta.iddemarcacion,relacion_jugador_encuesta.idjugador2,jugadores_encuesta.nombre,jugadores_encuesta.apellidos,demarcaciones.demarcacion,demarcaciones.iconodemarc FROM relacion_jugador_encuesta,demarcaciones,jugadores_encuesta WHERE relacion_jugador_encuesta.iddemarcacion=demarcaciones.iddemarcacion AND relacion_jugador_encuesta.idjugador2=jugadores_encuesta.idjugador ORDER BY idjugador2, relacion_jugador_encuesta.iddemarcacion";
    $reg = mysql_query($query, $conexion1);
    $row_reg = mysql_fetch_assoc($reg);
}
function getVotacionActual($idpartido,$idjugador){
    $conexion1=conectarBBDD();
    
    $query = sprintf("SELECT valoracion FROM relacion_jug_partido WHERE idjugador=%s AND idpartido=%s",
            GetSQLValueString($idjugador, "int"),
            GetSQLValueString($idpartido, "int"));
    
    $reg = mysql_query($query, $conexion1);
    $row_reg = mysql_fetch_assoc($reg);
    
    return $row_reg['valoracion'];
}
function getInfoPartido($idpartido){
    $conexion1=conectarBBDD();
    
    $query = sprintf("SELECT * FROM partido as p, campos as c WHERE p.idpartido=%s AND p.idcampo=c.idcampo",
            GetSQLValueString($idpartido, "int"));
    
    $reg = mysql_query($query, $conexion1);
    $row_reg = mysql_fetch_assoc($reg);
    
    return $row_reg;
}
function getConvocadosPartido($idpartido){
    $conexion1=conectarBBDD();
    
    $query = sprintf("SELECT p.idjugador,j.nombre,j.apellidos,j.dorsal,p.goles,p.asistencias,p.minutos,p.TA,p.TR,p.valoracion,p.yavotado,p.esportero FROM relacion_jug_partido as p, jugador as j WHERE p.idjugador=j.jugador_id AND p.idpartido=%s AND p.minutos IS NOT NULL AND p.minutos > 0 ORDER by dorsal",
            GetSQLValueString($idpartido, "int"));
    
    $reg = mysql_query($query, $conexion1);
    /*$row_reg = mysql_fetch_assoc($reg);*/
    
    return $reg;
}
function getTitularesPartido($idpartido){
    $conexion1=conectarBBDD();
    
    $query = sprintf("SELECT j.jugador_id,j.nombre,j.apellidos,j.dorsal,p.goles,p.asistencias,p.minutos,p.TA,p.TR,p.valoracion,p.yavotado,p.esportero FROM relacion_jug_partido as p, jugador as j WHERE p.titular=1 AND p.idjugador=j.jugador_id AND p.idpartido=%s AND p.minutos IS NOT NULL AND p.minutos > 0 ORDER by dorsal",
            GetSQLValueString($idpartido, "int"));
    
    $reg = mysql_query($query, $conexion1);
    /*$row_reg = mysql_fetch_assoc($reg);*/
    
    return $reg;
}
function getSuplentesPartido($idpartido){
    $conexion1=conectarBBDD();
    
    $query = sprintf("SELECT j.jugador_id,j.nombre,j.apellidos,j.dorsal,p.goles,p.asistencias,p.minutos,p.TA,p.TR,p.valoracion,p.yavotado,p.esportero FROM relacion_jug_partido as p, jugador as j WHERE p.titular=0 AND p.idjugador=j.jugador_id AND p.idpartido=%s AND p.minutos IS NOT NULL AND p.minutos > 0 ORDER by dorsal",
            GetSQLValueString($idpartido, "int"));
    
    $reg = mysql_query($query, $conexion1);
    /*$row_reg = mysql_fetch_assoc($reg);*/
    
    return $reg;
}
function yaVotado($idjugador,$idpartido){
    $conexion1=conectarBBDD();
    
    $query = sprintf("SELECT yavotado FROM relacion_jug_partido WHERE idpartido=%s AND idjugador=%s",
            GetSQLValueString($idpartido, "int"),
            GetSQLValueString($idjugador, "int"));
    
    $reg = mysql_query($query, $conexion1);
    $row_reg = mysql_fetch_assoc($reg);
    
    if ($row_reg['yavotado']==1){
        $resultado=TRUE;
    }
    else{
        $resultado=FALSE;
    }
    return $resultado;
}
function jugConvocado($idjugador,$idpartido){
    $conexion1=conectarBBDD();
    
    $query = sprintf("SELECT * FROM relacion_jug_partido WHERE idpartido=%s AND idjugador=%s",
            GetSQLValueString($idpartido, "int"),
            GetSQLValueString($idjugador, "int"));
    
    $reg = mysql_query($query, $conexion1);
    $row_reg = mysql_fetch_assoc($reg);
    $totalRows = mysql_num_rows($reg);
    
    if ($totalRows>0){
        $resultado=TRUE;
    }
    else{
        $resultado=FALSE;
    }
    
    return $resultado;
}
function actualizarVotaciones($idpartido,$idjugador){
    $conexion1=conectarBBDD();
    
    $query = sprintf("UPDATE relacion_jug_partido SET valoracion=(SELECT avg(votacion) FROM votaciones WHERE idcompeticion=1 AND idpartido=%s AND idvotado=%s) WHERE idjugador=%s AND idpartido=%s",
            GetSQLValueString($idpartido, "int"),
            GetSQLValueString($idjugador, "int"),
            GetSQLValueString($idpartido, "int"),
            GetSQLValueString($idjugador, "int"));
    
    $reg = mysql_query($query, $conexion1);
    $row_reg = mysql_fetch_assoc($reg);
}
function getFaltanJugvotar($idpartido){
    $conexion1=conectarBBDD();
    
    $query = sprintf("SELECT * FROM relacion_jug_partido WHERE idpartido=%s and yavotado=0",
            GetSQLValueString($idpartido, "int"));
    
    $reg = mysql_query($query, $conexion1);
    /*$row_reg = mysql_fetch_assoc($reg);*/
    $resultado = mysql_num_rows($reg);
    
    return $resultado;
}
function enviarCorreo($subject,$message,$headers){
        
    $to = "jlotito@opensistemas.com, lotybaink@hotmail.com";
    /*$subject = "No es ninguna Broma, LOTY Te vigila";
    $message = "Te Dije que no era una broma";
    $from = "no-reply@ancar2015.com";
    $headers = "From:" . $from;*/

    mail($to,$subject,$message,$headers);
}
function getMVP($idpartido){
    $conexion1=conectarBBDD();
    
    $query = sprintf("SELECT * FROM relacion_jug_partido WHERE idpartido=%s order by valoracion desc",
            GetSQLValueString($idpartido, "int"));
    $reg = mysql_query($query, $conexion1);
    $row_reg = mysql_fetch_assoc($reg);
    $resultado = $row_reg['idjugador'];
    
    return $resultado;
}
?>
