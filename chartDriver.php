

<?php
	$nomeTipoSensor = $_REQUEST['TipoSensor'];
	$dataDia = $_REQUEST['dataDia'];
	$arduinoId_Nome = $_REQUEST['arduinoId_Nome'];
	
	$nomeTipoSensor2 = $_REQUEST['TipoSensor2'];
	$dataDia2 = $_REQUEST['dataDia2'];
	$arduinoId_Nome2 = $_REQUEST['arduinoId_Nome2'];
	
	$nomeTipoSensor3 = $_REQUEST['TipoSensor3'];
	$dataDia3 = $_REQUEST['dataDia3'];
	$arduinoId_Nome3 = $_REQUEST['arduinoId_Nome3'];
	
	$nomeTipoSensor4 = $_REQUEST['TipoSensor4'];
	$dataDia4 = $_REQUEST['dataDia4'];
	$arduinoId_Nome4 = $_REQUEST['arduinoId_Nome4'];

	//echo "request".$_REQUEST['TipoSensor']."request";
	//echo "request".$$_REQUEST['TipoSensor2']."request";
	//echo $nomeTipoSensor3;
	//echo $nomeTipoSensor4;
?>

<html>
  <head>
    <!--Load the AJAX API-->
	<script src="js/jquery.min.js"></script>
	<link rel="stylesheet" href="css/form.css">
	<script src="js/Chart.min.js"></script>
	<!--<script src="js/chart.js"></script>-->
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
		<?php
			// If no spaces filled in, loads the same page
			if(!$_REQUEST['TipoSensor'] && !$_REQUEST['dataDia'] && !$_REQUEST['arduinoId_Nome'] && !$_REQUEST['TipoSensor2'] && !$_REQUEST['dataDia2'] && !$_REQUEST['arduinoId_Nome2'] && !$_REQUEST['TipoSensor3'] && !$_REQUEST['dataDia3'] && !$_REQUEST['arduinoId_Nome3'] && !$_REQUEST['TipoSensor4'] && !$_REQUEST['dataDia4'] && !$_REQUEST['arduinoId_Nome4'] )
			{
				header('Location: formChartDriver.php');
			}
			echo '<div class="charts">';
				if($_REQUEST['TipoSensor'] && $_REQUEST['dataDia'] && $_REQUEST['arduinoId_Nome']) //if line not filled in, does not show this graph
				{
					echo '<div class="chart1">
						<canvas id="Chart1" ></canvas>
					</div>';
				}
				if($_REQUEST['TipoSensor2'] && $_REQUEST['dataDia2'] && $_REQUEST['arduinoId_Nome2'])//if line not filled in, does not show this graph
				{
					echo '<div class="chart2">
						<canvas id="Chart2" ></canvas>
					</div>';
				}
				if($_REQUEST['TipoSensor3'] && $_REQUEST['dataDia3'] && $_REQUEST['arduinoId_Nome3'])//if line not filled in, does not show this graph
				{
					echo '<div class="chart3">
						<canvas id="Chart3" ></canvas>
					</div>';
				}
				if($_REQUEST['TipoSensor4'] && $_REQUEST['dataDia4'] && $_REQUEST['arduinoId_Nome4'])//if line not filled in, does not show this graph
				{
					echo '<div class="chart4">
						<canvas id="Chart4" ></canvas>
					</div>';
				}
			echo '</div>';
		
			require_once 'Tipo.php';
			require_once 'Sensors.php';
			require_once 'SortData.php';
			
			$tip=new Tipo();
			$sensor=new Sensors();
			$sort=new SortData();
			
			$link=$sort->bdServer();
			
            /* If connection failed */ //Chart
			//---------------------------------Tipo Query values----------------------------------
			if (!$link) {
                printf($messErr_connectionDatabaseFailed);
                printf("<br />");
            }
            /* If connection successed */
            else {

				//echo $nomeTipoSensor;

					$tipoIdNome=$tip->tipoQueryChart($nomeTipoSensor,$link); //array with type and id
					$tipoIdNome2=$tip->tipoQueryChart($nomeTipoSensor2,$link);//array with type and id
					$tipoIdNome3=$tip->tipoQueryChart($nomeTipoSensor3,$link);//array with type and id
					$tipoIdNome4=$tip->tipoQueryChart($nomeTipoSensor4,$link);//array with type and id

				
				//echo $tipoIdNome[0]; //tipoId
				//echo $tipoIdNome[1]; //tipoNome
				//echo $tipoIdNome2[1]; //tipoNome
			}
			
			//---------------------------------Sensor Query values----------------------------------
            if (!$link) {
                printf($messErr_connectionDatabaseFailed);
                printf("<br />");
            }
            /* If connection successed */
            else {

					$listValorData=$sensor->SensorQueryChart($tipoIdNome,$dataDia,$arduinoId_Nome, $link); //bidimensional array wuith calues and dates
					if($listValorData[1] && $_REQUEST['TipoSensor'] && $_REQUEST['dataDia'] && $_REQUEST['arduinoId_Nome']){//if line not filled in, does not show this graph or min and max
						$maxValor=$sensor->maxValor($listValorData[1]);
						$minValor=$sensor->minValor($listValorData[1]);
						echo "<h4>".$tipoIdNome[1]."</h4>";
						echo "<h4>".$_REQUEST['dataDia']."</h4>";
						echo "<h4>Valor Max.:".$maxValor."</h4>";
						echo "<h4>Valor Min.:".$minValor."</h4>";
					}

	
					$listValorData2=$sensor->SensorQueryChart($tipoIdNome2,$dataDia2,$arduinoId_Nome2, $link);//bidimensional array wuith calues and dates
					if($listValorData2[1] && $_REQUEST['TipoSensor2'] && $_REQUEST['dataDia2'] && $_REQUEST['arduinoId_Nome2']){//if line not filled in, does not show this graph or min and max
						$maxValor2=$sensor->maxValor($listValorData2[1]);
						$minValor2=$sensor->minValor($listValorData2[1]);
						echo "<h4>".$tipoIdNome2[1]."</h4>";
						echo "<h4>".$_REQUEST['dataDia2']."</h4>";
						echo "<h4>Valor Max.:".$maxValor2."</h4>";
						echo "<h4>Valor Min.:".$minValor2."</h4>";
					}


					$listValorData3=$sensor->SensorQueryChart($tipoIdNome3,$dataDia3,$arduinoId_Nome3, $link);//bidimensional array wuith calues and dates
					if($listValorData3[1] && $_REQUEST['TipoSensor3'] && $_REQUEST['dataDia3'] && $_REQUEST['arduinoId_Nome3']){//if line not filled in, does not show this graph or min and max
						$maxValor3=$sensor->maxValor($listValorData3[1]);
						$minValor3=$sensor->minValor($listValorData3[1]);
						echo "<h4>".$tipoIdNome3[1]."</h4>";
						echo "<h4>".$_REQUEST['dataDia3']."</h4>";
						echo "<h4>Valor Max.:".$maxValor3."</h4>";
						echo "<h4>Valor Min.:".$minValor3."</h4>";
					}


					$listValorData4=$sensor->SensorQueryChart($tipoIdNome4,$dataDia4,$arduinoId_Nome4, $link);//bidimensional array wuith calues and dates
					if($listValorData4[1] && $_REQUEST['TipoSensor4'] && $_REQUEST['dataDia4'] && $_REQUEST['arduinoId_Nome4']){//if line not filled in, does not show this graph or min and max
						$maxValor4=$sensor->maxValor($listValorData4[1]);
						$minValor4=$sensor->minValor($listValorData4[1]);
						echo "<h4>".$tipoIdNome4[1]."</h4>";
						echo "<h4>".$_REQUEST['dataDia4']."</h4>";
						echo "<h4>Valor Max.:".$maxValor4."</h4>";
						echo "<h4>Valor Min.:".$minValor4."</h4>";
					}

			}

		?>	
	<script type="text/javascript">
		var tipo1 = <?php echo json_encode($_REQUEST['TipoSensor'], JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable
		var tipo2 = <?php echo json_encode($_REQUEST['TipoSensor2'], JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable
		var tipo3 = <?php echo json_encode($_REQUEST['TipoSensor3'], JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable
		var tipo4 = <?php echo json_encode($_REQUEST['TipoSensor4'], JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable
		
		//chart1
		var arrayValores = <?php echo json_encode($listValorData[1], JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable
		var nomeTipo = <?php echo json_encode($tipoIdNome[1], JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable
		var arrayData = <?php echo json_encode($listValorData[0], JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable
		//chart2
		var arrayValores2 = <?php echo json_encode($listValorData2[1], JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable
		var nomeTipo2 = <?php echo json_encode($tipoIdNome2[1], JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable
		var arrayData2 = <?php echo json_encode($listValorData2[0], JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable		
		//chart3
		var arrayValores3 = <?php echo json_encode($listValorData3[1], JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable
		var nomeTipo3 = <?php echo json_encode($tipoIdNome3[1], JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable
		var arrayData3 = <?php echo json_encode($listValorData3[0], JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable		
		//chart4
		var arrayValores4 = <?php echo json_encode($listValorData4[1], JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable
		var nomeTipo4 = <?php echo json_encode($tipoIdNome4[1], JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable
		var arrayData4 = <?php echo json_encode($listValorData4[0], JSON_HEX_TAG); ?>; //convert PHP variable into Javascript Variable

		//chart1
		if(tipo1)
		{
			var ctx = document.getElementById("Chart1").getContext('2d');
			var myChart = new Chart(ctx, {
				type: 'line',
				data: {
						labels: arrayData,
						datasets: [{
							label: 'Representação gráfica de '+ nomeTipo,
							data: arrayValores,
							backgroundColor: "rgba(153,255,51,0.4)"
						}]
				}
			});
		}
		//chart2
		if(tipo2)
		{
			var ctx = document.getElementById("Chart2").getContext('2d');
			var myChart = new Chart(ctx, {
				type: 'line',
				data: {
						labels: arrayData2,
						datasets: [{
							label: 'Representação gráfica de '+ nomeTipo2,
							data: arrayValores2,
							backgroundColor: "rgba(153,255,51,0.4)"
						}]
				}
			});
		}
		//chart3
		if(tipo3)
		{		
			var ctx = document.getElementById("Chart3").getContext('2d');
			var myChart = new Chart(ctx, {
				type: 'line',
				data: {
						labels: arrayData3,
						datasets: [{
							label: 'Representação gráfica de '+ nomeTipo3,
							data: arrayValores3,
							backgroundColor: "rgba(153,255,51,0.4)"
						}]
				}
			});
		}
		//chart4
		if(tipo4)
		{
			var ctx = document.getElementById("Chart4").getContext('2d');
			var myChart = new Chart(ctx, {
				type: 'line',
				data: {
						labels: arrayData4,
						datasets: [{
							label: 'Representação gráfica de '+ nomeTipo4,
							data: arrayValores4,
							backgroundColor: "rgba(153,255,51,0.4)"
						}]
				}
			});
		}
    </script>
	
	
  </body>
</html>
