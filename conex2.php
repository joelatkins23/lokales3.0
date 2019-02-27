<?php
//----------------------------------------------------------------------------------------
//Conex.php
//Provee una funcion para que los programas se conecten al servidor de base de datos MySQL y
//detecta si se produce un error en la conexcion con el servidor o con la base de datos en si.
//--------------------------------------------------------------------------------------------*/
//error_reporting(E_ERROR | E_WARNING | E_PARSE);//para sacar de la pantalla los reportes de errores
function conectarse($host, $usr, $pass, $db)
{
	if(!($link=@mysql_connect($host, $usr, $pass))) // el @ oculta solo el DEPRECATED
	{
		echo "Error: No se pudo establecer la conexión con el servidor de base de datos";
		exit();
	}
	if(!mysql_select_db($db, $link)) 
	{
		echo "Error: No se pudo establecer la conexión con la base de datos";
		exit();

	}
	return $link; 
}
//$link=conectarse("localhost","root","","lokalespark");//LOCAL
$link=conectarse("lokalespark.db.5947511.hostedresource.com","lokalespark","SK8lokales","lokalespark");//REMOTO
?>