<?
    // Klassendefinition
    class IPS2GPIO_MCP3424 extends IPSModule 
    {
	// Überschreibt die interne IPS_Create($id) Funktion
        public function Create() 
        {
            	// Diese Zeile nicht löschen.
            	parent::Create();
 	    	$this->RegisterPropertyBoolean("Open", false);
		$this->ConnectParent("{ED89906D-5B78-4D47-AB62-0BDCEB9AD330}");
 	    	$this->RegisterPropertyInteger("DeviceAddress", 104);
		$this->RegisterPropertyInteger("DeviceBus", 1);
 	    	$this->RegisterPropertyInteger("Messzyklus", 60);
		$this->RegisterPropertyInteger("Resolution_0", 0);
		$this->RegisterPropertyInteger("Resolution_1", 0);
		$this->RegisterPropertyInteger("Resolution_2", 0);
		$this->RegisterPropertyInteger("Resolution_3", 0);
		$this->RegisterPropertyInteger("Amplifier_0", 0);
		$this->RegisterPropertyInteger("Amplifier_1", 0);
		$this->RegisterPropertyInteger("Amplifier_2", 0);
		$this->RegisterPropertyInteger("Amplifier_3", 0);
		
            	$this->RegisterTimer("Messzyklus", 0, 'I2GAD2_Measurement($_IPS["TARGET"]);');
        }
 	
	public function GetConfigurationForm() 
	{ 
		$arrayStatus = array(); 
		$arrayStatus[] = array("code" => 101, "icon" => "inactive", "caption" => "Instanz wird erstellt"); 
		$arrayStatus[] = array("code" => 102, "icon" => "active", "caption" => "Instanz ist aktiv");
		$arrayStatus[] = array("code" => 104, "icon" => "inactive", "caption" => "Instanz ist inaktiv");
		$arrayStatus[] = array("code" => 200, "icon" => "error", "caption" => "Pin wird doppelt genutzt!");
		$arrayStatus[] = array("code" => 201, "icon" => "error", "caption" => "Pin ist an diesem Raspberry Pi Modell nicht vorhanden!"); 
		
		$arrayElements = array(); 
		$arrayElements[] = array("name" => "Open", "type" => "CheckBox",  "caption" => "Aktiv"); 
 		
		$arrayOptions = array();
		$arrayOptions[] = array("label" => "104 dez. / 0x68h", "value" => 104);
		$arrayOptions[] = array("label" => "106 dez. / 0x6Ah", "value" => 106);
		$arrayOptions[] = array("label" => "108 dez. / 0x6Ch", "value" => 108);
		$arrayOptions[] = array("label" => "110 dez. / 0x6Eh", "value" => 110);
		$arrayElements[] = array("type" => "Select", "name" => "DeviceAddress", "caption" => "Device Adresse", "options" => $arrayOptions );
		
		$arrayElements[] = array("type" => "Label", "label" => "I²C-Bus (Default ist 1)");
		$arrayOptions = array();
		$arrayOptions[] = array("label" => "I²C-Bus 0", "value" => 0);
		$arrayOptions[] = array("label" => "I²C-Bus 1", "value" => 1);
		$arrayOptions[] = array("label" => "MUX I²C-Bus 0", "value" => 3);
		$arrayOptions[] = array("label" => "MUX I²C-Bus 1", "value" => 4);
		$arrayOptions[] = array("label" => "MUX I²C-Bus 2", "value" => 5);
		$arrayOptions[] = array("label" => "MUX I²C-Bus 3", "value" => 6);
		$arrayOptions[] = array("label" => "MUX I²C-Bus 4", "value" => 7);
		$arrayOptions[] = array("label" => "MUX I²C-Bus 5", "value" => 8);
		$arrayOptions[] = array("label" => "MUX I²C-Bus 6", "value" => 9);
		$arrayOptions[] = array("label" => "MUX I²C-Bus 7", "value" => 10);
		$arrayElements[] = array("type" => "Select", "name" => "DeviceBus", "caption" => "Device Bus", "options" => $arrayOptions );
		
		$arrayElements[] = array("type" => "Label", "label" => "Wiederholungszyklus in Sekunden (0 -> aus, 15 sek -> Minimum)"); 
		$arrayElements[] = array("type" => "IntervalBox", "name" => "Messzyklus", "caption" => "Messzyklus (sek)");
		
		$arrayElements[] = array("type" => "Label", "label" => "Auflösung der Kanäle wählen (Default 12 Bit)"); 
		$arrayOptions = array();
		$arrayOptions[] = array("label" => "12 Bit", "value" => 0);
		$arrayOptions[] = array("label" => "14 Bit", "value" => 1);
		$arrayOptions[] = array("label" => "16 Bit", "value" => 2);
		$arrayOptions[] = array("label" => "18 Bit", "value" => 3);
		$arrayElements[] = array("type" => "Select", "name" => "Resolution_0", "caption" => "Auflösung Kanal 1", "options" => $arrayOptions );
		$arrayElements[] = array("type" => "Select", "name" => "Resolution_1", "caption" => "Auflösung Kanal 2", "options" => $arrayOptions );
		$arrayElements[] = array("type" => "Select", "name" => "Resolution_2", "caption" => "Auflösung Kanal 3", "options" => $arrayOptions );
		$arrayElements[] = array("type" => "Select", "name" => "Resolution_3", "caption" => "Auflösung Kanal 4", "options" => $arrayOptions );
		
		$arrayElements[] = array("type" => "Label", "label" => "Verstärkung der Kanäle wählen (Default 1x)"); 
		$arrayOptions = array();
		$arrayOptions[] = array("label" => "1x", "value" => 0);
		$arrayOptions[] = array("label" => "2x", "value" => 1);
		$arrayOptions[] = array("label" => "4x", "value" => 2);
		$arrayOptions[] = array("label" => "8x", "value" => 3);
		$arrayElements[] = array("type" => "Select", "name" => "Amplifier_0", "caption" => "Verstärkung Kanal 1", "options" => $arrayOptions );
		$arrayElements[] = array("type" => "Select", "name" => "Amplifier_1", "caption" => "Verstärkung Kanal 2", "options" => $arrayOptions );
		$arrayElements[] = array("type" => "Select", "name" => "Amplifier_2", "caption" => "Verstärkung Kanal 3", "options" => $arrayOptions );
		$arrayElements[] = array("type" => "Select", "name" => "Amplifier_3", "caption" => "Verstärkung Kanal 4", "options" => $arrayOptions );
		
		$arrayActions = array();
		$arrayActions[] = array("type" => "Label", "label" => "Diese Funktionen stehen erst nach Eingabe und Übernahme der erforderlichen Daten zur Verfügung!");
		
		
 		return JSON_encode(array("status" => $arrayStatus, "elements" => $arrayElements, "actions" => $arrayActions)); 		 
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
	    		IPS_LogMessage("IPS2GPIO MCP3424","I2C-Device Adresse in einem nicht definierten Bereich!");  
	    	}
	    	// Profil anlegen
	    	
		//Status-Variablen anlegen
		$this->RegisterVariableFloat("Channel_1", "Kanal 1", "~Volt", 10);
          	$this->DisableAction("Channel_1");
		IPS_SetHidden($this->GetIDForIdent("Channel_1"), false);
		
		$this->RegisterVariableFloat("Channel_2", "Kanal 2", "~Volt", 20);
          	$this->DisableAction("Channel_2");
		IPS_SetHidden($this->GetIDForIdent("Channel_2"), false);
		
		$this->RegisterVariableFloat("Channel_3", "Kanal 3", "~Volt", 30);
          	$this->DisableAction("Channel_3");
		IPS_SetHidden($this->GetIDForIdent("Channel_3"), false);
		
		$this->RegisterVariableFloat("Channel_4", "Kanal 4", "~Volt", 40);
          	$this->DisableAction("Channel_4");
		IPS_SetHidden($this->GetIDForIdent("Channel_4"), false);
		
		If (IPS_GetKernelRunlevel() == 10103) {
			// Logging setzen
						
			//ReceiveData-Filter setzen
			$this->SetBuffer("DeviceIdent", (($this->ReadPropertyInteger("DeviceBus") << 7) + $this->ReadPropertyInteger("DeviceAddress")));
			$Filter = '((.*"Function":"get_used_i2c".*|.*"DeviceIdent":'.$this->GetBuffer("DeviceIdent").'.*)|.*"Function":"status".*)';
			//$this->SendDebug("IPS2GPIO", $Filter, 0);
			$this->SetReceiveDataFilter($Filter);
		
			
			If ($this->ReadPropertyBoolean("Open") == true) {
				$this->SendDataToParent(json_encode(Array("DataID"=> "{A0DAAF26-4A2D-4350-963E-CC02E74BD414}", "Function" => "set_used_i2c", "DeviceAddress" => $this->ReadPropertyInteger("DeviceAddress"), "DeviceBus" => $this->ReadPropertyInteger("DeviceBus"), "InstanceID" => $this->InstanceID)));
				$this->SetTimerInterval("Messzyklus", ($this->ReadPropertyInteger("Messzyklus") * 1000));
				// Setup
				$this->Setup();
				// Erste Messdaten einlesen
				
				$this->SetStatus(102);
			}
			else {
				$this->SetTimerInterval("Messzyklus", 0);
				$this->SetStatus(104);
			}	
		}
		else {
			$this->SetTimerInterval("Messzyklus", 0);
		}
	}
	
	public function ReceiveData($JSONString) 
	{
	    	// Empfangene Daten vom Gateway/Splitter
	    	$data = json_decode($JSONString);
	 	switch ($data->Function) {
			   case "get_used_i2c":
			   	If ($this->ReadPropertyBoolean("Open") == true) {
					$this->SendDataToParent(json_encode(Array("DataID"=> "{A0DAAF26-4A2D-4350-963E-CC02E74BD414}", "Function" => "set_used_i2c", "DeviceAddress" => $this->ReadPropertyInteger("DeviceAddress"), "DeviceBus" => $this->ReadPropertyInteger("DeviceBus"), "InstanceID" => $this->InstanceID)));
					$this->ApplyChanges();
				}
				break;
			   case "status":
			   	If ($data->HardwareRev <= 3) {
				   	If (($data->Pin == 0) OR ($data->Pin == 1)) {
				   		$this->SetStatus($data->Status);		
				   	}
			   	}
				else if ($data->HardwareRev > 3) {
					If (($data->Pin == 2) OR ($data->Pin == 3)) {
				   		$this->SetStatus($data->Status);
				   	}
				}
			   	break;
			  case "set_i2c_data":
			  	If ($data->DeviceIdent == $this->GetBuffer("DeviceIdent")) {
			  		// Daten der Messung
			  		If ($data->Register == $this->ReadPropertyInteger("DeviceAddress"))  {
			  			
						
						
			  		}
			  		
			  	}
			  	break;
			case "set_i2c_byte_block":
			   	If ($data->DeviceIdent == $this->GetBuffer("DeviceIdent")) {
			   		$this->SetBuffer("MeasurementData", $data->ByteArray);
			   	}
			   	break;
	 	}
 	}
	// Beginn der Funktionen
	// Führt eine Messung aus
	public function Measurement()
	{
		If ($this->ReadPropertyBoolean("Open") == true) {
			// Messwerterfassung setzen
			If ($this->ReadPropertyInteger("Resolution_".$i) <= 2) { 
				$this->SendDataToParent(json_encode(Array("DataID"=> "{A0DAAF26-4A2D-4350-963E-CC02E74BD414}", "Function" => "i2c_read_block_byte", "DeviceIdent" => $this->GetBuffer("DeviceIdent"), "Register" => 13, "Count" => 3)));
			}
			elseif ($this->ReadPropertyInteger("Resolution_".$i) == 3) {
				$this->SendDataToParent(json_encode(Array("DataID"=> "{A0DAAF26-4A2D-4350-963E-CC02E74BD414}", "Function" => "i2c_read_block_byte", "DeviceIdent" => $this->GetBuffer("DeviceIdent"), "Register" => 13, "Count" => 4)));
			}
		}
	}	
	
	private function Setup()
	{
		If ($this->ReadPropertyBoolean("Open") == true) {
			for ($i = 0; $i <= 3; $i++) {
				$Configuration = ($i << 5) | (1 << 4) | ($this->ReadPropertyInteger("Resolution_".$i) << 2) | $this->ReadPropertyInteger("Amplifier_".$i);
				$this->SendDataToParent(json_encode(Array("DataID"=> "{A0DAAF26-4A2D-4350-963E-CC02E74BD414}", "Function" => "i2c_write_byte", "DeviceIdent" => $this->GetBuffer("DeviceIdent"), "Register" => 13, "Value" => $Configuration)));
			}
		} 
	}
}
?>