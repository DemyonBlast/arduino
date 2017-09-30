<?php



class Sensors {
    private $ID;
    private $valores;
	private $tipoSensor;	
    private $arduinoID;
	private $valoresData;
	
    public function __construct()
    {
        //echo"<h4>Sensor criado com sucesso!</h4>"; //testing purposes
    }
	function setID($ID)
	{
		$this->ID=$ID;
	}
	function setValores($valores)
	{
		$this->valores=$valores;
	}
	function setValoresData($valoresData)
	{
		$this->valoresData=$valoresData;
	}
	function getID()
    {
        return $this->ID;
    }
	function getValores()
    {
        return $this->valores;
    }
	function setTipoSensor($tipoSensor)
	{
		$this->tipoSensor=$tipoSensor;
	}
	function getTipoSensor()
	{
		return $this->tipoSensor;
	}
	function setArduinoId($arduinoID)
	{
		$this->arduinoID=$arduinoID;
	}
	function getArduinoID()
	{
		return $this->arduinoID;
	}
	function getValoresData()
	{
		return $this->valoresData;
	}
	function maxValor($valores)//receives array
	{
		return max($valores); //returns the max value in the array
	}
	function minValor($valores)//receives array
	{

		return min($valores); //returns the max value in the array
	}
	function SensorQueryChart($tipoIdNome,$dataDia,$arduinoId_Nome, $link)
	{
		$querySensor = "select sensor.logtime, sensor.id, sensor.tipoID_fk, sensor.id_arduino_fk, sensor.valores, Arduino.descrip from sensor INNER JOIN Arduino ON sensor.id_arduino_fk=Arduino.id where tipoID_fk=".$tipoIdNome[0]." AND logtime LIKE '%".$dataDia."%' AND (descrip like '%".$arduinoId_Nome."%' OR id_arduino_fk='".$arduinoId_Nome."')";

		echo "<h4><table>"; //table start
		$results = $link->query($querySensor);
		$arrayValores=[];
		$arrayData=[];
						
		$flag=0;
		//echo $results->num_rows;
		if ($results->num_rows > 0)
		{
			while($row = $results->fetch_assoc())
			{
				$arrayData[$flag]=$row["logtime"];
				$arrayValores[$flag]=$row["valores"];
				//echo $arrayData[$flag]."<br />";
				//echo $arrayIdSensor[$flag]."<br />";
				//echo $arrayIdTipo[$flag]."<br />";
				//echo $arrayValores[$flag]."<br />";
				//echo $arrayIdArduino[$flag]."<br />";
									
				$flag++;
				//echo $flag;
			}
		}
		else
		{
			//echo "0 results Sensor";
		}
		echo "</table></h4>"; //end table
		//$max=$sensor->maxValor($arrayValores);
		//echo $max;
		return $arrayValorData= array ($arrayData,$arrayValores);
	}
	
}
?>