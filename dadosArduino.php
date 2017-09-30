<?php



class dadosArduino {

    private $ID;
    private $georeference;
    private $status;
	private $descrip;
    
    public function __construct()
    {
       //echo "<h4> Arduino criado com sucesso!</h4>";//testing purposes
    }
    function setID($ID)
    {
        $this->ID=$ID;
    }
    function setGeoreference($georeference)
    {
        $this->georeference=$georeference;
    }
    function setStatus($status)
    {
        $this->status=$status;
    }
	function setData($ID, $georeference, $status, $descrip)
	{
		$this->ID=$ID;
		$this->georeference=$georeference;
		$this->status=$status;
		$this->descrip=$descrip;
	}
	function setDescrip($descrip)
	{
		$this->descrip=$descrip;
	}
    function getID()
    {
        return $this->ID;
    }
    function getGeoreference()
    {
        return $this->georeference;
    }
    function getStatus()
    {
        return $this->status;
    }
	function getDescrip()
	{
		return $this->descrip;
	}
	function printAll()
	{
		echo "<h3>IDarduino:".getID()."- Geo:".getGeoreference()."- Status:".getStatus()." - Description: ".getDescrip()."<br></h3>"; 
	}

}
?>