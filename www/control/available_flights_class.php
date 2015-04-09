<?php
class Flight {
    var $departureAirport;
	var $arrivalAirport;
	var $equipment;
	var $markettingAirline;
	var $marketingCabin;
	var $arrivalDateTime;
	var $departureDateTime;
	var $departureDate;
	var $flightNumber;
	var $onTimeRate ;
	var $stopQuantity;
	var $journeyDuration;
	var $ticket;
	
	var $fareReference;
	var $fareReferenceID;
	var $rph;
	var $adultPrice;
	var $childPrice;
	var $infPrice;
	var $adultPriceSom;
	var $childPriceSom;
	var $infPriceSom;
	var $resBookDesigCode;
	var $resBookDesigQuantity;
	
	var $timestamp;
	
	var $books = array();
	
	function test(){
		echo $this->departureDateTime.": ".$this->departureAirport."->".$this->arrivalAirport." price: ".$this->adultPrice;
		print("<hr/>");
	}
	
	function departure_date(){
		return date('Y-m-d', strtotime($this->departureDateTime));
	}
	
	function updateSomPrice($kurs, $fee=5){
		$this->adultPriceSom = ceil(($this->adultPrice+$fee) * $kurs); // 5$ service fee
		$this->childPriceSom = ceil(($this->childPrice+$fee) * $kurs); // 5$ service fee
		$this->infPriceSom = ceil($this->infPrice * $kurs);
	}
}

class Book {
    var $rph;
	var $resBookDesigCode;
	var $resBookDesigQuantity;
	var $fareReference;
	var $fareReferenceID;
	var $adultPrice;
	var $childPrice;
	var $infPrice;
	var $price_list = array();
	
}

class Price {
    var $passenger;
	var $base_price;
	var $price;
	var $tax_list = array();
}

class Tax {
    var $amount;
	var $code;	
}
?>