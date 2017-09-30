

<?php
	$IdArduino = $_REQUEST['IdArduino'];

?>


<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        
    </head>
    <body>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiMJxOw6tOzlAruwO4vTgu-nNZFtu6PEY&callback=initMap" type="text/javascript"></script>
        <script src="js/gmaps.js"></script>
        <link rel="stylesheet" href="css/map.css">
		<link rel="stylesheet" href="css/form.css">
		

		<nav>
		<ul>
			<li><a href="index.php"><span> Ínicio</span></a></li>
			<li><a href="formMapDriver.php"><span>Localização dos Arduinos</span></a></li>
			<li><a href="listArduino.php"><span>Informação dos Arduinos</span></a></li>
			<li><a href="listSensor.php"><span>Informação dos Sensores</span></a></li>
			<li><a href="listTipo.php"><span>Informação dos Tipos</span></a></li>
			<li><a href="formChartDriver.php"><span>Gráficos</span></a></li>
		</ul>
		</nav>
		
        <div id="map"></div>
        <?php

            require_once 'dadosArduino.php';
            require_once 'SortData.php';
			require_once 'Tipo.php';
			require_once 'Sensors.php';
			
            $p1= new dadosArduino();
			$data= new SortData();
			$tip=new Tipo();
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
                $queryArduino = "SELECT id, descrip, geo, activo FROM Arduino WHERE id=".$IdArduino;
                //echo $queryArduino;

                $results = $link->query($queryArduino);
				//echo $results->num_rows;
                if ($results->num_rows > 0)
                {

                    while($row = $results->fetch_assoc()) 
                    {			
						$p1->setData($row["id"], $row["geo"], $row["activo"], $row["descrip"]);
						//echo "id: " . $row["id"]. " - Geo: " . $row["geo"]. "-Estado: ".$row["activo"]. "<br>";
						echo "<h4>IDarduino:".$p1->getID(). "<br>Geo:".$p1->getGeoreference()."<br>Local: ".$p1->getDescrip()."<br>Estado:".$p1->getStatus()."</h4>";
                    }
                }
				else
                {
                    echo "0 results arduino";
                }
				//---------------------------------Tipo Query----------------------------------
                $queryTipo = "SELECT id, tipo FROM Tipo";
                //echo $queryTipo;

                $results = $link->query($queryTipo);
				//echo $results->num_rows;
                if ($results->num_rows > 0)
                {
                    while($row = $results->fetch_assoc()) 
                    {
						$tip->setID($row["id"]);
						$tip->setTipo($row["tipo"]);
						
						//echo "IDtipo:".$tip->getID(). "- tipo:".$tip->getTipo()."<br>";
                    }
                }
				else
                {
                    echo "0 results Tipo";
                }
                
                
               // echo $p1->getGeoreference().' after sortData <br />';
                $coordenadas=$data->coords($p1->getGeoreference()); //obtém um array com a Lat e a Long
                $status=$p1->getStatus();
				$arduino_Id=$p1->getID();
				$description=$p1->getDescrip();
				//echo $description;
				//echo $status;
              // echo 'Latitude: '.$coordenadas[0].'<br />';//latitude
              // echo 'Longitude: '.$coordenadas[1].'<br />';//longitude
            }

            
        ?>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function(event) {   //Forces the page to load the javascript, otherwise we have to refresh it manually
				var coords = <?php echo json_encode($coordenadas, JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable
				var status = <?php echo json_encode($status, JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable
				var arduino_Id = <?php echo json_encode($arduino_Id, JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable
				var description = <?php echo json_encode($description, JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable

				//document.write(description); //for testing purposes

				var mapObj =new GMaps({
					div: '#map',
					lat: parseFloat(coords[0]),//map latitute
					lng: parseFloat(coords[1]) //map longitute
				});
				
				if(status=="0")
					var arduino1 = mapObj.addMarker({
						lat: parseFloat(coords[0]),//marker latitute location
						lng: parseFloat(coords[1]),//marker latitute location
						icon: "img/3DRedMarkerMini.png",
						title: 'Arduino',
						infoWindow: {
						  content: 'Arduino '+arduino_Id+'<div>Silves, Cerro S. Miguel</div>', //description of the marker
						  maxWidth: 100
						}
					});	
				else
					var arduino1 = mapObj.addMarker({
						lat: parseFloat(coords[0]),//marker latitute location
						lng: parseFloat(coords[1]),//marker latitute location
						icon: "img/3DGreenMarkerMini.png",
						title: 'Arduino'+arduino_Id,
						infoWindow: {
						  content: '<h2>Arduino '+arduino_Id+'</h2><div>'+description+'</div>', //description of the marker
						  maxWidth: 100
						}
					});						
				
				var area1 = mapObj.drawCircle({ //area of the sensor range
					lat: parseFloat(coords[0]),//area latitute location
					lng: parseFloat(coords[1]),//area latitute location
					radius: 300,
					fillColor: 'yellow',
					fillOpacity: 0.5,
					strokeWeight: 0,
					click: function(e){
					  alert('Está dentro dos 300m do sensor!');
					}
				});
			});
        </script>
    </body>
        
</html>
