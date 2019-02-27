<?
	include("conex.php");	
	//include("local_controla2.php");//controla2 usuario admin.
	$_SESSION['total_compra']=0;
//Ventas
$ultima_fecha=mysql_query("SELECT MAX(Fecha) FROM caja",$link);
$ult_fecha=mysql_fetch_array($ultima_fecha);
$ventas=mysql_query("SELECT * FROM ventas, ventas_detalle WHERE ventas.id_venta=ventas_detalle.id_venta AND fecha='".$ult_fecha[0]."' AND id_forma=1",$link);
//Sumamos las ventas
//+($venta['efectivo'])
$total_ventas=0;
while($venta=mysql_fetch_array($ventas))
{
	$total_ventas=$total_ventas+($venta['precio']*$venta['cantidad'])-(($venta['descuento']*($venta['precio']*$venta['cantidad'])/100));
}
//Sumamos parte en efectivo de las ventas hechas con tarjeta 
$ventas_tarjetas=mysql_query("SELECT * FROM ventas WHERE fecha='".$ult_fecha[0]."' AND (id_forma=2 OR id_forma=3) AND efectivo>0",$link);
//Sumamos al total de ventas
while($venta_tarjeta=mysql_fetch_array($ventas_tarjetas))
{
	$total_ventas=$total_ventas+$venta_tarjeta['efectivo'];
}
//Caja actual
	$caja_actual=mysql_query("SELECT billetes, monedas, fecha FROM caja WHERE fecha='".$ult_fecha[0]."'",$link);
	$datos_caja_actual=mysql_fetch_array($caja_actual);
	$consultas=mysql_query("SELECT * FROM consultas WHERE respuesta=''",$link);
	$cant_consultas=mysql_num_rows($consultas);
	$contactos=mysql_query("SELECT * FROM contacto WHERE respuesta=''",$link);
	$cant_contactos=mysql_num_rows($contactos);
//Aportes
$aportes=mysql_query("SELECT * FROM caja_aporte WHERE fecha='".$ult_fecha[0]."'",$link);
$total_aportes=0;
while($aporte=mysql_fetch_array($aportes))
{
	$total_aportes=$total_aportes+$aporte['billetes']+$aporte['monedas'];
}
//Extracciones
$extracciones=mysql_query("SELECT * FROM caja_extraccion WHERE fecha='".$ult_fecha[0]."'",$link);
$total_extracciones=0;
while($extraccion=mysql_fetch_array($extracciones))
{
	$total_extracciones=$total_extracciones+$extraccion['billetes']+$extraccion['monedas'];
}
?>
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="sistema.css" rel="stylesheet" type="text/css">
</head>

<body>
<p><a href="ver_ventas.php">VENTAS</a> efectivo HOY ($ <? echo $total_ventas;?>)</p>
<p><a href="ver_compras.php">COMPRAS</a></p>
<p><a href="ver_stock.php">STOCK</a></p>
<p>PEDIDO por proveedor</font></p>
<p><a href="ver_caja.php">CAJA</a>HOY ($ <? echo $datos_caja_actual['billetes']+$datos_caja_actual['monedas'];?>)</p>
<p><a href="ver_cajacierre.php">CAJA CIERRE</a></p>
<p><a href="ver_cajaaporte.php">CAJA APORTE </a>(+)HOY ($ <? echo $total_aportes;?>)</p>
<p><a href="ver_cajaextraccion.php">CAJA EXTRACCION</a> (-)HOY ($ <? echo $total_extracciones;?>)</p>
<p><strong><font color="#333333" size="4"><a href="ver_cierre.php">CIERRE DIARIO</a></font></strong> mostrar + o - (de ultima caja)</p>
<p><strong><font color="#333333" size="4"><a href="ver_balance.php">BALANCE</a></font></strong></p>
<p><strong><font color="#333333" size="4">AUDITORIA</font></strong><br>
</p>
<table width="100%" border="1">
  <tr> 
    <td width="8%"><font color="#333333" size="4"><strong><a href="ver_contacto.php">contactos</a></strong></font></td>
    <td width="92%" align="right"><a href="ver_mensajes2.php">ver_mensajes</a></td>
  </tr>
  <tr> 
    <td><font color="#333333" size="4"> <strong><a href="ver_consultas.php">consultas</a></strong></font></td>
    <td>&nbsp;</td>
  </tr>
</table>
<font color="#666666" size="4"><strong><br>
<font color="#333333">EDICION DE DATOS</font></strong></font><br>
<table width="100%" border="1">
  <tr> 
    <td width="12%"><font color="#333333" size="4"><strong>productos</strong></font></td>
    <td width="8%"><font size="4"><a href="alta_producto.php">alta</a></font></td>
    <td width="12%"><font size="4"><a href="alta_modificar.php">modificar</a></font></td>
    <td width="7%"><font size="4"><a href="baja_producto.php">baja</a></font></td>
    <td width="61%">&nbsp;</td>
  </tr>
  <tr> 
    <td><font color="#333333" size="4"> <strong>categorias</strong></font></td>
    <td><font size="4"><a href="alta_categoria.php">alta</a></font></td>
    <td><font size="4"><a href="alta_modificar_cat.php">modificar</a></font></td>
    <td><font size="4"><a href="baja_categoria.php">baja</a></font></td>
    <td>&nbsp;</td>
  </tr>
</table>
<p> <font size="4"><a href="local_kill_session.php">DESCONECTAR</a> </font></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
