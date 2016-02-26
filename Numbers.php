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
        $res = str_replace("  ", " ", $res);
        $res = trim ($res);

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

        return "";
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

        if($decadesNameString!=""){
            return $decadesNameString . " и " .$onesNameString;
        }else{
            return $onesNameString;
        }
    }

}

$SpecialDoubleDigitNames=[
    ""=>new DoubleDigitsName("",""),
    "10"=>new DoubleDigitsName("десет",""),
    "11"=>new DoubleDigitsName("единаесет",""),
    "12"=>new DoubleDigitsName("дванаесет",""),
    "13"=>new DoubleDigitsName("тринаесет",""),
    "14"=>new DoubleDigitsName("четиринаесет",""),
    "15"=>new DoubleDigitsName("петнаесет",""),
    "16"=>new DoubleDigitsName("шеснаесет",""),
    "17"=>new DoubleDigitsName("седумнаесет",""),
    "18"=>new DoubleDigitsName("осумнаесет",""),
    "19"=>new DoubleDigitsName("деветнаесет","")
];

$FirstDecadeDigits = [
    ""=>new DoubleDigitsName("",""),
    "0"=>new DoubleDigitsName("",""),
    "1"=>new DoubleDigitsName("еден","еднa"),
    "2"=>new DoubleDigitsName("двa","две"),
    "3"=>new DoubleDigitsName("три",""),
    "4"=>new DoubleDigitsName("четири",""),
    "5"=>new DoubleDigitsName("пет",""),
    "6"=>new DoubleDigitsName("шест",""),
    "7"=>new DoubleDigitsName("седум",""),
    "8"=>new DoubleDigitsName("осум",""),
    "9"=>new DoubleDigitsName("девет","")
];

$DecadesNames=[
    ""=>"",
    "0"=>"",
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

function LcTrim($string,$search){
    print "substring is:" . substr($string,0,strlen($search));
    print " search is " . $search;
    if(substr($string,0,strlen($search))==$search){
        return substr($string,strlen($search));
    }
    return $string;
}



function RcTrim($string,$search){
    if(substr($string,$search,0)==$search){
        return substr($string,-strlen($search));
    }
    return $string;
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


function GetNameForNumber($inpureNumber,$TriadeNamesMap,$hundretsNames,$DecadesNames,$FirstDecadeDigits,$SpecialDoubleDigitNames){
    $numberStr = PurifyNumber($inpureNumber);
    $reversedNumArr = str_split($numberStr,1);
    $reversedNumArr = array_reverse($reversedNumArr);
    $singular = false;
    if(count($reversedNumArr)>0 && $reversedNumArr[0]=="1"){
        $singular = true;
    }
    if(count($reversedNumArr)>1 && $reversedNumArr[0]=="1"&& $reversedNumArr[1]=="1"){
        $singular = false;
    }
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
    $names = array();
    foreach($chunks as $chnk){
        $chunkName = $chnk->CalculateName();
        if(str_replace(" ","",$chunkName)!=""){
            array_push($names,$chunkName);
        }
    }

    $res="";
    if(count($names)==1){
        $res = $names[0];
    }else{
        if(count($names)==0){
            $res =  "0";
        }else{
            $i=0;
            for($i=0; $i<count($names)-1; $i++){
                $res=  $res ." ". $names[$i];
            }

            $res = $res . " и " . $names[count($names)-1];
        }
    }

    $res = str_replace("  "," ",trim($res));
    if($singular){
        return $res . " денар";
    }else{
        return $res . " денари";
    }

}






#print GetNameForNumber("1234567",$TriadeNamesMap,$hundretsNames,$DecadesNames,$FirstDecadeDigits,$SpecialDoubleDigitNames);

?>
<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<form method="get" action="<?php $_PHP_SELF ?>" >
    <label>Број:</label>
    <br/>
    <input type="number" value="<?php echo $_GET["p"] ?>" name="p">
</form>

<br/>
<?php
    if (empty($_GET["p"] )) {
        echo "Бројот е задолжителен, внесете повторно";
    }else{
        echo GetNameForNumber($_GET["p"] ,$TriadeNamesMap,$hundretsNames,$DecadesNames,$FirstDecadeDigits,$SpecialDoubleDigitNames);
    }

?>

</body>
</html>


