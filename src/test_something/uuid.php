<?php
function create_guid() {
	$microTime = microtime();
	list($a_dec, $a_sec) = explode(" ", $microTime);
	$dec_hex = dechex($a_dec * 1000000);
	$sec_hex = dechex($a_sec);
	ensure_length($dec_hex, 5);
	ensure_length($sec_hex, 6);
	$guid = "";
	$guid .= $dec_hex;
	$guid .= create_guid_section(3);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= $sec_hex;
	$guid .= create_guid_section(6);
	
	return $guid;
}

function ensure_length(&$string, $length) {
	$strlen = strlen($string);
	if($strlen < $length) {
		$string = str_pad($string, $length, "0");
	} else {
		if($strlen > $length) {
			$string = substr($string, 0, $length);
		}
	}
}

function create_guid_section($characters) {
	$return = "";
	for($i = 0; $i < $characters; $i++) {
		$return .= dechex(mt_rand(0, 15));
	}
	
	return $return;
}

//echo create_guid();

function create_uuid($prefix = "") {    //可以指定前缀
	$str  = md5(uniqid(mt_rand(), true));
	$uuid = substr($str, 0, 8) . '-';
	$uuid .= substr($str, 8, 4) . '-';
	$uuid .= substr($str, 12, 4) . '-';
	$uuid .= substr($str, 16, 4) . '-';
	$uuid .= substr($str, 20, 12);
	
	return $prefix . $uuid;
}

echo create_uuid();