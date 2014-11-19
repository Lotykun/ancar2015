var salida=false;

function cargando(fechainicio, fechahoy) {
    var tot = $('.event').length;
    var timelineWidth = 165 * tot + 165;
    /*var screenWidth = $(document).width();*/

    var desplazo = ((fechahoy - fechainicio) / 86400) * 165;

    $('#timelineScroll').width(timelineWidth);
    /*$('#timelineScroll').css("margin-right",timelineWidth);*/
    $('#timelineScroll').css("margin-left", 0-desplazo);
}
function moverDerecha() {
    var puntos = $('#timelineScroll').css("margin-left");
    puntos = parseFloat(puntos);
    if (puntos < 0) {
        $('#timelineScroll').animate({ marginLeft: "+=165px" }, 1000);
    }
}
function moverIzquierda() {

    var puntos = $('#timelineScroll').css("margin-left");
    var ancho = $('#timelineScroll').css("width");

    puntos = parseFloat(puntos);
    puntos = Math.abs(puntos);
    ancho = parseFloat(ancho);

    if (puntos < (ancho - 2 * 165)) {
        $('#timelineScroll').animate({ marginLeft: "-=165px" }, 1000);
    }
}
function moverFastIzquierda() {

    var puntos = $('#timelineScroll').css("margin-left");
    var ancho = $('#timelineScroll').css("width");

    puntos = parseFloat(puntos);
    puntos = Math.abs(puntos);
    ancho = parseFloat(ancho);

    if (puntos < (ancho - 2 * 165)) {
        $('#timelineScroll').animate({ marginLeft: "-=1650px" }, 5000);
    }
}
function moverFastDerecha() {

    var puntos = $('#timelineScroll').css("margin-left");
    puntos = parseFloat(puntos);
    if (puntos < 0) {
        $('#timelineScroll').animate({ marginLeft: "+=1650px" }, 5000);
    }
}
function parar() {
    $('#timelineScroll').stop(true);
}
function cambiarcolor() {
    
    $('#flecha_der').css("background-color","#60F");
    
}
function cambiarcolor2() {
    
    /*$('#flecha_der').css("background-color","#FF3");*/
	$('#flecha_der').css("background-color","");
    
}