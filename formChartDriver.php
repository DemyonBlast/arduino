

<html>
<head>
</head>
<body>
	<link rel="stylesheet" href="css/form.css">
	<script src="js/jquery.min.js"></script>
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
	<section class="formboard">	
		<form id="chart" action="chartDriver.php" method="post">
			<div id="chart1">
				<input type="text" name="TipoSensor" placeholder="Nome do sensor" list=sensor>/>
				<input type="text" name="arduinoId_Nome" placeholder="Id do Arduino ou Local" list=arduino>/>
				<input type="text" name="dataDia" placeholder="Data- 2017-05-24" list=data>/>
			</div>
			
			<div id="chart2">
				<input type="text" name="TipoSensor2" placeholder="Nome do sensor" list=sensor>/>
				<input type="text" name="arduinoId_Nome2" placeholder="Id do Arduino ou Local" list=arduino>/>
				<input type="text" name="dataDia2" placeholder="Data- 2017-05-24" list=data>/>
				</div>
			
			<div id="chart3">
				<input type="text" name="TipoSensor3" placeholder="Nome do sensor" list=sensor>/>
				<input type="text" name="arduinoId_Nome3" placeholder="Id do Arduino ou Local" list=arduino>/>
				<input type="text" name="dataDia3" placeholder="Data- 2017-05-24" list=data>/>
				</div>
			
			<div id="chart4">
				<input type="text" name="TipoSensor4" placeholder="Nome do sensor" list=sensor>/>
				<input type="text" name="arduinoId_Nome4" placeholder="Id do Arduino ou Local" list=arduino>/>
				<input type="text" name="dataDia4" placeholder="Data- 2017-05-24" list=data>/>
			</div>		
			<div class="submitBut">
				<input type="submit" value="Concluir">
			</div>
			
			<datalist id=sensor>
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
						$sort->bdOptionsTipo($link);
					}
				?>
		    </datalist>

			<datalist id=arduino>
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
						$sort->bdOptionsArduinoDescrip($link);
					}
				?>
			</datalist>

			<datalist id=data>
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
						$sort->bdOptionsArduinoData($link);
					}
				?>
			</datalist>
			
		</form>
		
		<h3>Insira os dados do sensor que pretende visualizar e a data do dia.</h3>
		<h3>Tipos de sensores</h3>
		<h4 ><img src="img/humidity.png" alt="Forest" style="width:40px">Humidade do ar </h4>
		<h4><img src="img/UVradiationsymbol.jpg" alt="Forest" style="width:40px">Radiação UV</h4>
		<h4><img src="img/smoke.png" alt="Forest" style="width:40px">Fumo</h4>
		<h4><img src="img/temperature.png" alt="Forest" style="width:40px">Temperatura do ar</h4>
		<h4><img src="img/luminosity.png" alt="Forest" style="width:40px">Luminosidade</h4>
		<h4><img src="img/C02.png" alt="Forest" style="width:40px">CO2</h4>
		<h4><img src="img/lpg.png" alt="Forest" style="width:40px">LPG</h4>
		<h4><img src="img/atmospheric_pressure.png" alt="Forest" style="width:40px">Pressão Atmosférica</h4>
		<h4><img src="img/seaAtmophericPressure.GIF" alt="Forest" style="width:40px">Pressão ao nivel do Mar</h4>
		<h4><img src="img/Height.png" alt="Forest" style="width:40px">Altitude</h4>
		
	</section>
	
</body>
</html>