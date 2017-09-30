
<html>
<head>
	<meta charset="UTF-8">
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiMJxOw6tOzlAruwO4vTgu-nNZFtu6PEY&callback=initMap" type="text/javascript"></script>
    <script src="js/gmaps.js"></script>
    <link rel="stylesheet" href="css/map.css">
	<link rel="stylesheet" href="css/form.css">
</head>
<body>

	<nav>
	<ul>
		<li><a href="index.php"><span>Ínicio</span></a></li>
		<li><a href="formMapDriver.php"><span>Localização dos Arduinos</span></a></li>
		<li><a href="listArduino.php"><span>Informação dos Arduinos</span></a></li>
		<li><a href="listSensor.php"><span>Informação dos Sensores</span></a></li>
		<li><a href="listTipo.php"><span>Informação dos Tipos</span></a></li>
		<li><a href="formChartDriver.php"><span>Gráficos</span></a></li>
	</ul>
	</nav>
	<section class="formboard">
		<h3>Insira o Id do arduíno (1...100) que deseja visualizar para visualizar a sua localização.</h3>
		<div id="form">
			<form action="mapDriver.php" method="post">
				<input type="text" name="IdArduino" placeholder="Id do Arduino" list=arduinos>
				<input type="submit" value="Concluir">
				<datalist id=arduinos>
					<?php
						require_once 'SortData.php';
						$sort=new SortData();
						
						$link=$sort->bdServer();
						
						/* If connection failed */
						if (!$link) {
							printf($messErr_connectionDatabaseFailed);
							printf("<br />");
						}
						/* If connection successed */
						else {
							
							//----------------------------------Arduino Query------------------------------
							$queryArduino = "SELECT id, descrip, geo, activo FROM Arduino";
							//echo $queryArduino;

							$results = $link->query($queryArduino);
							//echo $results->num_rows;
							if ($results->num_rows > 0)
							{

								while($row = $results->fetch_assoc()) 
								{			
									echo "<option>".$row["id"]."</option>";
								}
							}
							else
							{
								echo "0 results arduino";
							}
						}
					?>
			  </datalist>
			</form>
			
		</div>
		
	</section>
	<div id="map" align='right'></div>
	<div id="info" align='left'>
	<?php
		require_once 'dadosArduino.php';
		require_once 'SortData.php';
		require_once 'Sensors.php';
		
		$p1= new dadosArduino();
		$data= new SortData();
		$sensor=new Sensors();
		
		$link=$data->bdServer();

		/* If connection failed */
		if (!$link) {
			printf($messErr_connectionDatabaseFailed);
			printf("<br />");
		}
		/* If connection successed */
		else {
			
			//----------------------------------Arduino Query------------------------------
			$queryArduino = "SELECT id, descrip, geo, activo FROM Arduino";
			//echo $queryArduino;
			$arrayCoords=[];
			$arrayStatus=[];
			$arrayArduino_Id=[];
			$arrayDescrip=[];
			$count=0;
			$results = $link->query($queryArduino);
			//echo $results->num_rows;
			if ($results->num_rows > 0)
			{

				while($row = $results->fetch_assoc()) 
				{			
					$p1->setData($row["id"], $row["geo"], $row["activo"], $row["descrip"]);
					//echo "id: " . $row["id"]. " - Geo: " . $row["geo"]. "-Estado: ".$row["activo"]. "<br>";
					echo "<h4>IDarduino:".$p1->getID(). "<br>Geo:".$p1->getGeoreference()."<br>Local: ".$p1->getDescrip()."<br>Estado:".$p1->getStatus()."</h4>";

					$arrayCoords[$count]=$coordenadas=$data->coords($p1->getGeoreference());
					//echo $arrayCoords[$count][0];
					//echo $arrayCoords[$count][1];
					$arrayStatus[$count]=$status=$p1->getStatus();
					//echo $arrayStatus[$count];
					$arrayArduino_Id[$count]=$arduino_Id=$p1->getID();
					//echo $arrayArduino_Id[$count];
					$arrayDescrip[$count]=$description=$p1->getDescrip();
					//echo $arrayDescrip[$count];
					$count++;
				}
			}
			else
			{
				echo "0 results arduino";
			}
		}

		
	?>
	</div>
	<script type="text/javascript">
		document.addEventListener("DOMContentLoaded", function(event) {   //Forces the page to load the javascript, otherwise we have to refresh it manually
			var coords = <?php echo json_encode($arrayCoords, JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable
			var status = <?php echo json_encode($arrayStatus, JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable
			var arduino_Id = <?php echo json_encode($arrayArduino_Id, JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable
			var description = <?php echo json_encode($arrayDescrip, JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable

			//document.write(description); //for testing purposes

			var mapObj =new GMaps({
				div: '#map',
				zoom: 12,
				center: {lat: parseFloat(coords[1][0]), lng: parseFloat(coords[1][1])} //map longitute
				//map latitute
				
			});
			for (i = 0; i < arduino_Id.length; i++) { 
				if(status[i]=="0")
					var arduino1 = mapObj.addMarker({
						lat: parseFloat(coords[i][0]),//marker latitute location
						lng: parseFloat(coords[i][1]),//marker latitute location
						icon: "img/3DRedMarkerMini.png",
						title: 'Arduino',
						infoWindow: {
						  content: '<h2>Arduino '+arduino_Id[i]+'</h2><div>'+description[i]+'</div>', //description of the marker
						  
						  maxWidth: 100
						}
					});	
				else
					var arduino1 = mapObj.addMarker({
						lat: parseFloat(coords[i][0]),//marker latitute location
						lng: parseFloat(coords[i][1]),//marker latitute location
						icon: "img/3DGreenMarkerMini.png",
						title: 'Arduino'+arduino_Id[i],
						infoWindow: {
						  content: '<h2>Arduino '+arduino_Id[i]+'</h2><div>'+description[i]+'</div>', //description of the marker
						  maxWidth: 100
						}
					});						
				
				var area1 = mapObj.drawCircle({ //area of the sensor range
					lat: parseFloat(coords[i][0]),//area latitute location
					lng: parseFloat(coords[i][1]),//area latitute location
					radius: 300,
					fillColor: 'yellow',
					fillOpacity: 0.5,
					strokeWeight: 0,
					click: function(e){
					  alert('Está dentro dos 300m do sensor!');
					}
				});
			}
			
		});
	</script>	
</body>
</html>