<?php

class SortData {
    
    function coords($arduino)
    {
           
        $j='0';  //vai definir o meio da função
        $lat='';
        $long='';
        $coords=$arduino;
        
        //echo $coords;
        //echo "length string".strlen($coords);
        list($lat,$long) = explode(',',$coords);
        //echo $long.'exploded <br />';

        $coords= array($lat,$long);
        //echo $coords[0].' coord 0<br />';
        //echo $coords[1].' coord 1<br />';
        //echo 'after array';
        return $coords;
    }
	function bdServer()
	{
		$servername = "localhost";
        $username = "root";
        $password = "";
        $database = "sensores";
			
		/* error messages */
        $messErr_connectionDatabaseFailed = "Error : connection failed. Please try later.";

        return $link = new mysqli($servername, $username, $password, $database);
	}
	function bdOptionsTipo($link)
	{
		$queryTipo = "SELECT id, tipo FROM Tipo";
        //echo $queryTipo;
		$nomeTipo=0;
		$tipoId=0;
        $results = $link->query($queryTipo);
		//echo $results->num_rows;
        if ($results->num_rows > 0)
        {
            while($row = $results->fetch_assoc()) 
            {
				echo "<option>".$row["tipo"]."</option>";
            }
        }
		else
        {
            echo "0 results Tipo de Sensor";

		}
	}
	
	function bdOptionsArduinoId($link)
	{
		$queryTipo = "SELECT id FROM Arduino";
        //echo $queryTipo;
		$nomeTipo=0;
		$tipoId=0;
        $results = $link->query($queryTipo);
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
            echo "0 results Tipo de Sensor";

		}
	}

	function bdOptionsArduinoDescrip($link)
	{
		$queryTipo = "SELECT descrip FROM Arduino";
        //echo $queryTipo;
		$nomeTipo=0;
		$tipoId=0;
        $results = $link->query($queryTipo);
		//echo $results->num_rows;
        if ($results->num_rows > 0)
        {
            while($row = $results->fetch_assoc()) 
            {
				echo "<option>".$row["descrip"]."</option>";
            }
        }
		else
        {
            echo "0 results Tipo de Sensor";

		}
	}
	
	function bdOptionsArduinoData($link)
	{
		$queryTipo = "select logtime from sensor";
        //echo $queryTipo;
		$nomeTipo=0;
		$tipoId=0;
        $results = $link->query($queryTipo);
		//echo $results->num_rows;
        if ($results->num_rows > 0)
        {
            while($row = $results->fetch_assoc()) 
            {
				echo "<option>".$row["logtime"]."</option>";
            }
        }
		else
        {
            echo "0 results Tipo de Sensor";

		}
	}
}
?>