<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>&laquo;TimeTo&raquo; jQuery plug-in example</title>
    <link href="css/timeTo.css" type="text/css" rel="stylesheet"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
</head>
<body onload="probando()">
    <p id="nuevo">Pruebas</p>
</body>
</html>
<script>
    $(document).ready(function() {   
        function animateDivers() {
            $('#nuevo').animate({'color':'#fff'},500).animate({'color':'#000'},500, animateDivers); 
        }
        animateDivers();
    });
  </script>
