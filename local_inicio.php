<?
	session_start();
	$_SESSION['pago']=1;
	$_SESSION['efectivo']="";
	$_SESSION['recargo']="";
	$_SESSION['descuento']="";	
	$hora_actual=gettimeofday();//Obtiene la hora actual.
	$_SESSION["hora"]=$hora_actual["sec"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>LOKALES TRAINING SPOT</title>
<META http-equiv=Content-Type content="text/html; charset=windows-1252"><LINK 
href="http://www.lokales.com.ar/favico.ico" rel="shortcut icon">
</head>

<body>
<form action="local_procesa_inicio.php" method="post" name="" id="">
  <p>
<input type="text" name="usuario" id="usuario">
    <font size="3" face="Times New Roman, Times, serif"> <strong>usuario</strong></font></p>
  <p> 
    <input name="clave" type="password" id="clave">
    <font size="3" face="Times New Roman, Times, serif"><strong>clave</strong></font> 
  </p>
  <p> 
    <input type="submit" name="Submit" value="Enviar">
  </p>
  </form>
</body>
</html>
