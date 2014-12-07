<?php

function aGetHeader($title,$css,$isIndex){
    $logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
?>    
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <link rel="stylesheet" href="css/boilerplate.css" />
    <link rel="stylesheet" href="css/plantilla.css" />
    <link href="css/timeTo.css" type="text/css" rel="stylesheet"/>
    <?php echo '<link rel="stylesheet" href="css/'.$css.'.css" />'; ?>
    <script type="text/javascript" src="assets/js/countdown.js"></script>
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale = 1.0,maximum-scale = 1.0" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title><?php echo $title ?></title>
    </head>
    <body>
    <div id="primaryContainer" class="primaryContainer clearfix">
        <div id="header" class="clearfix">
            <div id="logo" class="clearfix">
            	<a href="index.php"><img src="png/AncarLOGO.png" width="250" height="80" /></a>
            </div>
            <div id="fecha_usuario" class="clearfix">
                <?php
            	echo'<p class="text_fecha_usuario clearfix">'.date("D j M Y").'</p>';
                if (isset($_SESSION['MM_Username'])){
                    echo '<p class="text_fecha_usuario clearfix">Bienvenido '.$_SESSION['MM_Username'].'</p>';
                }
                if ($isIndex){
                    echo '<a class="text_fecha_usuario enlace_desconectar" href="'.$logoutAction.'">Desconectar</a>';
                }
                ?>
            </div>';
       	</div>';
        <div id="main" class="clearfix">';
<?php
}
function aGetDorsalesAsignados($jugadores_reg){
    $row_jugadores_reg = mysql_fetch_assoc($jugadores_reg);
    $dorsalesocupados=array();
?>
    <div class="clearfix card">
        <div class="clearfix title_card">
            <p class="clearfix text_title_card">DORSALES ASIGNADOS</p>
        </div>
        <div class="clearfix container_card">
            <div class="clearfix tabla_container_card">
<?php
    $i=0;
    do {  
?>                      
                <div class="clearfix fila_container_card">
                    <div class="clearfix celdanombre_container_card">
                        <p class="clearfix textnombre_container_card"><?php echo $row_jugadores_reg['nombre'].' '.$row_jugadores_reg['apellidos']?></p>
                    </div>';
                    <div class="clearfix celdadorsal_container_card">';
                        <p class="clearfix textdorsal_container_card"><?php echo $row_jugadores_reg['dorsal'] ?></p>';
                    </div>';
                </div>';
<?php
        $dorsalesocupados[$i]=$row_jugadores_reg['dorsal'];
        $i++;
    } while ($row_jugadores_reg = mysql_fetch_assoc($jugadores_reg));
?>
            </div>
        </div>
    </div>
<?php
    return $dorsalesocupados;
}
function aGetSeleccionarDorsal($dorsalesocupados){
?>
    <div class="clearfix card">
        <div class="clearfix title_card">
            <p class="clearfix text_title_card">SELECCIONAR DORSAL</p>
        </div>
        <div class="clearfix container_card">
            <div class="clearfix tabla_container_card">
                <form id="form1" name="form1" method="POST" action="seleccionardorsal.php">
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdalabel_container_card">
                            <p class="clearfix textnombre_container_card">Seleccionar disponible</p>
                        </div>
                        <div class="clearfix celdaselect_container_card">
                            <p class="clearfix textdorsal_container_card">
                                <select name="tdorsal" id="tdorsal">
                                <option></option>
                <?php                                        
                            $j=1;
                            do {
                                if (!in_array($j, $dorsalesocupados)) {
                                    echo '<option value="'.$j.'">'.$j.'</option>';
                                }
                                $j++;
                            } while ($j<26);
                ?>    
                                </select>
                            </p>
                        </div>
                    </div>
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdabutton_container_card">
                            <p class="clearfix textnombre_container_card">
                                <input type="submit" name="tbutton" id="tbutton" value="Enviar" />
                            </p>
                        </div>
                    </div>
                    <input type="hidden" name="MM_update" value="form1" />
                </form>
            </div>
        </div>
    </div>
<?php    
}
function aGetSeleccionarPosicion(){
    $reg=getAllPositions();
    $row_demarcaciones_reg = mysql_fetch_assoc($reg);
?>
    <div class="clearfix card">
        <div class="clearfix title_card">
            <p class="clearfix text_title_card">SELECCIONAR POSICION</p>
        </div>
        <div class="clearfix container_card">
            <div class="clearfix tabla_container_card">
                <form id="form1" name="form1" method="POST" action="seleccionarposicion.php">
   
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdainstrucciones_container_card">
                            <p class="clearfix textinstrucciones_container_card">Selecciona una posicion en cada opcion siendo la primera la más relevante.</br></br>Cada posicion trae al lado un ejemplo de un jugador que referencia esa posicion para ayudar.</br></br>NO DEJES OPCIONES EN BLANCO</br></p>
                        </div>
                    </div>
    
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdalabel_container_card">
                            <p class="clearfix textopcion_container_card">Opcion1</p>
                        </div>
                        <div class="clearfix celdaselect_container_card">
                            <p class="clearfix textdorsal2_container_card">
                                <select name="tseleccion1" id="tseleccion1">
                                <option></option>
                        <?php        
                        do {        
                            echo '<option value="'.$row_demarcaciones_reg['iddemarcacion'].'">'.$row_demarcaciones_reg['demarcacion'].'//'.$row_demarcaciones_reg['ejemplo'].'</option>';
                        } while ($row_demarcaciones_reg = mysql_fetch_assoc($reg));
                        ?>
                                </select>
                            </p>
                        </div>
                    </div>
                    <?php
                        $reg=getAllPositions();
                        $row_demarcaciones_reg = mysql_fetch_assoc($reg);
                    ?>
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdalabel_container_card">
                            <p class="clearfix textopcion_container_card">Opcion2</p>
                        </div>
                        <div class="clearfix celdaselect_container_card">
                            <p class="clearfix textdorsal2_container_card">
                                <select name="tseleccion2" id="tseleccion2">
                                    <option></option>
                    <?php            
                        do {        
                            echo '<option value="'.$row_demarcaciones_reg['iddemarcacion'].'">'.$row_demarcaciones_reg['demarcacion'].'//'.$row_demarcaciones_reg['ejemplo'].'</option>';
                        } while ($row_demarcaciones_reg = mysql_fetch_assoc($reg));
                    ?>
                                </select>
                            </p>
                        </div>
                    </div>
                    <?php
    
                        $reg=getAllPositions();
                        $row_demarcaciones_reg = mysql_fetch_assoc($reg);
                    ?>
    
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdalabel_container_card">
                            <p class="clearfix textopcion_container_card">Opcion3</p>
                        </div>
                        <div class="clearfix celdaselect_container_card">
                            <p class="clearfix textdorsal2_container_card">
                                <select name="tseleccion3" id="tseleccion3">
                                    <option></option>';
                                <?php
                                do {        
                                    echo '<option value="'.$row_demarcaciones_reg['iddemarcacion'].'">'.$row_demarcaciones_reg['demarcacion'].'//'.$row_demarcaciones_reg['ejemplo'].'</option>';
                                } while ($row_demarcaciones_reg = mysql_fetch_assoc($reg));
                                ?>
                                </select>
                            </p>
                        </div>
                    </div>
    
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdabutton_container_card">
                            <p class="clearfix textnombre_container_card">
                                <input type="submit" name="tbutton" id="tbutton" value="Enviar" />
                            </p>
                        </div>
                    </div>
                    <input type="hidden" name="MM_insert" value="form1" />
                </form>
            </div>
        </div>
    </div>
<?php    
}
function aGetPosicionesAsignadas($jugadores_reg){
    $row_jugadores_reg = mysql_fetch_assoc($jugadores_reg);
    
    echo '<div class="clearfix card">';
    echo '<div class="clearfix title_card">';
    echo '<p class="clearfix text_title_card">POSICIONES ASIGNADAS</p>';
    echo '</div>';
    echo '<div class="clearfix container_card">';
    echo '<div class="clearfix tabla_container_card">';
    
    echo '<div class="clearfix fila_container_card">';
    echo '<div class="clearfix celdacabecera_container_card" style="width:5%;margin-left:0px">';
    echo '<p class="clearfix textcabecera_container_card">Nº</p>';
    echo '</div>';
    echo '<div class="clearfix celdacabecera_container_card" style="width:30%">';
    echo '<p class="clearfix textcabecera_container_card">Nombre</p>';
    echo '</div>';
    echo '<div class="clearfix celdacabecera_container_card" style="width:20%">';
    echo '<p class="clearfix textcabecera_container_card">1ª Opcion</p>';
    echo '</div>';
    echo '<div class="clearfix celdacabecera_container_card" style="width:20%">';
    echo '<p class="clearfix textcabecera_container_card">2ª Opcion</p>';
    echo '</div>';
    echo '<div class="clearfix celdacabecera_container_card" style="width:20%">';
    echo '<p class="clearfix textcabecera_container_card">3ª Opcion</p>';
    echo '</div>';
    echo '</div>';
    
    $i=0;
    $datos=array();
    do {  
        if ($i<2){
            $datos[$i]=$row_jugadores_reg['demarcacion'];
            $i++;
        }
        else{
            echo '<div class="clearfix fila_container_card">';
            echo '<div class="clearfix celdadorsal_container_card">';
            echo '<p class="clearfix textdorsal2_container_card">'.$row_jugadores_reg['dorsal'].'</p>';
            echo '</div>';
            echo '<div class="clearfix celdaname_container_card">';
            echo '<p class="clearfix textname_container_card">'.$row_jugadores_reg['nombre'].' '.$row_jugadores_reg['apellidos'].'</p>';
            echo '</div>';
            echo '<div class="clearfix celdaposicion_container_card">';
            echo '<p class="clearfix textposicion_container_card">'.$datos[0].'</p>';
            echo '</div>';
            echo '<div class="clearfix celdaposicion_container_card">';
            echo '<p class="clearfix textposicion_container_card">'.$datos[1].'</p>';
            echo '</div>';
            echo '<div class="clearfix celdaposicion_container_card">';
            echo '<p class="clearfix textposicion_container_card">'.$row_jugadores_reg['demarcacion'].'</p>';
            echo '</div>';
            echo '</div>';
            $i=0;
        }

    } while ($row_jugadores_reg = mysql_fetch_assoc($jugadores_reg));

    echo '</div>';
    echo '</div>';
    echo '</div>';
}
function aGetMensajeError($title,$mensaje,$titlelink,$link){
?>
    <div class="clearfix card" style="min-height: 150px">
        <div class="clearfix title_card">
            <p class="clearfix text_title_card"><?php echo $title ?></p>
        </div>';
        <div class="clearfix container_card">';
            <p class="clearfix text_error_sesion"><?php echo $mensaje ?></p>';
            <?php    
                if (isset($titlelink)){
                    echo '<a class="enlace_simple" href="'.$link.'">'.$titlelink.'!--></a>';
                }
            ?>    
        </div>';
    </div>';
<?php    
}
function aGetFormLogin(){
?>
    <div class="clearfix card">
        <div class="clearfix title_card">
            <p class="clearfix text_title_card">LOGIN</p>
        </div>
        <div class="clearfix container_card">
            <div class="clearfix tabla_container_card">
                <form id="form1" name="form1" method="POST" action="login.php">
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdalabel_container_card">
                            <p class="clearfix textnombre_container_card">Usuario:</p>
                        </div>
                        <div class="clearfix celdainput_container_card">
                            <p class="clearfix textdorsal_container_card">
                                <input type="text" name="tusername" id="tusername" />
                            </p>
                        </div>
                    </div>
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdalabel_container_card">
                            <p class="clearfix textnombre_container_card">Password:</p>
                        </div>
                        <div class="clearfix celdainput_container_card">
                            <p class="clearfix textdorsal_container_card">
                                <input type="password" name="tpassword" id="tpassword" />
                            </p>
                        </div>
                    </div>
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdabutton_container_card">
                            <p class="clearfix textnombre_container_card">
                                <input type="submit" name="tbutton" id="tbutton" value="Enviar" />
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
}
function aGetMain($admin){
?>
    <div class="clearfix card">
        <div class="clearfix title_card">
            <p class="clearfix text_title_card">MENU PRINCIPAL</p>
        </div>
        <div class="clearfix container_card">
            <div class="clearfix tabla_container_card">
                <div class="clearfix fila_container_card">
                    <a id=nuevo2 class="enlace_simple" href="votacion.php?idpartido=5">Votaciones Jornada 5--></a>
                </div>
                <div class="clearfix fila_container_card">
                    <a id=nuevo class="enlace_simple" href="votacion.php?idpartido=6">Votaciones Jornada 6--></a>
                </div>
                <div class="clearfix fila_container_card">
                    <a class="enlace_simple" href="votacion.php?idpartido=1">Datos Jornada 1--></a>
                </div>
                <div class="clearfix fila_container_card">
                    <a class="enlace_simple" href="votacion.php?idpartido=2">Datos Jornada 2--></a>
                </div>
                <div class="clearfix fila_container_card">
                    <a class="enlace_simple" href="votacion.php?idpartido=3">Datos Jornada 3--></a>
                </div>
                <div class="clearfix fila_container_card">
                    <a class="enlace_simple" href="votacion.php?idpartido=4">Datos Jornada 4--></a>
                </div>
            </div>
        </div>
    </div>
<?php    
}
function aGetScriptCountdown($fechapartido,$horapartido){
    $timestampstart=date("Y-m-j G:i:s");
    $timestampfin=$fechapartido." ".$horapartido;
    $time=calcula_tiempo($timestampstart, $timestampfin);
    
    $tok = strtok($horapartido, ":");
    $hora=intval($tok);
    $tok = strtok(":");
    $minutos=intval($tok);
    $dias=intval($time['dias']);
    $dias=$dias+1;
?>    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="assets/js/jquery.timeTo.min.js"></script>
    <script>
        date = getRelativeDate('.$dias.','.$hora.','.$minutos.');
        date = getRelativeDate(<?php echo $dias ?>,<?php echo $hora ?>,<?php echo $minutos ?>);
    /*echo 'date = getRelativeDate(7,'.$hora.','.$minutos.');';*/
    /*echo 'document.getElementById("date-str").innerHTML = date.toString();';*/

        $("#countdown-3").timeTo({
            timeTo: date,
            displayDays: 2,
            theme: "black",
            displayCaptions: true,
            fontSize: 12,
            captionSize: 8
        });

        function getRelativeDate(days, hours, minutes){
            var date = new Date((new Date()).getTime() + 60000 /* milisec */ * 60 /* minutes */ * 24 /* hours */ * days /* days */);
            date.setHours(hours || 0);
            date.setMinutes(minutes || 0);
            date.setSeconds(0);
            return date;
        }
    </script>
<?php    
}
function aGetScriptanimacioncolor(){
?>    
    <script>
    $(document).ready(function() {
        function animateDivers() {
            $("#nuevo").animate({"color":"#f00"},1000).animate({"color":"#00f"},1000, animateDivers);
            $("#nuevo2").animate({"color":"#00f"},1000).animate({"color":"#0f0"},1000, animateDivers);
        }   
        animateDivers();
    });
    </script>';
<?php    
}
function aGetSeleccionarDisponibilidad($partido){
    
    $row_reg = mysql_fetch_assoc($reg);
?>    
    <div class="clearfix card">
        <div class="clearfix title_card">
            <p class="clearfix text_title_card">SELECCIONAR DISPONIBILIDAD</br><span style="font-weight: bold;">ANCAR-<?php echo $partido['rival'] ?></span></p>
        </div>
        <div class="clearfix container_card">
            <div class="clearfix tabla_container_card">
                <form id="form1" name="form1" method="POST" action="disponibilidad.php">    
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdainstrucciones_container_card">
                            <p class="clearfix textinstrucciones_container_card"></br>Selecciona si estaras disponible o no, para el proximo partido.</br></br>AQUI NO HAY DEPENDES. O estas disponible o no lo estas.</br></br>Tienes hasta el viernes a las 15:00 para responder. Si no respondes, se asumirá que no estás disponible</br></br></p>
                        </div>
                    </div>    
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdadisponibilidad_container_card">
                            <p class="clearfix textdisponibilidad_container_card">TIEMPO HASTA FIN DE CONVOCATORIA</p>
                        </div>
                    </div>
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdadisponibilidad_container_card">
                            <div id="countdown-3"></div>
                        </div>
                    </div>    
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdadisponibilidad_container_card">
                            <p class="clearfix textdisponibilidad_container_card">Dia: <span style="font-weight: bold;"><?php echo $partido['fecha']?></span></p>
                        </div>
                    </div>
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdadisponibilidad_container_card">
                            <p class="clearfix textdisponibilidad_container_card">Hora: <span style="font-weight: bold;"><?php echo $partido['hora']?></span></p>
                        </div>
                    </div>
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdadisponibilidad_container_card">
                            <p class="clearfix textdisponibilidad_container_card">Campo: <span style="font-weight: bold;"><?php echo $partido['nombre']?></span></p>
                        </div>
                    </div>
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdadisponibilidad_container_card">
                            <p class="clearfix textdisponibilidad_container_card">Direccion: <span style="font-weight: bold;"><?php echo $partido['direccion']?></span></p>
                        </div>
                    </div>
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdadisponibilidad_container_card">
                            <p class="clearfix textdisponibilidad_container_card">Link: <a href="<?php echo $partido['linkmaps'] ?>">Google Maps:</a></p>
                        </div>
                    </div>
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdadisponibilidad_container_card">
                            <p class="clearfix textdisponibilidad_container_card">Rival: <span style="font-weight: bold;"><?php echo $partido['rival']?></span></p>
                        </div>
                    </div>    
                    <div class="clearfix fila_container_card">
                        <div class="clearfix celdabutton_container_card" style="width:200px;">
                            <p class="clearfix textnombre_container_card">
                                <input type="submit" name="tbutton1" style="float:left;" class="button_disponiblilidad" id="tbutton1" value="Si" />
                                <input type="submit" name="tbutton2" style="float:right;" class="button_disponiblilidad" id="tbutton2" value="No" />
                            </p>
                        </div>
                    </div>
                    <input type="hidden" name="MM_update" value="form1" />
                </form>
            </div>
        </div>
    </div>
<?php   
}
function aGetVotacionesDelPartido($idjugador,$partido){
    
    $reg=getConvocadosPartido($partido['idpartido']);
    $row_reg = mysql_fetch_assoc($reg);
    
    echo '<div class="clearfix card">';
    echo '<div class="clearfix title_card">';
    echo '<p class="clearfix text_title_card">VALORACIONES JUGADORES</br><span style="font-weight: bold;">ANCAR-'.$partido['rival'].'</span></p>';
    echo '</div>';
    echo '<div class="clearfix container_card">';
    echo '<div class="clearfix tabla_container_card">';
    echo '<form id="form1" name="form1" method="POST" action="votacion.php?idpartido='.$partido['idpartido'].'">';
    
    echo '<div class="clearfix fila_container_card">';
    echo '<div class="clearfix celdainstrucciones_container_card">';
    echo '<p class="clearfix textinstrucciones_container_card"></br>Vota segun tus propios criterios entre los jugadores convocados para el partido.</br></br></p>';
    echo '</div>';
    echo '</div>';
    
    do{
        if ($row_reg['idjugador']!=$idjugador){
            echo '<div class="clearfix fila_container_card">';
            echo '<div class="clearfix celdalabel_container_card" style="width:230px; margin-right:10px;">';
            echo '<p class="clearfix textopcion_container_card">'.$row_reg['dorsal'].' '.$row_reg['nombre'].' '.$row_reg['apellidos'].'</p>';
            echo '</div>';
            echo '<div class="clearfix celdaselect_container_card">';
            echo '<p class="clearfix textopcion_container_card">';
            echo '<select name="tselec'.$row_reg['idjugador'].'" id="tselec'.$row_reg['idjugador'].'">';
            echo '<option></option>';
            for ($i=1;$i<11;$i++) {        
                echo '<option value="'.$i.'">'.$i.'</option>';
            }
            echo '</select>';
            echo '</p>';
            echo '</div>';
            echo '</div>';
        }
    } while ($row_reg = mysql_fetch_assoc($reg));
    
    echo '<div class="clearfix fila_container_card">';
    echo '<div class="clearfix celdabutton_container_card">';
    echo '<p class="clearfix textnombre_container_card">';
    echo '<input type="submit" name="tbutton" id="tbutton" value="Enviar" />';
    echo '</p>';
    echo '</div>';
    echo '</div>';
    echo '<input type="hidden" name="MM_update" value="form1" />';
    echo '</form>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    
}
function aGetFichaDelPartido($partido){
    $idpartido=$partido['idpartido'];
    $titulares=  getTitularesPartido($idpartido);
    $suplentes=  getSuplentesPartido($idpartido);
    
    $row_reg = mysql_fetch_assoc($titulares);
    echo '<div class="clearfix card">';
    echo '<div class="clearfix title_card">';
    echo '<p class="clearfix text_title_card">FICHA DEL PARTIDO</br><span style="font-weight: bold;">ANCAR '.$partido['golesfavor'].' - '.$partido['golescontra'].' '.$partido['rival'].'</span></p>';
    echo '</div>';
    echo '<div class="clearfix container_card">';
    echo '<div class="clearfix tabla_container_card">';
    
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdainstrucciones_container_card">';
            echo '<p class="clearfix textinstrucciones_container_card">Fecha: <span style="font-weight: bold">'.$partido['fecha'].'</span></p>';
        echo '</div>';
    echo '</div>';
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdainstrucciones_container_card">';
            echo '<p class="clearfix textinstrucciones_container_card">Lugar: <span style="font-weight: bold">'.$partido['nombre'].'</span></p>';
        echo '</div>';
    echo '</div>';
    
    
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdainstrucciones_container_card">';
            echo '<p class="clearfix textinstrucciones_container_card">Titulares</p>';
        echo '</div>';
    echo '</div>';
    
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdatitulodorsal_container_card">';
            /*echo '<p class="clearfix texttitulonombre_container_card">Jugaron el partido</p>';*/
        echo '</div>';
        echo '<div class="clearfix celdatitulonombre_container_card">';
            /*echo '<p class="clearfix texttitulonombre_container_card">Jugaron el partido</p>';*/
        echo '</div>';
        echo '<div class="clearfix celdatitulodatos_container_card">';
            echo '<p class="clearfix texttitulodatos_container_card">Gol</p>';
        echo '</div>';
        echo '<div class="clearfix celdatitulodatos_container_card">';
            echo '<p class="clearfix texttitulodatos_container_card">Asis</p>';
        echo '</div>';
        echo '<div class="clearfix celdatitulodatos_container_card">';
            echo '<p class="clearfix texttitulodatos_container_card">T.A.</p>';
        echo '</div>';
        echo '<div class="clearfix celdatitulodatos_container_card">';
            echo '<p class="clearfix texttitulodatos_container_card">T.R.</p>';
        echo '</div>';
        echo '<div class="clearfix celdatitulodatos_container_card">';
            echo '<p class="clearfix texttitulodatos_container_card">Nota</p>';
        echo '</div>';
    echo '</div>';
    
    do {
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdadorsal_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['dorsal'].'</p>';
        echo '</div>';
        echo '<div class="clearfix celdanombre_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['nombre'].' '.$row_reg['apellidos'].'</p>';
        echo '</div>';
        
        echo '<div class="clearfix celdadatos_container_card">';
            if ($row_reg['esportero']){
                echo '<p class="clearfix textvotacion_container_card">-'.$row_reg['goles'].'</p>';
            }
            else{
                echo '<p class="clearfix textvotacion_container_card">'.$row_reg['goles'].'</p>';
            }
        echo '</div>';
        
        echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['asistencias'].'</p>';
        echo '</div>';
        echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['TA'].'</p>';
        echo '</div>';
        echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['TR'].'</p>';
        echo '</div>';
        echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.number_format($row_reg['valoracion'],1).'</p>';
        echo '</div>';
    echo '</div>';
    if ($row_reg['minutos']<90){
        echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdacambio_container_card">';
            echo '<p class="clearfix textvotacion_container_card">--> Sale Minuto '.$row_reg['minutos'].'</p>';
        echo '</div>';
        echo '</div>';
    }
    } while ($row_reg = mysql_fetch_assoc($titulares));
    
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdainstrucciones_container_card">';
            echo '<p class="clearfix textinstrucciones_container_card">Suplentes</p>';
        echo '</div>';
    echo '</div>';
    $row_reg = mysql_fetch_assoc($suplentes);
    do {
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdadorsal_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['dorsal'].'</p>';
        echo '</div>';
        echo '<div class="clearfix celdanombre_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['nombre'].' '.$row_reg['apellidos'].'</p>';
        echo '</div>';
        
        echo '<div class="clearfix celdadatos_container_card">';
            if ($row_reg['esportero']){
                echo '<p class="clearfix textvotacion_container_card">-'.$row_reg['goles'].'</p>';
            }
            else{
                echo '<p class="clearfix textvotacion_container_card">'.$row_reg['goles'].'</p>';
            }
        echo '</div>';
        
        echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['asistencias'].'</p>';
        echo '</div>';
        echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['TA'].'</p>';
        echo '</div>';
        echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['TR'].'</p>';
        echo '</div>';
        echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.number_format($row_reg['valoracion'],1).'</p>';
        echo '</div>';
    echo '</div>';
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdacambio_container_card">';
            echo '<p class="clearfix textvotacion_container_card"><-- Entra Minuto '.(90-$row_reg['minutos']).'</p>';
        echo '</div>';
    echo '</div>';
    } while ($row_reg = mysql_fetch_assoc($suplentes));
    
    
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
function aGetFichaDelPartido2($partido){
    $idpartido=$partido['idpartido'];
    $titulares=  getTitularesPartido($idpartido);
    $suplentes=  getSuplentesPartido($idpartido);
    
    $row_reg = mysql_fetch_assoc($titulares);
    echo '<div class="clearfix card">';
    echo '<div class="clearfix title_card">';
    echo '<p class="clearfix text_title_card">FICHA DEL PARTIDO</br><span style="font-weight: bold;">ANCAR '.$partido['golesfavor'].' - '.$partido['golescontra'].' '.$partido['rival'].'</span></p>';
    echo '</div>';
    echo '<div class="clearfix container_card">';
    echo '<div class="clearfix tabla_container_card">';
    
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdainstrucciones_container_card">';
            echo '<p class="clearfix textinstrucciones_container_card">Fecha: <span style="font-weight: bold">'.$partido['fecha'].'</span></p>';
        echo '</div>';
    echo '</div>';
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdainstrucciones_container_card">';
            echo '<p class="clearfix textinstrucciones_container_card">Lugar: <span style="font-weight: bold">'.$partido['nombre'].'</span></p>';
        echo '</div>';
    echo '</div>';
    
    
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdainstrucciones_container_card">';
            echo '<p class="clearfix textinstrucciones_container_card">Titulares</p>';
        echo '</div>';
    echo '</div>';
    
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdatitulodorsal_container_card">';
            /*echo '<p class="clearfix texttitulonombre_container_card">Jugaron el partido</p>';*/
        echo '</div>';
        echo '<div class="clearfix celdatitulonombre_container_card">';
            /*echo '<p class="clearfix texttitulonombre_container_card">Jugaron el partido</p>';*/
        echo '</div>';
        echo '<div class="clearfix celdatitulodatos_container_card">';
            echo '<p class="clearfix texttitulodatos_container_card">Gol</p>';
        echo '</div>';
        echo '<div class="clearfix celdatitulodatos_container_card">';
            echo '<p class="clearfix texttitulodatos_container_card">Asis</p>';
        echo '</div>';
        echo '<div class="clearfix celdatitulodatos_container_card">';
            echo '<p class="clearfix texttitulodatos_container_card">T.A.</p>';
        echo '</div>';
        echo '<div class="clearfix celdatitulodatos_container_card">';
            echo '<p class="clearfix texttitulodatos_container_card">T.R.</p>';
        echo '</div>';
        /*echo '<div class="clearfix celdatitulodatos_container_card">';
            echo '<p class="clearfix texttitulodatos_container_card">Nota</p>';
        echo '</div>';*/
    echo '</div>';
    
    do {
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdadorsal_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['dorsal'].'</p>';
        echo '</div>';
        echo '<div class="clearfix celdanombre_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['nombre'].' '.$row_reg['apellidos'].'</p>';
        echo '</div>';
        
        echo '<div class="clearfix celdadatos_container_card">';
            if ($row_reg['esportero']){
                echo '<p class="clearfix textvotacion_container_card">-'.$row_reg['goles'].'</p>';
            }
            else{
                echo '<p class="clearfix textvotacion_container_card">'.$row_reg['goles'].'</p>';
            }
        echo '</div>';
        
        echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['asistencias'].'</p>';
        echo '</div>';
        echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['TA'].'</p>';
        echo '</div>';
        echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['TR'].'</p>';
        echo '</div>';
        /*echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['valoracion'].'</p>';
        echo '</div>';*/
    echo '</div>';
    if ($row_reg['minutos']<90){
        echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdacambio_container_card">';
            echo '<p class="clearfix textvotacion_container_card">--> Sale Minuto '.$row_reg['minutos'].'</p>';
        echo '</div>';
        echo '</div>';
    }
    } while ($row_reg = mysql_fetch_assoc($titulares));
    
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdainstrucciones_container_card">';
            echo '<p class="clearfix textinstrucciones_container_card">Suplentes</p>';
        echo '</div>';
    echo '</div>';
    $row_reg = mysql_fetch_assoc($suplentes);
    do {
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdadorsal_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['dorsal'].'</p>';
        echo '</div>';
        echo '<div class="clearfix celdanombre_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['nombre'].' '.$row_reg['apellidos'].'</p>';
        echo '</div>';
        
        echo '<div class="clearfix celdadatos_container_card">';
            if ($row_reg['esportero']){
                echo '<p class="clearfix textvotacion_container_card">-'.$row_reg['goles'].'</p>';
            }
            else{
                echo '<p class="clearfix textvotacion_container_card">'.$row_reg['goles'].'</p>';
            }
        echo '</div>';
        
        echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['asistencias'].'</p>';
        echo '</div>';
        echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['TA'].'</p>';
        echo '</div>';
        echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['TR'].'</p>';
        echo '</div>';
        /*echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['valoracion'].'</p>';
        echo '</div>';*/
    echo '</div>';
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdacambio_container_card">';
            echo '<p class="clearfix textvotacion_container_card"><-- Entra Minuto '.(90-$row_reg['minutos']).'</p>';
        echo '</div>';
    echo '</div>';
    } while ($row_reg = mysql_fetch_assoc($suplentes));
    
    
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
function aGetDatosPartido($partido,$idjugadormvp){
    $idpartido=$partido['idpartido'];
    $titulares=  getTitularesPartido($idpartido);
    $suplentes=  getSuplentesPartido($idpartido);
    
    $row_reg = mysql_fetch_assoc($titulares);
    echo '<div class="clearfix card">';
    echo '<div class="clearfix title_card">';
    echo '<p class="clearfix text_title_card">FICHA DEL PARTIDO</br><span style="font-weight: bold;">ANCAR '.$partido['golesfavor'].' - '.$partido['golescontra'].' '.$partido['rival'].'</span></p>';
    echo '</div>';
    echo '<div class="clearfix container_card">';
    echo '<div class="clearfix tabla_container_card">';
    
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdainstrucciones_container_card">';
            echo '<p class="clearfix textinstrucciones_container_card">Fecha: <span style="font-weight: bold">'.$partido['fecha'].'</span></p>';
        echo '</div>';
    echo '</div>';
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdainstrucciones_container_card">';
            echo '<p class="clearfix textinstrucciones_container_card">Lugar: <span style="font-weight: bold">'.$partido['nombre'].'</span></p>';
        echo '</div>';
    echo '</div>';
    
    
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdainstrucciones_container_card">';
            echo '<p class="clearfix textinstrucciones_container_card">Titulares</p>';
        echo '</div>';
    echo '</div>';
    
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdatitulodorsal_container_card">';
        echo '</div>';
        echo '<div class="clearfix celdatitulonombre_container_card">';
        echo '</div>';
        echo '<div class="clearfix celdatitulodatos_container_card">';
            echo '<p class="clearfix texttitulodatos_container_card">Gol</p>';
        echo '</div>';
        echo '<div class="clearfix celdatitulodatos_container_card">';
            echo '<p class="clearfix texttitulodatos_container_card">Asis</p>';
        echo '</div>';
        echo '<div class="clearfix celdatitulodatos_container_card">';
            echo '<p class="clearfix texttitulodatos_container_card">T.A.</p>';
        echo '</div>';
        echo '<div class="clearfix celdatitulodatos_container_card">';
            echo '<p class="clearfix texttitulodatos_container_card">T.R.</p>';
        echo '</div>';
        echo '<div class="clearfix celdatitulodatos_container_card">';
            echo '<p class="clearfix texttitulodatos_container_card">Nota</p>';
        echo '</div>';
    echo '</div>';
    
    do {
        if ($row_reg['jugador_id']==$idjugadormvp){
            echo '<div class="clearfix fila_container_card_mvp">';
        }
        else{
            echo '<div class="clearfix fila_container_card">';
        }
            echo '<div class="clearfix celdadorsal_container_card">';
                echo '<p class="clearfix textvotacion_container_card">'.$row_reg['dorsal'].'</p>';
            echo '</div>';
            echo '<div class="clearfix celdanombre_container_card">';
                echo '<p class="clearfix textvotacion_container_card">'.$row_reg['nombre'].' '.$row_reg['apellidos'].'</p>';
            echo '</div>';

            echo '<div class="clearfix celdadatos_container_card">';
                if ($row_reg['esportero']){
                    echo '<p class="clearfix textvotacion_container_card">-'.$row_reg['goles'].'</p>';
                }
                else{
                    echo '<p class="clearfix textvotacion_container_card">'.$row_reg['goles'].'</p>';
                }
            echo '</div>';

            echo '<div class="clearfix celdadatos_container_card">';
                echo '<p class="clearfix textvotacion_container_card">'.$row_reg['asistencias'].'</p>';
            echo '</div>';
            echo '<div class="clearfix celdadatos_container_card">';
                echo '<p class="clearfix textvotacion_container_card">'.$row_reg['TA'].'</p>';
            echo '</div>';
            echo '<div class="clearfix celdadatos_container_card">';
                echo '<p class="clearfix textvotacion_container_card">'.$row_reg['TR'].'</p>';
            echo '</div>';
            echo '<div class="clearfix celdadatos_container_card">';
                echo '<p class="clearfix textvotacion_container_card">'.number_format($row_reg['valoracion'],1).'</p>';
            echo '</div>';
        echo '</div>';
        if ($row_reg['minutos']<90){
            echo '<div class="clearfix fila_container_card">';
            echo '<div class="clearfix celdacambio_container_card">';
                echo '<p class="clearfix textvotacion_container_card">--> Sale Minuto '.$row_reg['minutos'].'</p>';
            echo '</div>';
            echo '</div>';
        }
    } while ($row_reg = mysql_fetch_assoc($titulares));
    
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdainstrucciones_container_card">';
            echo '<p class="clearfix textinstrucciones_container_card">Suplentes</p>';
        echo '</div>';
    echo '</div>';
    $row_reg = mysql_fetch_assoc($suplentes);
    do {
        
        if ($row_reg['jugador_id']==$idjugadormvp){
            echo '<div class="clearfix fila_container_card_mvp">';
        }
        else{
            echo '<div class="clearfix fila_container_card">';
        }
        
    /*echo '<div class="clearfix fila_container_card">';*/
        echo '<div class="clearfix celdadorsal_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['dorsal'].'</p>';
        echo '</div>';
        echo '<div class="clearfix celdanombre_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['nombre'].' '.$row_reg['apellidos'].'</p>';
        echo '</div>';
        
        echo '<div class="clearfix celdadatos_container_card">';
            if ($row_reg['esportero']){
                echo '<p class="clearfix textvotacion_container_card">-'.$row_reg['goles'].'</p>';
            }
            else{
                echo '<p class="clearfix textvotacion_container_card">'.$row_reg['goles'].'</p>';
            }
        echo '</div>';
        
        echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['asistencias'].'</p>';
        echo '</div>';
        echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['TA'].'</p>';
        echo '</div>';
        echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.$row_reg['TR'].'</p>';
        echo '</div>';
        echo '<div class="clearfix celdadatos_container_card">';
            echo '<p class="clearfix textvotacion_container_card">'.number_format($row_reg['valoracion'],1).'</p>';
        echo '</div>';
    echo '</div>';
    echo '<div class="clearfix fila_container_card">';
        echo '<div class="clearfix celdacambio_container_card">';
            echo '<p class="clearfix textvotacion_container_card"><-- Entra Minuto '.(90-$row_reg['minutos']).'</p>';
        echo '</div>';
    echo '</div>';
    } while ($row_reg = mysql_fetch_assoc($suplentes));
    
    
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
function aGetFooter(){
?>    
                </div>
                <div id="footer" class="clearfix">
                    <p class="text_footer">by © LOTY 2014, Lotybaink@hotmail.com</p>
                </div>
            </div>
        </body>
    </html>
<?php    
}
?>




