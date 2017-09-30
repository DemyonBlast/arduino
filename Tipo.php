<?php


class Tipo {
    private $ID;
    private $tipo;
    
    public function __construct()
    {
       // echo"<h4>Tipo criado com sucesso!</h4>";//testing purposes
    }
	function setID($ID)
    {
        $this->ID=$ID;
    }
	function setTipo($tipo)
    {
        $this->tipo=$tipo;
    }
    function getID()
    {
        return $this->ID;
    }
    
    function getTipo()
    {
        return $this->tipo;
    }
	function tipoQueryChart($strQuery, $link)
	{
		$queryTipo = "SELECT id, tipo FROM Tipo WHERE tipo like '%".$strQuery."%'";
        //echo $queryTipo;
		$nomeTipo=0;
		$tipoId=0;
        $results = $link->query($queryTipo);
		//echo $results->num_rows;
        if ($results->num_rows > 0)
        {
            while($row = $results->fetch_assoc()) 
            {
						
				$nomeTipo=$row["tipo"];
				$tipoId=$row["id"];
				//echo $nomeTipo;
				//echo $tipoId;
            }
        }
		else
        {
            echo "0 results Tipo de Sensor";
        }
		
		return $arrayDados=[$tipoId, $nomeTipo];
	}
}
?>