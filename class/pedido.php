<?php
	class Pedido
	{
		//atributos clase pedido
		private $mens=array();
		private $link;
		
		function __construct() {
       		//include_once("Conecta.php");
	   		$this->link=conecta::Con();
   		}
	
		//metodo que devuelve nick y nivel del usuario accediendo
		public function Nick_Nivel($nombre)
		{
			$sql="SELECT * FROM artycla_lista WHERE usuario='$nombre'";
			$result=mysql_query($sql,conecta::Con());
			$filaUsuario   = mysql_fetch_array($result);		
	        $nivel = $filaUsuario["nivel"];
	        $nick = $filaUsuario["nick"];
			return array($nivel, $nick);  
		}
		
		//metodo que cambia el estado de rojo a verde o viceversa
		public function Cambio_Estado($id)
		{
			$sql="select * FROM artycla_pedido WHERE id='$id'";
			$result=mysql_query($sql,conecta::Con());
			$filapedido = mysql_fetch_array($result);
			$estado = $filapedido["estado"];
			 if ($estado == "R"){
				 $est = "V";				  
		      }
			 
		   if ($estado == "V"){
			   $est = "R";
		   }
		   $sql="UPDATE artycla_pedido SET estado='$est' WHERE id='$id'";
		   $result=mysql_query($sql,conecta::Con());  		
	       
			return $result;
		}
		
		
		//metodo para grabar un nuevo pedido en la bd
		public function Insertar_Pedido($nombre, $obra, $urgencia, $pedido, $fecha_pedido, $fecha_urgencia, $estado)
			{
			$sql="INSERT INTO artycla_pedido (nombre,obra,urgencia,pedido,fecha_pedido,fecha_urgencia,estado) VALUES ('$nombre', '$obra', '$urgencia', '$pedido', '$fecha_pedido', '$fecha_urgencia', '$estado')";			
			$result=mysql_query($sql,conecta::Con());
			return $result;
		}
		
		
			//metodo que devuelve todos los pedidos por filtro (por obra, por urgencia o todos)
		public function Dame_Pedidos($filtro, $fecha_urgencia)
		{
			if ($filtro == "por_obra"){
				$sql = "SELECT * FROM artycla_pedido where obra='$obra' order by fecha_urgencia ASC";
			}
			if ($filtro == "por_urgencia"){
				$sql ="SELECT * FROM artycla_pedido where fecha_urgencia='$fecha_urgencia' order by obra ASC";
			}
			if ($filtro == "todo"){
				$sql ="SELECT * FROM artycla_pedido order by fecha_urgencia ASC";
			}
			$result=mysql_query($sql,conecta::Con()); 
			return $result; 
		}
		
			
		//metodos clase pedido que devuelve todos los pedidos de un usuario determinado
		public function Dame_Pedido_Usuario($nombre) 
		{
			
			$sql="SELECT * FROM artycla_pedido where nombre='$nombre' order by fecha_urgencia ASC";
			$result=mysql_query($sql,conecta::Con());
			return $result; 
		}
		
		//metodo para borrar comentario en la bd
		public function Borrar_Pedido($id)
		{
			$sql="DELETE FROM artycla_pedido WHERE id='$id'";
			$result=mysql_query($sql,conecta::Con());
			return $result;
		}
	}
?>
