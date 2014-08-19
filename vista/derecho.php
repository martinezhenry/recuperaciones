<html> 
<head>
<title>Menu</title>
<script type="text/javascript">
var ns4 = (document.layers)? true:false 
var ie = (document.all)? true:false 
var ns6 = (document.getElementById && !document.all) ? true: false; 
var coorX, coorY; 
if (ns6) document.addEventListener("mousedown", coord, true) 
if (ns4) {document.captureEvents(Event.MOUSEDOWN); document.mousedown = coord;} 
if (ie) document.onmousedown = coord;

function coord(e)
{
  if (ns4||ns6)  { 
   coorX = e.pageX; 
   coorY = e.pageY; 
  } 
  if (ie)  { 
   coorX = event.x; 
   coorY = event.y; 
  } 
  if (document.layers && !document.getElementById){ 
   if (e.which == 2 || e.which == 3){ 
   mostrar();
   return false; 
   } 
  } 

  return true;
}
function mostrar()
{ 

  with(document.getElementById("menu"))  { 
   style.top = coorY + 10; 
   style.left = coorX + 10; 
   style.visibility = "visible"; 
  } 
  return false;
} 

function ocultar()
{ 
  document.getElementById("menu").style.visibility = "hidden"; 
} 
document.oncontextmenu=mostrar
</script> <style>
.skin0
{
position:absolute;
border:1.5px solid black;
background-color:menu;
font-family:Verdana;
line-height:20px;
cursor:default;
height:auto;
}
.menuitems
{
font-size: 12px;
FONT-FAMILY:Verdana;
padding-right:20px;
}
</style>
</head> 
<body onclick="ocultar()">
<center><b>Haz Click Derecho</b></center>
<div id="menu" class="skin0" style="visibility:hidden"> 
<div class="menuitems" onclick="location.href='http://foro.skylium.com'" onmouseover="this.style.background='highlight';this.style.color='white'" onmouseout="this.style.background='menu';this.style.color='black'">Skylium</div>

<div class="menuitems" onclick="location.href='http://hostig.skylium.com'" onmouseover="this.style.background='highlight';this.style.color='white'" onmouseout="this.style.background='menu';this.style.color='black'">Hosting</div>
</div>
</body>
</html>