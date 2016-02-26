<?php

class DoubleDigitsName {

  public $Male;
  public $Female;

  public function __construct($male,$female) {
    $this->Male = $male;
	if(empty($female)){
		$this->Female=$male;
	}else{
		$this->Female=$female;
	}
  }
}



class TriadName {

  public $Singular;
  public $Plural;
  public $IsMasculin;

  public function __construct($sing,$plr,$ismsc) {
    $this->Singular = $sing;
	$this->Plural=$plr;
	$this->IsMasculin = $ismsc;
  }
}

class Chunck{
	public $Hundrets;
	public $Decades;
	public $Ones;
	public $DTriad;
	public $DecadessMap;
	public $OnessMap;
	public $SpecialsMap;
	public $HundretssMap;
	
	public function __construct($digits,$triad,$hundretsMap,$decadesMap,$onesMap,$specialDoubleDigitsMap)
	{
		$this->DecadessMap=$decadesMap;
		$this->OnessMap=$onesMap;
		$this->HundretssMap=$hundretsMap;
		$this->SpecialsMap=$specialDoubleDigitsMap;
		$this->DTriad=$triad;
		$digits = array_reverse($digits);
		list($this->Ones,$this->Decades,$this->Hundrets) = $digits;
	}
	
	public function PrintDigits(){
		print $this->Hundrets . $this->Decades . $this->Ones;
	}
	
	public function CalculateName()
	{
		$hundretsName = $this->CalculateHundretsName();
		$decadesAndOnes =$this->CalculateDecadesAndOnesName();
		$triadeName = $this->CalculateTriadName();
		$res = $hundretsName . "  " . $decadesAndOnes . " " . $triadeName;
		$res = trim ($res);
		$res = ltrim($res,"и");
		$res = str_replace("  ", " ", $res);
		return $res;
	}
	
	public function CalculateTriadName()
	{
		#000
		if((!isset($this->Ones)||$this->Ones=="0") && (!isset($this->Decades)||$this->Decades=="0") && (!isset($this->Hundrets)||$this->Hundrets=="0"))
		{
			return NULL;
		}
		
		#singular
		if($this->Ones === "1"){
			return $this->DTriad->Singular;
		}
		
		#plural
		return $this->DTriad->Plural;
	}

	public function CalculateHundretsName()
	{
		if(isset($this->Hundrets))
		{
			return $this->HundretssMap[$this->Hundrets];
		}
		
		return NULL;
	}
	
	public function CalculateDecadesAndOnesName()
	{
		$decadesAndOnesString=NULL;
		if(array_key_exists ($this->Decades . $this->Ones, $this->SpecialsMap ))
		{
			#special case, no need for further calculations;
			$ddName = $this->SpecialsMap[$this->Decades . $this->Ones];
			if($this->DTriad->IsMasculin){
				return  $ddName->Male;
			}else{
				return  $ddName->Female;
			}
		}
		
		$onesName = $this->OnessMap[$this->Ones];
		if($this->DTriad->IsMasculin){
			$onesNameString = $onesName->Male;
		}else{
			$onesNameString=$onesName->Female;	
		}
		
		if(isset($this->Decades))
		{
			$decadesNameString=$this->DecadessMap[$this->Decades];
		}
		
		$decadesAndOnesString = $decadesNameString . " ". $onesNameString;
		
		return $decadesAndOnesString;
	}
	
}

$SpecialDoubleDigitNames=[
""=>new DoubleDigitsName("",""),
"00"=>new DoubleDigitsName("",""),
"01"=>new DoubleDigitsName("и еден","и еднa"),
"02"=>new DoubleDigitsName("и двa","и две"),
"03"=>new DoubleDigitsName("и три",""),
"04"=>new DoubleDigitsName("и четири",""),
"05"=>new DoubleDigitsName("и пет",""),
"06"=>new DoubleDigitsName("и шест",""),
"07"=>new DoubleDigitsName("и седум",""),
"08"=>new DoubleDigitsName("и осум",""),
"09"=>new DoubleDigitsName("и девет",""),
"10"=>new DoubleDigitsName("дест",""),
"11"=>new DoubleDigitsName("единаесет",""),
"12"=>new DoubleDigitsName("дванаесет",""),
"13"=>new DoubleDigitsName("тринаесет",""),
"14"=>new DoubleDigitsName("четиринаесет",""),
"15"=>new DoubleDigitsName("петнаесет",""),
"16"=>new DoubleDigitsName("шестнаесет",""),
"17"=>new DoubleDigitsName("седумнаесет",""),
"18"=>new DoubleDigitsName("осумнаесет",""),
"19"=>new DoubleDigitsName("деветнаесет","")
];

