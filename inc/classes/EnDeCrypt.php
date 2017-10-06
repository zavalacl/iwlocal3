<?php

if (!class_exists('EnDeCrypt')) {

	class EnDeCrypt
	{
	    const CYPHER = 'blowfish';
	    const MODE   = 'cfb';
	
	    public function encrypt($key, $plaintext)
	    {
	        $td = mcrypt_module_open(self::CYPHER, '', self::MODE, '');
	        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	        mcrypt_generic_init($td, $key, $iv);
	        $crypttext = mcrypt_generic($td, $plaintext);
	        mcrypt_generic_deinit($td);
	        return $iv.$crypttext;
	    }
	
	    public function decrypt($key, $crypttext)
	    {
	        $plaintext = '';
	        $td        = mcrypt_module_open(self::CYPHER, '', self::MODE, '');
	        $ivsize    = mcrypt_enc_get_iv_size($td);
	        $iv        = substr($crypttext, 0, $ivsize);
	        $crypttext = substr($crypttext, $ivsize);
	        if ($iv)
	        {
	            mcrypt_generic_init($td, $key, $iv);
	            $plaintext = mdecrypt_generic($td, $crypttext);
	        }
	        return $plaintext;
	    }
	}
}

?>