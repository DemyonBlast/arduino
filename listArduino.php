<!DOCTYPE html>


<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        
    </head>
    <body>
		<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
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
		<div class="listData">
		<?php
			require_once 'SortData.php';
            require_once 'dadosArduino.php';
			
			$sort=new SortData();
			$p1= new dadosArduino();
			
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
				echo "<h4><table>"; //table start
				echo "<tr>";
					echo "<th>Local</th>";
					echo "<th>IDarduino</th>";
					echo "<th>Geolocalização</th>";
					echo "<th>Estado</th>";
				echo "</tr>";
                $results = $link->query($queryArduino);
				//echo $results->num_rows;
	
				$flag=0;
                if ($results->num_rows > 0)
                {

                    while($row = $results->fetch_assoc()) 
                    {			
						$p1->setData($row["id"], $row["geo"], $row["activo"], $row["descrip"]);
						//echo "id: " . $row["id"]. " - Geo: " . $row["geo"]. "-Estado: ".$row["activo"]. "<br>";

						echo '<h4><tr><td>
							<a href="mapDriver.php?IdArduino='.$p1->getID().'">'.$p1->getDescrip().'</a>
							</td><td><a href="mapDriver.php?IdArduino='.$p1->getID().'">'.$p1->getID().'</a>
							</td><td><a href="mapDriver.php?IdArduino='.$p1->getID().'">'.$p1->getGeoreference().'</a> 
							</td><td><a href="mapDriver.php?IdArduino='.$p1->getID().'">'.$p1->getStatus().'</a> </td></tr></h4>';						
						$flag++;
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