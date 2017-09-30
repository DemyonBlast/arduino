

<!DOCTYPE html>


<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        
    </head>
    <body>
		<script src="js/jquery.min.js"></script>
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
            require_once 'Tipo.php';
			
			$sort=new SortData();
			
			$tip=new Tipo();

			$link=$sort->bdServer();
            /* If connection failed */
            if (!$link) {
                printf($messErr_connectionDatabaseFailed);
                printf("<br />");
            }
            /* If connection successed */
            else {
				
				//---------------------------------Tipo Query----------------------------------
                $queryTipo = "SELECT id, tipo FROM Tipo";
                //echo $queryTipo;
				echo "<h4><table>"; //table start
				echo "<tr>";
					echo "<th><h4>Tipo</h4></th>";
					echo "<th><h4>IDtipo</h4></th>";
				echo "</tr>";
                $results = $link->query($queryTipo);
				//echo $results->num_rows;
                if ($results->num_rows > 0)
                {
                    while($row = $results->fetch_assoc()) 
                    {
						$tip->setID($row["id"]);
						$tip->setTipo($row["tipo"]);
						
						echo '<tr><td><a href="tipoSelect.php?tipoSelect='.$tip->getTipo().'">'.$tip->getTipo().'</a>
						</td><td><a href="tipoSelect.php?tipoSelect='.$tip->getTipo().'">'.$tip->getID().'</td></tr>';
						
                    }
                }
				else
                {
                    echo "0 results Tipo de Sensor";
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
			//xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.onreadystatechange = function() {//Call a function when the state changes.
				if(xhttp.readyState == 4 && xhttp.status == 200) {
					//alert(xhttp.responseText);
						window.location.href = "tipoSelect.php";
				}
				xhttp.send();
		</script>
	</body>
</html>