<?php
	
	class Conecta
	{
		public static function Con()
		{
			$con = mysql_connect("mistablas.db.11435666.hostedresource.com","mistablas","ContrSeg27#");
			if(!$con)
			{
				//header("Location: include/error_bd.php");	
			}
			mysql_query("SET NAMES 'utf8'"); //para que funcionen los caracteres latinos
			mysql_select_db("mistablas");
			return $con;
		}
	}
	
?>