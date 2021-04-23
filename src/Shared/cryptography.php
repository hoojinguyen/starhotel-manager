<?php

namespace Romi\Shared;

class Cryptography {

	private static $cipher = 'AES-256-CBC';
	private static $key = 'secret key';
	private static $options = OPENSSL_RAW_DATA;
	
	public static function Encrypt($rawText) {
		$ivlen = openssl_cipher_iv_length(self::$cipher);
		$iv = openssl_random_pseudo_bytes($ivlen);
		$cipherRaw = openssl_encrypt($rawText, self::$cipher, self::$key, self::$options, $iv);
		$hmac = hash_hmac('sha256', $cipherRaw, self::$key, $as_binary=true);
		$cipherText = base64_encode( $iv.$hmac.$cipherRaw );
		return $cipherText;
	}

	public static function Decrypt($cipherText) {
		$decoded = base64_decode($cipherText);
		$ivlen = openssl_cipher_iv_length(self::$cipher);
		$iv = substr($decoded, 0, $ivlen);
		$hmac = substr($decoded, $ivlen, $sha2len=32);
		$cipherRaw = substr($decoded, $ivlen+$sha2len);
		$originalText = openssl_decrypt($cipherRaw, self::$cipher, self::$key, self::$options, $iv);
		$calcmac = hash_hmac('sha256', $cipherRaw, self::$key, $as_binary=true);
		if (hash_equals($hmac, $calcmac)){
			return $originalText;
		}else{
			return '';
		}
		
	}

	//Consider to use openssl_private_encrypt, openssl_private_decrypt
	//Consider to use openssl_public_encrypt, openssl_public_decrypt
	//openssl_pkey_get_private & openssl_pkey_get_public
}
