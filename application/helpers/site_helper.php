<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('makeDirektori'))

{
	function makeDirektori($path=null,$permision=0775){
		if($path!=null){
			if(!is_dir($path)){
				@mkdir($path,$permision);
				@copy('./resources/index.html', $path."/index.html");
			}
		}
	}
}

if( ! function_exists('changedate')){
	function changedate($date="",$lang="id"){
		$date = strtolower(trim($date));
		if($date=="01 january 1970" or $date==""){
			$date = "-";
		}else if($date!=""){
			if($lang=="id"){
				$date = str_replace(array("january","february","march","april","may","june","july","august","september","october","november","december","sunday","monday","tuesday","wednesday","thursday","friday","saturday"), array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember","Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu"),$date);
			}else if($lang=="en"){
				$date = str_replace(array("januari","februari","maret","april","mei","juni","juli","agustus","september","oktober","november","desember","minggu","senin","selasa","rabu","kamis","jum'at","sabtu"),array("January","February","March","April","May","June","July","August","September","October","November","December","Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"),$date);
			}
		}
		return $date;
	}
}


if ( ! function_exists('moneyConvert'))
{
	function moneyConvert($data=0,$symbol="",$compress=false,$aftercomma=0)
	{
		$result 	= "";
		if($compress==true && $data>=1000000){
			if($data>=1000000000000){
				$result = number_format(($data/1000000000000),$aftercomma,",",".")." T";
			}else if($data>=1000000000){
				$result = number_format(($data/1000000000),$aftercomma,",",".")." M";
			}else if($data>=1000000){
				$result = number_format(($data/1000000),$aftercomma,",",".")." Jt";
			}
		}else{
			$result = number_format($data,2,",",".");
		}
		return $symbol." ".$result;
	}	
}

if ( ! function_exists('privateEmail'))
{
	function privateEmail($email)
	{
		$em   	= explode("@",$email);
	    $name 	= implode(array_slice($em, 0, count($em)-1), '@');
	    $len  	= strlen($name);
	    $front 	= "";
	    if($len>9){
	    	$front = substr($name,0, 2).str_repeat('*', $len-5).substr($name,$len-3, $len);
	    }else if($len>5){
	    	$front = substr($name,0, 2).str_repeat('*', $len-2);
	    }else{
	    	$front = substr($name,0, 1).str_repeat('*', $len-1);
	    }
	    return $front. "@" . end($em);   
	}	
}

if ( ! function_exists('privateText'))
{
	function privateText($text)
	{
	    $name 	= $text;
	    $len  	= strlen($text);
	    $front 	= "";
	    if($len>9){
	    	$front = substr($name,0, 2).str_repeat('*', $len-5).substr($name,$len-3, $len);
	    }else if($len>5){
	    	$front = substr($name,0, 2).str_repeat('*', $len-2);
	    }else{
	    	$front = substr($name,0, 1).str_repeat('*', $len-1);
	    }
	    return $front;   
	}	
}

if(! function_exists('integerToRoman')){
	function integerToRoman($integer){
	 	$integer = intval($integer);
	 	$result = '';
	 
	 	$lookup = array(
	 			'M' => 1000,
				'CM' => 900,
			 	'D' => 500,
			 	'CD' => 400,
			 	'C' => 100,
			 	'XC' => 90,
			 	'L' => 50,
			 	'XL' => 40,
			 	'X' => 10,
			 	'IX' => 9,
			 	'V' => 5,
			 	'IV' => 4,
			 	'I' => 1);
	 
	 	foreach($lookup as $roman => $value){
	  		$matches = intval($integer/$value);
		  	$result .= str_repeat($roman,$matches);
	 	 	$integer = $integer % $value;
	 	}
	 	return $result;
	}
}

if(! function_exists('numberTowords')){
	function numberTowords($number){
		$before_comma 	= trim(beforecomma($number));
		$after_comma 	= trim(aftercomma($number));
        return $before_comma." ".$after_comma;
    }

    function aftercomma($number){
    	$number 		= stristr($number,'.');
	  	$length 		= strlen($number);
	  	if($length==0){
	  		return "";
	  	}else{
	    	$angka 		= array("Nol", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan");
	  		$results 	= "Koma ";
	  		for ($i=1; $i < $length; $i++) { 
	  			$results .= " ".$angka[substr($number,$i,1)];
	  		}
	        return $results;
	    }
    }

    function beforecomma($number){
    	if ($number < 12){
	    	$angka 		= array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
	    	return " " . $angka[$number];
	  	}else if ($number < 20){
	    	return beforecomma($number - 10) . " Belas ";
	  	}else if ($number < 100){
	    	return beforecomma($number / 10) . " Puluh" . beforecomma($number % 10);
	  	}else if ($number < 200){
	    	return " Seratus" . beforecomma($number - 100);
	  	}else if ($number < 1000){
	    	return beforecomma($number / 100) . " Ratus" . beforecomma($number % 100);
	  	}else if ($number < 2000){
	    	return " Seribu" . beforecomma($number - 1000);
	  	}else if ($number < 1000000){
	    	return beforecomma($number / 1000) . " Ribu" . beforecomma($number % 1000);
	  	}else if ($number < 1000000000){
	    	return beforecomma($number / 1000000) . " Juta" . beforecomma($number % 1000000);
	  	}else if ($number < 1000000000000){
	    	return beforecomma($number / 1000000000) . " Milyar" . beforecomma($number % 1000000000);
	  	}else if ($number < 1000000000000000){
	    	return beforecomma($number / 1000000000000) . " Triliun" . beforecomma($number % 1000000000000);
	  	}
	}
}

if(! function_exists('validateDate')){
	function validateDate($date, $format = 'Y-m-d'){
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) === $date;
	}
}
?>