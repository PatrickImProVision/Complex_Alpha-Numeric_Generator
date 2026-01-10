<?php
function unique_key_generator($default_code_length=1,$default_code_type=1,$default_code=false,$code_random=true){
//Sub funkce code_count(); zajistuje vypocet pozice klice jestly je 1. nebo 80. atp.
function code_count($code_base,$array_chars) {
        $characters = array_flip($array_chars);
        $character_keys = $array_chars;
 
        $code_characters = str_split($code_base);
 
        $number = 0;
        for ($i = 0; $i < count($code_characters); $i++) {
                $number = $number * count($characters) + $character_keys[$code_characters[$i]];
        }
        return $number; # pokud chceš tak ještě +1
}
//Casovy otisk -> Time stamp
$code_time = time();
//Informace o tom kolik druhu klicu muze funkce poskytnout
$code_max_type = 5;
//Druhy klicu
if ($default_code_type==1){
//Sestaveni array tabulky s jednotlivimy znaky
$characters = array_merge(range('A','Z'), range('a','z'));
//Jmeno generovaneho klice
$code_name = '[A-Z,a-z]';
//Cislo generovaneho klice
$code_number = 1;
}
if ($default_code_type==2){
//Megaupload.com style
//Sestaveni array tabulky s jednotlivimy znaky
$characters = array_merge(range('A','Z'), range('0','9'));
//Jmeno generovaneho klice
$code_name = '[A-Z,0-9]';
//Cislo generovaneho klice
$code_number = 2;
}
if ($default_code_type==3){
//Sestaveni array tabulky s jednotlivimy znaky
$characters = array_merge(range('a','z'), range('0','9'));
//Jmeno generovaneho klice
$code_name = '[a-z,0-9]';
//Cislo generovaneho klice
$code_number = 3;
}
if ($default_code_type==4){
//Sestaveni array tabulky s jednotlivimy znaky
$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
//Jmeno generovaneho klice
$code_name = '[A-Z,a-z,0-9]';
//Cislo generovaneho klice
$code_number = 4;
}
if ($default_code_type>4){
//Youtube.com style
//Sestaveni array tabulky s jednotlivimy znaky
$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'),array('-','_'));
//Jmeno generovaneho klice
$code_name = '[A-Z,a-z,0-9,-_]';
//Cislo generovaneho klice
$code_number = 5;
}
//Vypocet kolik klicu muze generator poskytnout
$count_characters = count($characters);
$count_range = pow($count_characters, $default_code_length);
//Oznaceni konce ve vypoctu klicu
$character_end = 'Z';
$character_start = '';
$array_chars = array_flip($characters);
//Podminka slouzi pro detekci jestly ma funkce vygenerovat pocatecni ,nahodny nebo nasledujici klic
 if ($default_code==false){
//Generator nahodneho nebo pocatecniho klice
for ($n=1;$default_code_length>=$n;$n++){
if ($code_random){
//Generace nahodneho klice
$character_start .= $characters[rand(0,($count_characters-1))];
}else{
//Generace pocatecniho klice
$character_start .= 'A';
}
}
$code_base = $character_start;
                return array('code_base'=>$code_base,'code_base_md5'=>md5($code_base),'code_base_sha1'=>sha1($code_base),'code_base64_encode'=>base64_encode($code_base),'code_time'=>$code_time,'code_count'=>code_count($code_base,$array_chars),'code_range'=>$count_range,'code_message'=>'','code_name'=>$code_name,'code_type'=>$code_number,'code_max_type'=>$code_max_type,'code_length'=>$default_code_length);
}else{
//Generace nasledujiciho klice
    $chars = $characters;
    $code_array = str_split($default_code);
    // Starts searching for the next character capable of increasing, or different from Z
    // Note that initiates the last character to the first
    for($i = count($code_array)-1;$i>-1;$i--){
        if($code_array[$i] == $character_end){
            if($i=='0'){
                //If equal to Z and is the first character, mental increases the length and zeroes
                $code_array = array_fill(0,count($code_array) + 1,0);
$code_base = implode("",$code_array);
//Podminka hlida generaci spravneho klice, pokud je klic spravne dostanete -> 'code_message'=>'' jinak je vygenerovany klic spatne nebo uz funkce vygenerovala maximalni pocet klicu
if ($default_code_length==strlen($code_base)){
        return array('code_base'=>$code_base,'code_base_md5'=>md5($code_base),'code_base_sha1'=>sha1($code_base),'code_base64_encode'=>base64_encode($code_base),'code_range'=>$count_range,'code_count'=>code_count($code_base,$array_chars),'code_time'=>$code_time,'code_message'=>'','code_name'=>$code_name,'code_type'=>$code_number,'code_max_type'=>$code_max_type,'code_length'=>$default_code_length);
                    }elseif($default_code_length<strlen($code_base)){
                return array('code_base'=>$code_base,'code_base_md5'=>md5($code_base),'code_base_sha1'=>sha1($code_base),'code_base64_encode'=>base64_encode($code_base),'code_range'=>$count_range,'code_count'=>code_count($code_base,$array_chars),'code_time'=>$code_time,'code_message'=>'is_upper_or_full','code_name'=>$code_name,'code_type'=>$code_number,'code_max_type'=>$code_max_type,'code_length'=>$default_code_length);
                    }else{
                return array('code_base'=>$code_base,'code_base_md5'=>md5($code_base),'code_base_sha1'=>sha1($code_base),'code_base64_encode'=>base64_encode($code_base),'code_range'=>$count_range,'code_count'=>code_count($code_base,$array_chars),'code_time'=>$code_time,'code_message'=>'is_lower','code_name'=>$code_name,'code_type'=>$code_number,'code_max_type'=>$code_max_type,'code_length'=>$default_code_length);
}
            }else{
                if($code_array[$i -1] != $character_end){
                    // If the character is different from previous Z, increment it and resets the current and subsequent
                    // If the character is above the first, also works because it increases and the other resets
                    $code_array[$i -1] = $chars[array_search($code_array[$i -1],$chars) + 1];
                    for($j = $i; $j < count($code_array); $j++){
                        $code_array[$j] = '0';
                    }
$code_base = implode("",$code_array);
//Podminka hlida generaci spravneho klice, pokud je klic spravne dostanete -> 'code_message'=>'' jinak je vygenerovany klic spatne nebo uz funkce vygenerovala maximalni pocet klicu
if ($default_code_length==strlen($code_base)){
        return array('code_base'=>$code_base,'code_base_md5'=>md5($code_base),'code_base_sha1'=>sha1($code_base),'code_base64_encode'=>base64_encode($code_base),'code_range'=>$count_range,'code_count'=>code_count($code_base,$array_chars),'code_time'=>$code_time,'code_message'=>'','code_name'=>$code_name,'code_type'=>$code_number,'code_max_type'=>$code_max_type,'code_length'=>$default_code_length);
                    }elseif($default_code_length<strlen($code_base)){
                return array('code_base'=>$code_base,'code_base_md5'=>md5($code_base),'code_base_sha1'=>sha1($code_base),'code_base64_encode'=>base64_encode($code_base),'code_range'=>$count_range,'code_count'=>code_count($code_base,$array_chars),'code_time'=>$code_time,'code_message'=>'is_upper_or_full','code_name'=>$code_name,'code_type'=>$code_number,'code_max_type'=>$code_max_type,'code_length'=>$default_code_length);
                    }else{
                return array('code_base'=>$code_base,'code_base_md5'=>md5($code_base),'code_base_sha1'=>sha1($code_base),'code_base64_encode'=>base64_encode($code_base),'code_range'=>$count_range,'code_count'=>code_count($code_base,$array_chars),'code_time'=>$code_time,'code_message'=>'is_lower','code_name'=>$code_name,'code_type'=>$code_number,'code_max_type'=>$code_max_type,'code_length'=>$default_code_length);
}
                }
            }
 
        }else{
                // calculate the next character, or increments the current
                $code_array[$i] = $chars[array_search($code_array[$i],$chars) + 1];
                if($i == '0'){
                    // If the first character, meaning others Salo z
                     // That is, they reset
                    $novo_array = array_fill(0,count($code_array),0);
                    $novo_array[0] = $code_array[$i];
                    $code_array = $novo_array;
                }
$code_base = implode("",$code_array);
//Podminka hlida generaci spravneho klice, pokud je klic spravne dostanete -> 'code_message'=>'' jinak je vygenerovany klic spatne nebo uz funkce vygenerovala maximalni pocet klicu
if ($default_code_length==strlen($code_base)){
        return array('code_base'=>$code_base,'code_base_md5'=>md5($code_base),'code_base_sha1'=>sha1($code_base),'code_base64_encode'=>base64_encode($code_base),'code_range'=>$count_range,'code_count'=>code_count($code_base,$array_chars),'code_time'=>$code_time,'code_message'=>'','code_name'=>$code_name,'code_type'=>$code_number,'code_max_type'=>$code_max_type,'code_length'=>$default_code_length);
                    }elseif($default_code_length<strlen($code_base)){
                return array('code_base'=>$code_base,'code_base_md5'=>md5($code_base),'code_base_sha1'=>sha1($code_base),'code_base64_encode'=>base64_encode($code_base),'code_range'=>$count_range,'code_count'=>code_count($code_base,$array_chars),'code_time'=>$code_time,'code_message'=>'is_upper_or_full','code_name'=>$code_name,'code_type'=>$code_number,'code_max_type'=>$code_max_type,'code_length'=>$default_code_length);
                    }else{
                return array('code_base'=>$code_base,'code_base_md5'=>md5($code_base),'code_base_sha1'=>sha1($code_base),'code_base64_encode'=>base64_encode($code_base),'code_range'=>$count_range,'code_count'=>code_count($code_base,$array_chars),'code_time'=>$code_time,'code_message'=>'is_lower','code_name'=>$code_name,'code_type'=>$code_number,'code_max_type'=>$code_max_type,'code_length'=>$default_code_length);
}
        }
    }
}
 
}
?>
