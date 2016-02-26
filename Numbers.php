<?php
/**
 * Created by PhpStorm.
 * User: Ace
 * Date: 26.02.2016
 * Time: 11:32
 */

#Holds the gender dependant forms  of a word
class GenderDependantName {

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


#Holds the data for the word representations (singular and plural form, and gender) of a triad.
# A triad is a named number like (tousend, milion, bilion etc). This word was invented by me so use it with caution in public.
class QuantitiveDependantWord {

    public $Singular;
    public $Plural;
    public $IsMasculin;

    public function __construct($sing,$plr,$ismsc) {
        $this->Singular = $sing;
        $this->Plural=$plr;
        $this->IsMasculin = $ismsc;
    }
}

#Holds the data of a 3 (or less) digits chunk. Can convert it to the word
class TriDigitChunk{
    public $Hundrets;
    public $Decades;
    public $Ones;
    public $Triad;
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
        $this->Triad=$triad;
        $this->Ones="";
        $this->Decades="";
        $this->Hundrets="";
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

    #Returns the word reprensentation of this tri digit chunk
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

    private function CalculateTriadName()
    {
        #000
        if((!isset($this->Ones)||$this->Ones=="0") && (!isset($this->Decades)||$this->Decades=="0") && (!isset($this->Hundrets)||$this->Hundrets=="0"))
        {
            return NULL;
        }

        #singular
        if($this->Ones == "1" && $this->Decades!="1"){
            return $this->Triad->Singular;
        }

        #plural
        return $this->Triad->Plural;
    }

    private  function CalculateHundretsName()
    {
        if(isset($this->Hundrets))
        {
            return $this->HundretssMap[$this->Hundrets];
        }

        return "";
    }

