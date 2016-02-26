<?php
/**
 * Created by PhpStorm.
 * User: Ace
 * Date: 26.02.2016
 * Time: 11:32
 */


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
        $this->Ones="";
        $this->Decades="";
        $this->Hundrets="";
        #$digits = array_reverse($digits);
        $digitsLength = count($digits);
        if($digitsLength>=1){
            $this->Ones=$digits[$digitsLength-1];
        }
        if($digitsLength>=2){
            $this->Decades=$digits[$digitsLength-2];
        }

        if($digitsLength>=3){
            $this->Hundrets=$digits[$digitsLength-3];
        }

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
        if($this->Ones == "1" && $this->Decades!="1"){
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

        $decadesNameString="";
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
    "10"=>new DoubleDigitsName("и десет",""),
    "11"=>new DoubleDigitsName("и единаесет",""),
    "12"=>new DoubleDigitsName("и дванаесет",""),
    "13"=>new DoubleDigitsName("и тринаесет",""),
    "14"=>new DoubleDigitsName("и четиринаесет",""),
    "15"=>new DoubleDigitsName("и петнаесет",""),
    "16"=>new DoubleDigitsName("и шестнаесет",""),
    "17"=>new DoubleDigitsName("и седумнаесет",""),
    "18"=>new DoubleDigitsName("и осумнаесет",""),
    "19"=>new DoubleDigitsName("и деветнаесет","")
];

$FirstDecadeDigits = [
    ""=>new DoubleDigitsName("",""),
    "0"=>new DoubleDigitsName("",""),
    "1"=>new DoubleDigitsName("и еден","и еднa"),
    "2"=>new DoubleDigitsName("и двa","и две"),
    "3"=>new DoubleDigitsName("и три",""),
    "4"=>new DoubleDigitsName("и четири",""),
    "5"=>new DoubleDigitsName("и пет",""),
    "6"=>new DoubleDigitsName("и шест",""),
    "7"=>new DoubleDigitsName("и седум",""),
    "8"=>new DoubleDigitsName("и осум",""),
    "9"=>new DoubleDigitsName("и девет","")
];

$DecadesNames=[
    ""=>"",
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
    ""=>"",
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

function PrintArr($arr){
    foreach($arr as $a)
    {
        print $a . " | ";
    }
    echo "<br/>";
}

function FirstN($arr,$n){
    $i=0;
    $arrLen = count($arr);
    $res = array();
    for($i=0; $i<$arrLen && $i<$n; $i++)
    {
       array_push($res,$arr[$i]);
    }
    return $res;
}

function PurifyNumber($inpureNumber)
{
    $inpureNumber = str_split($inpureNumber,1);
    $pureNumber = array();
    foreach($inpureNumber as $digit){
        if(is_numeric ($digit)){
            array_push($pureNumber,$digit);
        }
    }
    return implode("",$pureNumber);
}



$numberStr = PurifyNumber("10,000");
$reversedNumArr = str_split($numberStr,1);
$reversedNumArr = array_reverse($reversedNumArr);
$triadIndex=0;
$chunks = array();
while(count($reversedNumArr)>0){
    $triChunk = FirstN($reversedNumArr,3);
    $reversedNumArr = array_slice($reversedNumArr,count($triChunk),count($reversedNumArr));
    $triChunk = array_reverse($triChunk);
    $triad = $TriadeNamesMap[$triadIndex];
    $chunk = new Chunck($triChunk,$triad,$hundretsNames,$DecadesNames,$FirstDecadeDigits,$SpecialDoubleDigitNames);
    array_push($chunks,$chunk);
    $triadIndex++;
}

$chunks=array_reverse($chunks);
$res = "";
for($i = 0; $i<count($chunks)-1; $i++){
    $res = $res . $chunks[$i]->CalculateName() . " ";
}

$lastChunkName = $chunks[count($chunks)-1]->CalculateName();
if(trim($res)!=""){
   $res =   $res . " и " . $lastChunkName;
}else{
    $res =  $lastChunkName;
}

$res = trim($res);
$res = rtrim($res,"и");
print str_replace("  "," ",trim($res));

?>