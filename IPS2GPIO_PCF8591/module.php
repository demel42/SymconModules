<?
    // Klassendefinition
    class IPS2GPIO_PCF8591 extends IPSModule 
    {
	// Überschreibt die interne IPS_Create($id) Funktion
        public function Create() 
        {
            	// Diese Zeile nicht löschen.
            	parent::Create();
 	    	$this->ConnectParent("{ED89906D-5B78-4D47-AB62-0BDCEB9AD330}");
 	    	$this->RegisterPropertyInteger("DeviceAddress", 48);
 	    	$this->RegisterPropertyInteger("Messzyklus", 60);
            	$this->RegisterTimer("Messzyklus", 0, 'I2GAD1_Measurement($_IPS["TARGET"]);');
        }
 
        // Überschreibt die intere IPS_ApplyChanges($id) Funktion
        public function ApplyChanges() 
        {
            	// Diese Zeile nicht löschen
            	parent::ApplyChanges();
            	//Connect to available splitter or create a new one
	    	$this->ConnectParent("{ED89906D-5B78-4D47-AB62-0BDCEB9AD330}");
	    	// Device Adresse prüfen
	    	If (($this->ReadPropertyInteger("DeviceAddress") < 0) OR ($this->ReadPropertyInteger("DeviceAddress") > 128)) {
	    		IPS_LogMessage("GPIO : ","I2C-Device Adresse in einem nicht definierten Bereich!");  
	    	}
	    	//Status-Variablen anlegen
	    	$this->RegisterVariableInteger("HardwareRev", "HardwareRev", "", 100);
          	$this->DisableAction("HardwareRev");
		IPS_SetHidden($this->GetIDForIdent("HardwareRev"), true);
		
		$this->RegisterVariableString("MeasurementData", "MeasurementData", "", 130);
		$this->DisableAction("MeasurementData");
		IPS_SetHidden($this->GetIDForIdent("MeasurementData"), true);
		
		$this->RegisterVariableInteger("Channel_0", "Channel 0", "~Intensity.255", 10);
          	$this->DisableAction("Channel_0");
		IPS_SetHidden($this->GetIDForIdent("Channel_0"), false);
		
		$this->RegisterVariableInteger("Channel_1", "Channel 1", "~Intensity.255", 20);
          	$this->DisableAction("Channel_1");
		IPS_SetHidden($this->GetIDForIdent("Channel_1"), false);
		
		$this->RegisterVariableInteger("Channel_2", "Channel 2", "~Intensity.255", 30);
          	$this->DisableAction("Channel_2");
		IPS_SetHidden($this->GetIDForIdent("Channel_2"), false);
		
		$this->RegisterVariableInteger("Channel_3", "Channel 3", "~Intensity.255", 40);
          	$this->DisableAction("Channel_3");
		IPS_SetHidden($this->GetIDForIdent("Channel_3"), false);
		
		$this->RegisterVariableInteger("Output", "Output", "~Intensity.255", 50);
          	$this->EnableAction("Output");
		IPS_SetHidden($this->GetIDForIdent("Output"), false);
		
          	$this->RegisterVariableInteger("Handle", "Handle", "", 110);
		$this->DisableAction("Handle");
		IPS_SetHidden($this->GetIDForIdent("Handle"), true);
             	
            	If (GetValueInteger($this->GetIDForIdent("Handle")) >= 0) {
             		// Handle löschen
             		//$this->SendDataToParent(json_encode(Array("DataID"=> "{A0DAAF26-4A2D-4350-963E-CC02E74BD414}", "Function" => "close_handle_i2c", "Handle" => GetValueInteger($this->GetIDForIdent("Handle")))));
             		SetValueInteger($this->GetIDForIdent("Handle"), -1);
             	}
            	// den Handle für dieses Gerät ermitteln
            	$this->SendDataToParent(json_encode(Array("DataID"=> "{A0DAAF26-4A2D-4350-963E-CC02E74BD414}", "Function" => "get_handle_i2c", "DeviceAddress" => $this->ReadPropertyInteger("DeviceAddress"))));
            	$this->SendDataToParent(json_encode(Array("DataID"=> "{A0DAAF26-4A2D-4350-963E-CC02E74BD414}", "Function" => "get_pinupdate")));
            	$this->SetTimerInterval("Messzyklus", ($this->ReadPropertyInteger("Messzyklus") * 1000));
            	If (GetValueInteger($this->GetIDForIdent("Handle")) >= 0) {
	            	// Setup
	            	$this->Setup();
	            	// Erste Messdaten einlesen
	            	$this->Measurement();
	            	$this->SetStatus(102);
            	}
        }
	
	public function ReceiveData($JSONString) 
	{
	    	// Empfangene Daten vom Gateway/Splitter
	    	$data = json_decode($JSONString);
	 	switch ($data->Function) {
			   case "set_i2c_handle":
			   	If ($data->Address == $this->ReadPropertyInteger("DeviceAddress")) {
			   		SetValueInteger($this->GetIDForIdent("Handle"), $data->Handle);
			   		SetValueInteger($this->GetIDForIdent("HardwareRev"), $data->HardwareRev);
			   	}
			   	break;
			   case "get_used_i2c":
			   	$this->SendDataToParent(json_encode(Array("DataID"=> "{A0DAAF26-4A2D-4350-963E-CC02E74BD414}", "Function" => "set_used_i2c", "Value" => true)));
			   	break;
			   case "status":
			   	If (GetValueInteger($this->GetIDForIdent("HardwareRev")) <= 3) {
				   	If (($data->Pin == 0) OR ($data->Pin == 1)) {
				   		$this->SetStatus($data->Status);		
				   	}
			   	}
				else if (GetValueInteger($this->GetIDForIdent("HardwareRev")) > 3) {
					If (($data->Pin == 2) OR ($data->Pin == 3)) {
				   		$this->SetStatus($data->Status);
				   	}
				}
			   	break;
			  case "set_i2c_data":
			  	If ($data->Handle == GetValueInteger($this->GetIDForIdent("Handle"))) {
			  		// Daten der Messung
			  		
			  		}
			  		
			  	}
			  	break;
			  case "set_i2c_byte_block":
			   	If ($data->Handle == GetValueInteger($this->GetIDForIdent("Handle"))) {
			   		SetValueString($this->GetIDForIdent("MeasurementData"), $data->ByteArray);
			   		$MeasurementData = unserialize(GetValueString($this->GetIDForIdent("MeasurementData")));
			   		SetValueInteger($this->GetIDForIdent("Channel_0"), $MeasurementData[0]);
			   		SetValueInteger($this->GetIDForIdent("Channel_1"), $MeasurementData[1]);
			   		SetValueInteger($this->GetIDForIdent("Channel_2"), $MeasurementData[2]);
			   		SetValueInteger($this->GetIDForIdent("Channel_3"), $MeasurementData[3]);
			   	}
			   	break;
	 	}
	return;
 	}
	// Beginn der Funktionen
	// Führt eine Messung aus
	public function Measurement()
	{
		//$this->SendDataToParent(json_encode(Array("DataID"=> "{A0DAAF26-4A2D-4350-963E-CC02E74BD414}", "Function" => "i2c_read_word", "Handle" => GetValueInteger($this->GetIDForIdent("Handle")), "Register" => $this->ReadPropertyInteger("DeviceAddress"))));
		
	return;
	}
	
	public function Set_Output($Value)
	{
		//$this->SendDataToParent(json_encode(Array("DataID"=> "{A0DAAF26-4A2D-4350-963E-CC02E74BD414}", "Function" => "i2c_read_word", "Handle" => GetValueInteger($this->GetIDForIdent("Handle")), "Register" => $this->ReadPropertyInteger("DeviceAddress"))));
		
	return;
	}
	
	private function ReadData()
	{
		$MeasurementData = array();
		SetValueString($this->GetIDForIdent("MeasurementData"), serialize($MeasurementData));
		$this->SendDataToParent(json_encode(Array("DataID"=> "{A0DAAF26-4A2D-4350-963E-CC02E74BD414}", "Function" => "i2c_read_block_byte", "Handle" => GetValueInteger($this->GetIDForIdent("Handle")), "Register" => hexdec("40"), "Count" => 4)));
	return;
	}
}
?>