$FirstDecadeDigits = [
""=>new DoubleDigitsName("",""),
"0"=>new DoubleDigitsName("",""),
"1"=>new DoubleDigitsName("и еден","и еднa"),
"2"=>new DoubleDigitsName("и двa","и две"),
"3"=>new DoubleDigitsName("и три",""),
"4"=>new DoubleDigitsName("четири",""),
"5"=>new DoubleDigitsName("и пет",""),
"6"=>new DoubleDigitsName("и шест",""),
"7"=>new DoubleDigitsName("и седум",""),
"8"=>new DoubleDigitsName("и осум",""),
"9"=>new DoubleDigitsName("и девет","")
];

$DecadesNames=[
"2"=>"дваесет",
"3"=>"триесет",
"4"=>"четириесет",
"5"=>"педесет",
"6"=>"шеесет",
"7"=>"седумдесет",
"8"=>"осумдесет",
"9"=>"деведесет"
];


$TriadeNamesMap =[
	0=>new TriadName("","",true),
	1=>new TriadName("илјада","илјади",false),
	2=>new TriadName("милион","милиони",true),
];

$hundretsNames=[
	"0"=>"",
	"1"=>"сто",
	"2"=>"двесте",
	"3"=>"триста",
	"4"=>"четиристотини",
	"5"=>"петстотини",
	"6"=>"шестотини",
	"7"=>"седумстотини",
	"8"=>"осумстотини",
	"9"=>"деветстотини"
];

function GetNumberAsString ($number,$triadeNamesMap )
{
	
	$numberAsArray = str_split($number,1);
	$reversedNumArr = array_reverse($numberAsArray);
	$reversedNumString = implode ("",$reversedNumArr );
	$curTriadeIndex=0;
	$chunks = array();
	while($reversedNumString ){
		$splitBy3 = str_split($reversedNumString ,3);
		$triadeString = $splitBy3[0];
		$triadeArr = array_reverse(str_split($triadeString,1));
		$reversedNumString = ltrim($reversedNumString,$triadeString);
		
		$triade = $triadeNamesMap[$curTriadeIndex];
		$chunk = new Chunck($triadeArr,$triade,$hundretsNames,$DecadesNames,$FirstDecadeDigits,$SpecialDoubleDigitNames);
		array_push($chunks,$chunk);
		$curTriadeIndex = $curTriadeIndex+1;
	}
	
	$chunks= array_reverse($chunks);
	$res="";
	foreach($chunks as $chunk){
		$chunkName = $chunk->CalculateName();
		$res = $res . $chunkName;
	}
	$res = rtrim($res,$chunkName);
	if(trim($res)!=NULL)
	{
		$res = $res . " и " . $chunkName;
	}else{
		$res = $res . " " . $chunkName;
	}
	
	return $res;
}

$tryChunk = new Chunck(array(1,2,3),new TriadName("илјада","илјади",false),$hundretsNames,$DecadesNames,$FirstDecadeDigits,$SpecialDoubleDigitNames);
echo $tryChunk->DTriad->Female;
echo $tryChunk->Ones;
echo $tryChunk->CalculateName() . "\xA";

$tryChunk = new Chunck(array(1,2,2),new TriadName("илјада","илјади",false),$hundretsNames,$DecadesNames,$FirstDecadeDigits,$SpecialDoubleDigitNames);
echo $tryChunk->CalculateName() . "\xA";
$tryChunk = new Chunck(array(1,1,2),new TriadName("илјада","илјади",false),$hundretsNames,$DecadesNames,$FirstDecadeDigits,$SpecialDoubleDigitNames);
echo $tryChunk->CalculateName() . "\xA";
$tryChunk = new Chunck(array(1,0,3),new TriadName("илјада","илјади",false),$hundretsNames,$DecadesNames,$FirstDecadeDigits,$SpecialDoubleDigitNames);
echo $tryChunk->CalculateName() . "\xA";
$tryChunk = new Chunck(array(0,0,3),new TriadName("илјада","илјади",false),$hundretsNames,$DecadesNames,$FirstDecadeDigits,$SpecialDoubleDigitNames);
echo $tryChunk->CalculateName() . "\xA";

$tryChunk = new Chunck(array(3),new TriadName("илјада","илјади",false),$hundretsNames,$DecadesNames,$FirstDecadeDigits,$SpecialDoubleDigitNames);
echo $tryChunk->CalculateName() . "\xA";
$tryChunk = new Chunck(array(1,2),new TriadName("илјада","илјади",false),$hundretsNames,$DecadesNames,$FirstDecadeDigits,$SpecialDoubleDigitNames);
echo $tryChunk->CalculateName() . "\xA";

$tryChunk = new Chunck(array(1,0,0),new TriadName("илјада","илјади",false),$hundretsNames,$DecadesNames,$FirstDecadeDigits,$SpecialDoubleDigitNames);
echo $tryChunk->CalculateName() . "\xA";


?>