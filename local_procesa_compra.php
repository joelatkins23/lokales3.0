<?
	include("conex.php");
	//Variable que nos dice si hay que cargar la venta o no
	$cargar_compra="no";
	//session_start();
	include("local_controla2.php");		
	//Boton agregar
	if(isset($_POST['agregar']) or isset($_POST['terminar']))
	{
		//Limpio la tabla temporal
		mysql_query("DELETE FROM compra_temporal",$link);
		$_SESSION['total_compra']=0;
		for($i=0;$i<$_SESSION['cant_items'];$i++)
		{
			$cod_producto=$_POST['producto'][$i];
			$cantidad=$_POST['cant'][$i];
			if($_POST['costo'][$i]<>0)
			{
				$costo=$_POST['costo'][$i];			
			}
			else
			{
				//Totamos el costo actual si quedo vacio
				$productos=mysql_query("SELECT * FROM productos WHERE cod='$cod_producto'",$link);
				$producto=mysql_fetch_array($productos);
				$costo=$producto['costo'];
			}
			$fecha=$_POST['anio']."-".$_POST['mes']."-".$_POST['dia'];
			//Guardamos la fecha para no perderla y el proveedor
			$_SESSION['dia']=$_POST['dia'];
			$_SESSION['mes']=$_POST['mes'];
			$_SESSION['anio']=$_POST['anio'];
			$_SESSION['id_proveedor']=$_POST['proveedor'];
			if($cantidad<>"")
			{
				$cargar_compra="si";
				mysql_query("INSERT INTO compra_temporal (cod_producto, cantidad, costo) VALUES ('$cod_producto','$cantidad','$costo')",$link);
			}
		}
		//Calculamos el total
		$compras=mysql_query("SELECT * FROM compra_temporal",$link);
		while($compra=mysql_fetch_array($compras))
		{
			$_SESSION['total_compra']=$_SESSION['total_compra'] + $compra['cantidad']*$compra['costo'];		
		}		
		header("Location:local_compra.php");
	}	
	if(isset($_POST['terminar']) and $cargar_compra=="si")
	{
		unset($_SESSION['dia']);
		unset($_SESSION['mes']);
		unset($_SESSION['anio']);
		unset($_SESSION['id_proveedor']);
		$id_usuario=$_SESSION['usuario_act'];
		//Obtiene la fecha y hora
		//$array_fecha=getdate();
		//$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
		//$hora=strval($array_fecha['hours']).":".strval($array_fecha['minutes']);
		$id_proveedor=$_POST['proveedor'];
		//Creamos el registro en la tabla ventas
		if(mysql_query("INSERT INTO compra (id_usuario, fecha, id_proveedor) VALUES ('$id_usuario', '$fecha', '$id_proveedor')",$link))
		{
			$id_compra=mysql_insert_id($link);
			$error=0;
			//Cargamos el detalle de la venta
			$temporales=mysql_query("SELECT * FROM compra_temporal",$link);
			while($temporal=mysql_fetch_array($temporales))
			{
				if($temporal['cantidad']>0)
				{
					$cod_producto=$temporal['cod_producto'];
					$cantidad=$temporal['cantidad'];
					$costo=$temporal['costo'];
					/*//Buscamos y calculamos el precio del producto
					$productos=mysql_query("SELECT * FROM productos, categorias WHERE categorias.cod=cod_cat AND productos.cod='$cod_producto'",$link);
					$producto=mysql_fetch_array($productos);
					$precio=$producto['costo']*$producto['margen'];*/
					if(!mysql_query("INSERT INTO compra_detalle (id_compra, cod_producto, cantidad, costo) VALUES ('$id_compra', '$cod_producto', '$cantidad', '$costo')",$link))
					{
						echo mysql_error($link);
					}
					//Actualizamos stock
					mysql_query("UPDATE productos SET stock=stock+'$cantidad', costo='$costo' WHERE cod='$cod_producto'",$link);
					//Borramos la tabla temporal
					mysql_query("DELETE FROM compra_temporal",$link);
					//Volvemos a ventas
					header("Location:local_compra.php");
				}
			}
		}
		else
		{
			$error=3;
		}
		
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<?
	if($error==0)
	{
		echo "La compra se cargo correctamente.";
	}	
	if($error==3)
	{
		echo "Error de base de datos: ".mysql_error($link);
	}		
?>
</body>
</html>
