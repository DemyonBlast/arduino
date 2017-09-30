<!DOCTYPE html>


<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        
		<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
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
               
			   $querySensor = "select sensor.id, sensor.tipoID_fk, sensor.id_arduino_fk, sensor.valores, Arduino.descrip, Tipo.tipo from sensor INNER JOIN Arduino ON sensor.id_arduino_fk=Arduino.id INNER JOIN Tipo ON sensor.tipoID_fk=Tipo.id";

				//echo $querySensor;
				echo "<h4><table>"; //table start
				echo "<tr>";
					echo "<th>Valores</th>";
					echo "<th>Tipo</th>";
					echo "<th>IDsensor</th>";
					echo "<th>Localização do Arduino </th>";
				echo "</tr>";
                $results = $link->query($querySensor);
				//echo $results->num_rows;
                if ($results->num_rows > 0)
                {
                    while($row = $results->fetch_assoc())
                    {
						echo '<tr><td>'.$row['valores'].' </td><td><a href="tipoSelect.php?tipoSelect='.$row['tipo']. '">'.$row['tipo'].'</a>
						</td><td>'.$row['id'].'</td><td>'.$row['descrip'].'</td></tr>';
						
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
			function ajax(tipo){
				var xhttp = new XMLHttpRequest();
					str = "tipoSelect.php?tipoSelect="+tipo;
					xhttp.open("GET", str, true);
					xhttp.onreadystatechange = function() {//Call a function when the state changes.
					if(xhttp.readyState == 4 && xhttp.status == 200) {
							window.location.href = "tipoSelect.php";
					}
					xhttp.send();
		</script>
		
		
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
		
	</body>
</html>