    private  function CalculateDecadesAndOnesName()
    {
        $decadesAndOnesString=NULL;
        if(array_key_exists ($this->Decades . $this->Ones, $this->SpecialsMap ))
        {
            #special case, no need for further calculations;
            $ddName = $this->SpecialsMap[$this->Decades . $this->Ones];
            if($this->Triad->IsMasculin){
                return  $ddName->Male;
            }else{
                return  $ddName->Female;
            }
        }

        $onesName = $this->OnessMap[$this->Ones];
        if($this->Triad->IsMasculin){
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

#the public api function
#class used for converting digit strings to words from macedonian language
class NuberToTextConvertor{

    public $SpecialDoubleDigitNames;
    public $FirstDecadeDigits;
    public $DecadesNames;
    public $TriadeNamesMap;
    public $HundretsNames;
    public $CurrencyName;

    public  function __construct()
    {
        $this->CurrencyName = new QuantitiveDependantWord("денар","денари",true);
        $this->SpecialDoubleDigitNames=[
            ""=>new GenderDependantName("",""),
            "10"=>new GenderDependantName("десет",""),
            "11"=>new GenderDependantName("единаесет",""),
            "12"=>new GenderDependantName("дванаесет",""),
            "13"=>new GenderDependantName("тринаесет",""),
            "14"=>new GenderDependantName("четиринаесет",""),
            "15"=>new GenderDependantName("петнаесет",""),
            "16"=>new GenderDependantName("шестнаесет",""),
            "17"=>new GenderDependantName("седумнаесет",""),
            "18"=>new GenderDependantName("осумнаесет",""),
            "19"=>new GenderDependantName("деветнаесет","")
        ];

        $this->FirstDecadeDigits = [
            ""=>new GenderDependantName("",""),
            "0"=>new GenderDependantName("",""),
            "1"=>new GenderDependantName("еден","еднa"),
            "2"=>new GenderDependantName("двa","две"),
            "3"=>new GenderDependantName("три",""),
            "4"=>new GenderDependantName("четири",""),
            "5"=>new GenderDependantName("пет",""),
            "6"=>new GenderDependantName("шест",""),
            "7"=>new GenderDependantName("седум",""),
            "8"=>new GenderDependantName("осум",""),
            "9"=>new GenderDependantName("девет","")
        ];

        $this->DecadesNames=[
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


        $this->TriadeNamesMap =[
            0=>new QuantitiveDependantWord("","",true),
            1=>new QuantitiveDependantWord("илјада","илјади",false),
            2=>new QuantitiveDependantWord("милион","милиони",true),
        ];

        $this->HundretsNames=[
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
    }

    #Substring for arrays. Returns a new aray consisting of the first $n elements of
    # $arr. If $n is larger then the length of $arr it returns a copy of the array.
    public function FirstN($arr,$n){
        $i=0;
        $arrLen = count($arr);
        $res = array();
        for($i=0; $i<$arrLen && $i<$n; $i++)
        {
            array_push($res,$arr[$i]);
        }
        return $res;
    }

    #gets the number string as an reversed array of digits and omits any non digit characters
    private function ParseInputNuberToReversedArray($inputNum)
    {
        $numberStr=$this->PurifyNumber($inputNum);
        $numArr = str_split($numberStr,1);
        return array_reverse($numArr);
    }

    # Removes all non digit letters from a string.
    public function PurifyNumber($inpureNumber)
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

    # Gets the correct form of the currency word based on gramatical number category for the reversed representation of a number
    #$reversedNumArr must be a valid number representation as an array of digits in reversed order
    # returns the currency word in the correct form as a string
    public function CalculateCurrency($reversedNumArr)
    {
        $isCurencySingular = false;
        if(count($reversedNumArr)>0 && $reversedNumArr[0]=="1"){
            $isCurencySingular = true;
        }
        if(count($reversedNumArr)>1 && $reversedNumArr[0]=="1"&& $reversedNumArr[1]=="1"){
            $isCurencySingular = false;
        }

        if($isCurencySingular){
            return $this->CurrencyName->Singular;
        }else{
            return $this->CurrencyName->Plural;
        }
    }

    #Splits the $reversed array represntation of a number in tri digit chunks.
    #Returns an array of TriDigitChunk in the order from most significant to least significant digit groups
    public  function CreateTriDigitChunks($reversedNumArr){
        $triadIndex=0;
        $chunks = array();
        while(count($reversedNumArr)>0){
            $triChunk = $this->FirstN($reversedNumArr,3);
            $reversedNumArr = array_slice($reversedNumArr,count($triChunk),count($reversedNumArr));
            $triChunk = array_reverse($triChunk);
            $triad = $this->TriadeNamesMap[$triadIndex];
            $chunk = new TriDigitChunk($triChunk,$triad,$this->HundretsNames,$this->DecadesNames,$this->FirstDecadeDigits,$this->SpecialDoubleDigitNames);
            array_push($chunks,$chunk);
            $triadIndex++;
        }

        #get an array of chunk names in the correct order. Omit chunks that result in empty string
       return array_reverse($chunks);
    }

    #Returns a new array of the word reprensentation of all the TriDigitChunk in the input array. Order is preserved.
    #Resulting array ommits any word repreentations that are empty or white space;
    public function CalculateNames($chunkArr)
    {
        $names = array();
        foreach($chunkArr as $chnk){
            $chunkName = $chnk->CalculateName();
            if(str_replace(" ","",$chunkName)!=""){
                array_push($names,$chunkName);
            }
        }
        return $names;
    }

    #Creates a string representing the names of all TriDigitChunk names in the $names array.
    #It adds the needed connecting common words in order for the result to be gramatically corect
    #$names is a list of TriDigitChunkNames
    public function ImplodeChunkNames($names)
    {
        $res="";
        if(count($names)==1){
            $res = $names[0];
        }else{
            if(count($names)==0){
                $res =  "0";
            }else{
                for($i=0; $i<count($names)-1; $i++){
                    $res=  $res ." ". $names[$i];
                }

                $res = $res . " и " . $names[count($names)-1];
            }
        }
        return  str_replace("  "," ",trim($res));;
    }

    #Converts a string of digits into the appropriate word strings of the macedonian language.
    #The allowed range is 0-999 999 999.
    public function GetNameForNumber($inpureNumber){

        $reversedNumArr = $this->ParseInputNuberToReversedArray($inpureNumber);

        $currencyWord = $this->CalculateCurrency($reversedNumArr);

        $chunks = $this->CreateTriDigitChunks($reversedNumArr);

        $chunkNames=$this->CalculateNames($chunks);

        $res = $this->ImplodeChunkNames($chunkNames);

        return $res . " " . $currencyWord;
    }
}



function PrintArr($arr){
    foreach($arr as $a)
    {
        print $a . " | ";
    }
    echo "<br/>";
}








#print GetNameForNumber("1234567",$TriadeNamesMap,$hundretsNames,$DecadesNames,$FirstDecadeDigits,$SpecialDoubleDigitNames);

?>
<html>
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
        $converter = new NuberToTextConvertor();

        echo $converter->GetNameForNumber($_GET["p"] );
    }

?>

</body>
</html>


