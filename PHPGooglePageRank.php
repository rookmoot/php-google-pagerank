<?php

class PHPGooglePageRank {

  const SERVER_URI = 'toolbarqueries.google.com';
  const SERVER_TIMEOUT = 10;
  const USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.93 Safari/537.36';

  private $_url;
  
  public function __construct($url) {
    $this->_url = $url;
  }

  public function get() {
    $request = sprintf(
      'http://%s/tbr?client=navclient-auto&ch=%s&ie=UTF-8&oe=UTF-8&features=Rank&q=info:%s',
      self::SERVER_URI,
      $this->_getCheckSum(),
      urlencode($this->_url)
    );


    $request = 'http://toolbarqueries.google.com/tbr?client=navclient-auto&ch='.$this->_getCheckSum().'&features=Rank&q=info:'.$this->_url;
    echo $request.'<br>';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $request);
    curl_setopt($ch, CURLOPT_USERAGENT, self::USER_AGENT);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, self::SERVER_TIMEOUT);
    $result = curl_exec($ch);
    if (curl_error($ch)) {
      throw new Exception(curl_error($ch));
    }
    if (empty($result)) {
      return -1;
    }
    return intval(substr($result, strrpos($result, ':')+1));
  }

  private function _getCheckSum() {
    $hash = $this->_pr_hash($this->_url);
    $ch = $this->_pr_hash_check($hash);
    return $ch;
    //    return $this->_checkHash($this->_hashUrl($this->_url));
  }

  /**
        Does some magic I saw on the internet
  */
  private function _pr_hash($String)
  {
    $Check1 = self::_pr_str2num($String, 0x1505, 0x21);
    $Check2 = self::_pr_str2num($String, 0, 0x1003F);

    $Check1 >>= 2;
    $Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
    $Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
    $Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);

    $T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F );
    $T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );

    return ($T1 | $T2);
  }

  /**
        Does some magic I saw on the internet
  */
  private function _pr_hash_check($Hashnum)
  {
    $CheckByte = 0;
    $Flag = 0;

    $HashStr = sprintf('%u', $Hashnum) ;
    $length = strlen($HashStr);

    for ($i = $length - 1;  $i >= 0;  $i --) {
      $Re = $HashStr{$i};
      if (1 === ($Flag % 2)) {
	$Re += $Re;
	$Re = (int)($Re / 10) + ($Re % 10);
      }
      $CheckByte += $Re;
      $Flag ++;
    }

    $CheckByte %= 10;
    if (0 !== $CheckByte) {
      $CheckByte = 10 - $CheckByte;
      if (1 === ($Flag % 2) ) {
	if (1 === ($CheckByte % 2)) {
	  $CheckByte += 9;
	}
	$CheckByte >>= 1;
      }
    }
    return '7'.$CheckByte.$HashStr;
  }

  private function _pr_str2num($Str,$Check,$Magic)
  {
    $Int32Unit = 4294967296;  // 2^32

    $length = strlen($Str);
    for ($i = 0; $i < $length; $i++) {
      $Check *= $Magic;
      //If the float is beyond the boundaries of integer (usually +/- 2.15e+9 = 2^31),
      //  the result of converting to integer is undefined
      //  refer to http://www.php.net/manual/en/language.types.integer.php
      if ($Check >= $Int32Unit) {
	$Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
	//if the check less than -2^31
	$Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
      }
      $Check += ord($Str{$i});
    }
    return $Check;
  }
}