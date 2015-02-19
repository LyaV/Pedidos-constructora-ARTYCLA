<?php session_start();?>
<!DOCTYPE html>
<html lang="es-ES">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Pedido</title>

<link rel="stylesheet" type="text/css" href="css/estilopedido.css" media="screen"/>

<link rel="shortcut icon" type="image/x-icon" href="imagenes/favicon.JPG" /> <!--para favicon-->
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
</head>

<body>
 <?php
 date_default_timezone_set("Europe/Madrid");

 include_once("class/Pedido.php");
 include_once("class/Conecta.php");
 $pedido= new Pedido();
?>
<div class="cont_pedido_0">

<img src="content-images/logo_artycla_peque.png" />

<?php
//Para acceso de Usuario
$paginaret = "pedido_artycla.php";
include("include/opensessionpedido.php");


 $_SESSION["paginaret"] = $paginaret;
if( !isset ($_SESSION["sessionInic"])){
	$_SESSION["sess"] = "no existe sesionInic esto es dif";
	echo "<meta http-equiv=Refresh content=\"0 ; url=pedido.php\">"; 
	//header ("location: bd.php");
	}


else{
	if ($_SESSION["sessionInic"]=="No"){
		$_SESSION["sess"] = "si existe sesionInic y es No";
		echo "<meta http-equiv=Refresh content=\"0 ; url=pedido.php\">"; 
		//header ("location: bd.php");
		}
}
//Para acceso de Usuario

