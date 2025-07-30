<?php

namespace Lan\Services\Security;

use Lan\Contracts\Services\Security\CryptServiceInterface;

class CryptService implements CryptServiceInterface
{
    public function __construct(
        private $padding = true,
        private $mode = MCRYPT_MODE_CBC
    )
    {
    }

    public function encrypt(
        string $stringToCrypt,
        string $key,
        string $iv,
        bool $is_base = true
    ): string
    {
        $key = hash('sha256', $key, true);

        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', $this->mode, '');
        mcrypt_generic_init($td, $key, $this->hexToStr($iv));
        if ($this->padding) {
            $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, $this->mode);
            $pad = $block - (strlen($stringToCrypt) % $block);
            $stringToCrypt .= str_repeat(chr($pad), $pad);
        }
        $encrypted = mcrypt_generic($td, $stringToCrypt);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        if ($is_base) {
            return base64_encode($encrypted);
        } else {
            return $encrypted;
        }
    }

    public function decrypt(
        string $encryptedString,
        string $key,
        string $iv,
    ): string
    {
        $key = hash('sha256', $key, true);

        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', $this->mode, '');
        mcrypt_generic_init($td, $key, $this->hexToStr($iv));
        $str = mdecrypt_generic($td, base64_decode($encryptedString));
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return ($this->padding) ? $this->strippadding($str) : $str;
    }


    function hexToStr($hex)
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }

        return $string;
    }

    private function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    /*
      For PKCS7 padding
     */
    private function strippadding($string)
    {
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        if (preg_match("/$slastc{" . $slast . "}/", $string)) {
            $string = substr($string, 0, strlen($string) - $slast);
            return $string;
        } else {
            return false;
        }
    }
}
