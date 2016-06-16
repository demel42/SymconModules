<?
   // Klassendefinition
    class IPS2GPIO_RGB extends IPSModule 
    {
 
        // Der Konstruktor des Moduls
        // Überschreibt den Standard Kontruktor von IPS
        public function __construct($InstanceID) 
        {
            // Diese Zeile nicht löschen
            parent::__construct($InstanceID);
 
            // Selbsterstellter Code
        }
 
        // Überschreibt die interne IPS_Create($id) Funktion
        public function Create() 
        {
            // Diese Zeile nicht löschen.
            parent::Create();
           
            $this->RegisterPropertyInteger("Pin_R", 2);
            $this->RegisterPropertyInteger("Pin_G", 3);
            $this->RegisterPropertyInteger("Pin_B", 4);
 	    $this->ConnectParent("{ED89906D-5B78-4D47-AB62-0BDCEB9AD330}");	
        }
 
        // Überschreibt die intere IPS_ApplyChanges($id) Funktion
        public function ApplyChanges() 
        {
      		// Diese Zeile nicht löschen
      		parent::ApplyChanges();
            
      		//Connect to available splitter or create a new one
	   	$this->ConnectParent("{ED89906D-5B78-4D47-AB62-0BDCEB9AD330}");
	   
	        //Status-Variablen anlegen
	        $this->RegisterVariableBoolean("Status", "Status", "~Switch", 1);
           	$this->EnableAction("Status");
           	$this->RegisterVariableInteger("Intensity_R", "Intensity Rot", "~Intensity.255");
           	$this->EnableAction("Intensity_R");
           	$this->RegisterVariableInteger("Intensity_G", "Intensity Grün", "~Intensity.255");
           	$this->EnableAction("Intensity_G");
           	$this->RegisterVariableInteger("Intensity_B", "Intensity Blau", "~Intensity.255");
           	$this->EnableAction("Intensity_B");
           	$this->RegisterVariableInteger("Color", "Farbe", "~HexColor");
           	$this->EnableAction("Color");
           	$this->Set_Mode_RGB();
        }
	// Beginn der Funktionen
	
	// Setzt den gewaehlten Pins in den Output-Modus
	private function Set_Mode_RGB()
	{
   		$RPiPort = 8888;
   		$IPSID = 29419;
   		list($result, $IPSUser, $IPSPass) = $this->RemoteAccessData();
		$result = "";
   		$result = exec('sudo python '.IPS_GetKernelDir().'modules/SymconModules/IPS2GPIO/ips2gpio.py '.IPS_GetProperty((IPS_GetInstance($this->InstanceID)['ConnectionID']), "IPAddress").' '.$RPiPort.' '.$IPSUser.' '.$IPSPass.' '.$IPSID.' set_mode_RGB '.$this->ReadPropertyInteger("Pin_R").' '.$this->ReadPropertyInteger("Pin_G").' '.$this->ReadPropertyInteger("Pin_B"));
	return $result;
	}
	
	// Dimmt den gewaehlten Pin
	public function Set_RGB($R, $G, $B)
	{
   		$IPSID = 29419;
   		list($result, $IPSUser, $IPSPass) = $this->RemoteAccessData();
		$result = "";
   		$result = exec('sudo python '.IPS_GetKernelDir().'modules/SymconModules/IPS2GPIO/ips2gpio.py '.IPS_GetProperty((IPS_GetInstance($this->InstanceID)['ConnectionID']), "IPAddress").' 8888 '.$IPSUser.' '.$IPSPass.' '.$IPSID.' set_PWM_dutycycle_RGB '.$this->ReadPropertyInteger("Pin_R").' '.$R.' '.$this->ReadPropertyInteger("Pin_G").' '.$G.' '.$this->ReadPropertyInteger("Pin_B").' '.$B);
		SetValue($this->GetIDForIdent("Intensity_R"), $R);
		SetValue($this->GetIDForIdent("Intensity_G"), $G);
		SetValue($this->GetIDForIdent("Intensity_B"), $B);
		
	return $result;
	}
	
	public function Set_Status($value)
	{
		SetValue($this->GetIDForIdent("Status"), $value);
		$IPSID = 29419;
   		list($result, $IPSUser, $IPSPass) = $this->RemoteAccessData();
		$result = "";
		If ($value == true)
		{
			$result = exec('sudo python '.IPS_GetKernelDir().'modules/SymconModules/IPS2GPIO/ips2gpio.py '.IPS_GetProperty((IPS_GetInstance($this->InstanceID)['ConnectionID']), "IPAddress").' 8888 '.$IPSUser.' '.$IPSPass.' '.$IPSID.' set_PWM_dutycycle_RGB '.$this->ReadPropertyInteger("Pin_R").' '.GetValue($this->GetIDForIdent("Intensity_R")).' '.$this->ReadPropertyInteger("Pin_G").' '.GetValue($this->GetIDForIdent("Intensity_G")).' '.$this->ReadPropertyInteger("Pin_B").' '.GetValue($this->GetIDForIdent("Intensity_B")));
		}
		else
		{
			$result = exec('sudo python '.IPS_GetKernelDir().'modules/SymconModules/IPS2GPIO/ips2gpio.py '.IPS_GetProperty((IPS_GetInstance($this->InstanceID)['ConnectionID']), "IPAddress").' 8888 '.$IPSUser.' '.$IPSPass.' '.$IPSID.' set_PWM_dutycycle_RGB '.$this->ReadPropertyInteger("Pin_R").' 0 '.$this->ReadPropertyInteger("Pin_G").' 0 '.$this->ReadPropertyInteger("Pin_B").' 0');
		}
	}
	
	
	private function Hex2RGB($Hex)
	{
		$r = (($Hex >> 16) & 0xFF);
		$g = (($Hex >> 8) & 0xFF);
		$b = (($Hex >> 0) & 0xFF);	
	return array($r, $g, $b);
	}
	
	private function RGB2Hex($r, $g, $b)
	{
		$Hex = hexdec(str_pad(dechex($r), 2,'0', STR_PAD_LEFT).str_pad(dechex($g), 2,'0', STR_PAD_LEFT).str_pad(dechex($b), 2,'0', STR_PAD_LEFT));
	
	return $Hex;
	}
	
	// Ermittelt den User und das Passwort für den Fernzugriff (nur RPi)
	private function RemoteAccessData()
	{
	   	$result = true;
	   	exec('sudo cat /root/.symcon', $ResultArray);
	   	If (strpos($ResultArray[0], "Licensee=") === false) {
			$result = false; }
		else {
	      		$User = substr(strstr($ResultArray[0], "="),1); }
		If (strpos($ResultArray[(count($ResultArray))-1], "Password=") === false) {
			$result = false; }
		else {
	      		$Pass = base64_decode(substr(strstr($ResultArray[(count($ResultArray))-1], "="),1)); }
	return array($result, $User, $Pass);
	}

    }
?>
