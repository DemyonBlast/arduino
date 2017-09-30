

<?php
	$tipoSelect = $_REQUEST['tipoSelect'];
?>

<!DOCTYPE html>


<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        
		<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="css/form.css">
    </head>
    <body>
		
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
		
		<div class="listData">
		<?php
                      /* database connection information */
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
				
				//---------------------------------Sensor Query----------------------------------
               $querySensor = "SELECT sensor.id, sensor.id_arduino_fk, sensor.valores, tipo.tipo FROM sensor INNER JOIN Tipo ON sensor.tipoID_fk=Tipo.id where tipo like'%".$tipoSelect."%'";
				//echo $querySensor;
				echo "<h4><table>"; //table start
				echo "<tr>";
					echo "<th>Tipo</th>";
					echo "<th>SensorId</th>";
					echo "<th>ArduinoId</th>";
					echo "<th>Valores</th>";
				echo "</tr>";
                $results = $link->query($querySensor);
				//echo $results->num_rows;
                if ($results->num_rows > 0)
                {
                    while($row = $results->fetch_assoc())
                    {
						echo '<tr><td>'.$row['tipo'].'</a> </td><td>'.$row['id']. '</td><td>'.$row['id_arduino_fk'].'</td><td>'.$row['valores'].'</td></tr>';
						
                    }
                }
				else
                {
                    echo "0 results arduino";
                }
				echo "</table></h4>"; //end table
			}
		?>
		</div>
		<script type="text/javascript">
			var time = new Date().getTime();
			$(document.body).bind("mousemove keypress", function(e) {
				time = new Date().getTime();
			});

			function refresh() {
				if(new Date().getTime() - time >= 10000) 
					window.location.reload(true);
				else 
					setTimeout(refresh, 1000);
			}

			setTimeout(refresh, 1000);

		</script>
		<script type="text/javascript">

		function ajax(id){
			var xhttp = new XMLHttpRequest();
				str = "mapDriver.php?IdArduino="+id;
				xhttp.open("GET", str, true);
				xhttp.onreadystatechange = function() {//Call a function when the state changes.
				if(xhttp.readyState == 4 && xhttp.status == 200) {
						window.location.href = "mapDriver.php";
				}
				xhttp.send();
		</script>
	</body>
</html>