//Si hay post, puede ser para: "eliminar pedido", "pedido estado a rojo", "pedido estado a verde" o insertar nuevo pedido
     if ($_POST){ 
	     $enviar = $_POST["enviar"]; 
		  
	     //Eliminar pedido
		 if ($enviar == "eliminar"){
			 $id = $_POST["id"];
			 $result=$pedido->Borrar_Pedido($id);	 		 		   
		 }//end if ($enviar == "eliminar")
		 
		 //Cambiar estado pedido
		 if ($enviar == "R/V"){		   
		     $id = $_POST["id"]; 			 
			 $result=$pedido->Cambio_Estado($id);					   
		 }//end if ($enviar == "R/V")
		 
		 //Insertar nuevo pedido
		 if ($enviar == "ENVIAR"){	 
	     //Estoy recibiendo el pedido compongo información para BD 
		  $nombre = $_POST["nombre"]; 
		  $obra = $_POST["obra"]; 
		  $urgencia = $_POST["urgencia"]; 
		  $ped = $_POST["pedido"]; 
		  $fecha_pedido = date('Y-m-j-H-i');
		  $estado = "R";		  		  
		  $fecha_urgencia = strtotime ( $urgencia , strtotime ( $fecha_pedido ) ) ;
		  $fecha_urgencia = date ( 'Y-m-j' , $fecha_urgencia );		  
		  
		  //inserto nuevo registro de pedido en la bd 
		 $result=$pedido->Insertar_Pedido($nombre, $obra, $urgencia, $ped, $fecha_pedido, $fecha_urgencia, $estado);
	      } // end if ($enviar == "ENVIAR")
		  
	 } // end if ($_POST)
   
	
	// Nick y nivel del usuario accediendo. Todo queda en $ninel y $nick);
	 $nombre = $_SESSION["Usuario"];
	 $result=$pedido->Nick_Nivel($nombre);	
	 $nick = $result[1];
	 $nivel = $result[0];
	?>
    
   
 <br/><hr />
 
 
 
 <b>Pedidos de<?php echo ": " . $nick .""; 
 
  ?> </b>
 <br/><br/>
 
 <div class="cont_pedido_1"> 
 
   <form action="con-clase.php" method="post"> 

       <input name="nombre" type="hidden" value="<?php echo $nombre; ?>" />
       
         <label for="obra">Obra</label><br />
        <select name="obra">
          <option value="mitre" selected>Mitre: &darr;</option>
          <option value="roger">Roger</option>
        </select>
         
        <br /><br />
       
        <label for="urgencia">Urgencia:</label><br />
        <select name="urgencia">
          <option value="+1 days" selected>mañana  &darr;</option>
          <option value="+2 days">2 días</option>
          <option value="+3 days">3 días</option>
          <option value="+4 days">4 días</option>
          <option value="+5 days">5 días</option>
        </select>
        
        <p><label for="pedido">Pedido:<br /></label>
        <textarea name="pedido" cols="24" rows="5"></textarea></p>
        
        <p><input type="submit" name="enviar" id="formulario_boton" value="ENVIAR"/>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
        <input type="reset" id="txtimg" value="Borrar Formulario"></p>
      
    <br />
    
  </form>
  
 <?php
 //Mostrando pedidos, 
 //si el nivel de usuario es 0, se muestran todos los pedidos
 //si el nivel de usuario es 0, puede filtrar los pedidos por "obra"
 //si el nivel de usuario es 0, puede filtrar los pedidos por "Urgencia"
 
  if ($nivel == 0){
	  echo "
	   FILTRAR POR...<br />
	  <div class='cont_pedido_4'>
	  <br />
	   <form action='con-clase.php' method='post'> 
       
        <label for='filtro obra'>Obra:</label><br />
        <select name='obra'>
          <option value='mitre' selected>Mitre  &darr;</option>
          <option value='roger'>Roger</option>
        </select>
        
        <input type='submit' name='enviar' value='por obra'/>
       
    <br /><br />
    
  </form>
  
   <form action='con-clase.php' method='post'> 
       
        <label for='filtro obra'>Urgencia:</label><br />
        <select name='urgencia'>
          <option value='+1 days' selected>mañana  &darr;</option>
          <option value='+2 days'>2 días</option>
          <option value='+3 days'>3 días</option>
          <option value='+4 days'>4 días</option>
          <option value='+5 days'>5 días</option>
        </select>
        
        <input type='submit' name='enviar' value='por dia'/>
    
  </form>
  
  <form action='con-clase.php' method='post'> 
        <input type='submit' name='enviar' value='todo'/>
       
    <br /><br />
    
  </form>
  
  
	  </div><!--cont_pedido_4 --> <br />
	  <br />
	  "; 
	  
	  $filtro = "todo";
      $fecha_urgencia = "";
	  //si filtra...
	  if ($_POST){
	   //si fitró por obra:	   
	   if ($enviar == "por obra"){
	       $obra = $_POST["obra"]; 
		   $filtro = "por_obra";
		   $fecha_urgencia = "";
		   echo $obra;
	   }
	   
	    if ($enviar == "por dia"){
			//si filtró por día de urgencia:	
		   $urgencia1 = $_POST["urgencia"]; 
		   $fecha_pedido = date('Y-m-j');
		   $fecha_urgencia = strtotime ( $urgencia1 , strtotime ( $fecha_pedido ) ) ;
		   $fecha_urgencia = date ( 'Y-m-j' , $fecha_urgencia );
		   $filtro = "por_urgencia";
	   }
	   
	  if ($enviar == "todo"){
		     //si se quieren mostrar todos los pedidos:
			 $filtro = "todo";
			 $fecha_urgencia = "";	
	  }
	  $result=$pedido->Dame_Pedidos($filtro, $fecha_urgencia);	
	  }/* if ($_POST)*/
	  //si no filtra muestra todo
	  else {
		  $filtro = "todo";
		  $fecha_urgencia = "";
		  $result=$pedido->Dame_Pedidos($filtro, $fecha_urgencia);		
		  }
		   	
	  
	 
	  //Obtengo la información de la BD
	  while ($filaPedido = mysql_fetch_array($result)) {
	  $id = $filaPedido["id"];
	  $nombre = $filaPedido["nombre"];
	  $obra = $filaPedido["obra"];
	  $urgencia = $filaPedido["urgencia"];
	  $pedido = $filaPedido["pedido"];
	  $fecha_pedido = $filaPedido["fecha_pedido"];
	  $fecha_pedido=date("d/m/Y-H:i",strtotime($fecha_pedido)); /*cambia fecha a formato europeo*/
	  $fecha_urgencia = $filaPedido["fecha_urgencia"];
	  $fecha_urgencia=date("d/m/Y",strtotime($fecha_urgencia)); /*cambia fecha a formato europeo*/
	  $estado = $filaPedido["estado"];
	  if ($estado == "R"){
		  //defino estilo css
		  $cont = "cont_pedido_rojo";
		  }
	  else{	
	      $cont = "cont_pedido_verde";
	  }  
		
		
		
		  
	 //Muestro los pedidos
	  echo "
     
	    <div class='cont_pedido_3'> 
	         <div class='cont_pedido_2'> "
			    . $nombre .
			"  | </div><!--cont_pedido_2 --> 
			
			<div class='cont_pedido_2'>"
			    . $obra .
			"  |  </div><!--cont_pedido_2 --> 
			
			<div class='cont_pedido_2'>"
			    . $urgencia .
			"   | </div><!--cont_pedido_2 --> 
			
			<div class='cont_pedido_2'> el  "
			    . $fecha_pedido .
			"   | </div><!--cont_pedido_2 --> 
			
			<div class='cont_pedido_2'> para <b>  "
			    . $fecha_urgencia .
			" </b> </div><!--cont_pedido_2 --> 
			
		
		
			 <div class='cont_pedido_4'>
				 <div class='". $cont ."'> "
					. $pedido .
				" </div><!--cont_pedido_R_V -->
			 </div><!--cont_pedido_4 -->  
			
			 <div class='cont_pedido_5'>
				 <form action='con-clase.php' method='post'> 
				   <input name='id' type='hidden' value='" . $id . "' />				
				   <p><input type='submit' name='enviar' value='eliminar'/></p>
				 </form>
			 </div><!--cont_pedido_5 -->
			 
			 <div class='cont_pedido_6'>
				 <form action='con-clase.php' method='post'> 
				   <input name='id' type='hidden' value='" . $id . "' />			
				   <p><input type='submit' name='enviar' value='R/V'/></p>
				 </form>
			 </div><!--cont_pedido_6 -->
			
	 </div><!--cont_pedido_3 -->
	    
	  ";
	  }
  }
  else { //si nivel no es cero, solo se muestran los pedidos de ese usuario sin opciones de filtro
	 
	   $result=$pedido->Dame_Pedido_Usuario($nombre);		
	  
	  while ($filaPedido = mysql_fetch_array($result)) {
			  $id = $filaPedido["id"];
			  $nombre = $filaPedido["nombre"];
			  $obra = $filaPedido["obra"];
			  $urgencia = $filaPedido["urgencia"];
			  $pedido = $filaPedido["pedido"];
			  $fecha_pedido = $filaPedido["fecha_pedido"];
			  $fecha_pedido=date("d/m/Y-H:i",strtotime($fecha_pedido)); /*cambia fecha a formato europeo*/
			  $fecha_urgencia = $filaPedido["fecha_urgencia"];
			  $fecha_urgencia=date("d/m/Y",strtotime($fecha_urgencia)); /*cambia fecha a formato europeo*/
			  $estado = $filaPedido["estado"];
			  if ($estado == "R"){
				  $cont = "cont_pedido_rojo";
				  }
			  else{	
				  $cont = "cont_pedido_verde";
			  }  
				  
			  echo "
					 
			  <div class='cont_pedido_3'> 
					 <div class='cont_pedido_2'> "
						. $nombre .
					"  | </div><!--cont_pedido_2 --> 
					
					<div class='cont_pedido_2'>"
						. $obra .
					"  |  </div><!--cont_pedido_2 --> 
					
					<div class='cont_pedido_2'>"
						. $urgencia .
					"   | </div><!--cont_pedido_2 --> 
					
					<div class='cont_pedido_2'> el  "
						. $fecha_pedido .
					"   | </div><!--cont_pedido_2 --> 
					
					<div class='cont_pedido_2'> para <b>  "
						. $fecha_urgencia .
					" </b> </div><!--cont_pedido_2 --> 
					
				
				
					 <br/><br/>
					 <div class='". $cont ."'> "
						. $pedido .
					" </div><!--cont_pedido_R_V --> 
					
					 <div class='cont_pedido_5'>
						 <form action='con-clase.php' method='post'> 
							 <input name='id' type='hidden' value='" . $id . "' />				
							 <p><input type='submit' name='enviar' value='eliminar'/></p>
						 </form>
					 </div><!--cont_pedido_5 -->
					
			</div><!--cont_pedido_3 -->
			  
			  ";
			  } // end while ($filaPedido = mysql_fetch_array($resultPedido))
	  }//end  else { //si nivel no es cero,......

  ?>
	
    
  </div><!--cont_pedido_1 --> 
 
 </div><!--cont_pedido_0 --> 
 
 
</body>
</html>