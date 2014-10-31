<?php
class Goodies{

    function rememberMe($userId) {
		$salt = "AnyXYZSTring";
		$rnd = mt_rand( 0, 0x7fffffff ) ^ crc32( $salt ) ^ crc32( microtime() );
		$secret = md5( $rnd+$userId );
		setcookie("cfusercookie", $secret, time()+60*60*24*100, "/");
		return $secret;
	}
	function getRemeberMe() {
		return $_COOKIE["cfusercookie"];
	}

}