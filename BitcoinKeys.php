<?php

// Copyright (c) 2021 Alexandre Vialle
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions are met:
// 1. Redistributions of source code must retain the above copyright notice,
//    this list of conditions and the following disclaimer.
// 2. Redistributions in binary form must reproduce the above copyright notice,
//    this list of conditions and the following disclaimer in the documentation
//    and/or other materials provided with the distribution.
// 3. Neither the name of the copyright holder nor the names of its contributors
//    may be used to endorse or promote products derived from this software
//    without specific prior written permission.
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
// AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
// IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
// ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
// LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
// CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
// SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
// INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
// CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
// ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
// POSSIBILITY OF SUCH DAMAGE.

if (!extension_loaded('openssl')) {
    throw new \Exception('openssl extension is not loaded');
}

require_once("base58.php");

abstract class BitcoinNetworks {
    const Main = 0;
    const Test = 1;
}

class BitcoinKeys {
    private $secret;
    private $public;
    private $address;
    public static $WIF_NETWORKS = array('80', 'ef');
    public static $ADDR_NETWORKS = array('00', '6f');
    private $network = 0;
    static function newKeyPair() {
        $skr = openssl_pkey_new(array(
            'private_key_type' => OPENSSL_KEYTYPE_EC,
            'curve_name' => 'secp256k1'
        ));

        $ki = openssl_pkey_get_details($skr);
        $secret = bin2hex($ki['ec']['d']);
        $public = '04'.bin2hex($ki['ec']['x']).bin2hex($ki['ec']['y']);

        return array($secret, $public);
    }
    static function secretToWif($secret, $network = '80') {
        $sk = $network.$secret;
        $bh1 = openssl_digest(hex2bin($sk), 'sha256', true);
        $h2 = bin2hex(openssl_digest($bh1, 'sha256', true));
        $f4 = substr($h2, 0, 8);
        return base58::encode($sk.$f4);
    }
    static function publicToAddress($public, $network = '00') {
        $bh1 = openssl_digest(hex2bin($public), 'sha256', true);
        $h2 = bin2hex(openssl_digest($bh1, 'ripemd160', true));
        $n2 = $network.$h2;
        $bh3 = openssl_digest(hex2bin($n2), 'sha256', true);
        $h4 = bin2hex(openssl_digest($bh3, 'sha256', true));
        $f4 = substr($h4, 0, 8);
        $addr = $n2.$f4;
        return base58::encode($addr);
    }
    static function secretToPem($secret) {
        $der_data = hex2bin('302e0201010420'.$secret.'a00706052b8104000a');
        // 2e = 46 bytes (the count of the following) = 5 bytes + 32 bytes for the key + 9 bytes
        $pem = chunk_split(base64_encode($der_data), 64, "\n");
        $pem = "-----BEGIN EC PRIVATE KEY-----\n".$pem."-----END EC PRIVATE KEY-----\n";
        return $pem;
    }
    static function wifToKeyPair($wif) {
        if (strlen($wif) !== 51) throw new \Exception('WIF must be 51 characters long');
        $hex = base58::decode($wif);
        if (strlen($hex) !== 74) throw new \Exception('WIF has invalid data length');
        $network = substr($hex, 0, 2);
        $secret = substr($hex, 2, 64);
        $checksum = substr($hex, 66);
        if (strlen($checksum) !== 8) throw new \Exception('WIF has an invalid checksum length');
        $sk = $network.$secret;
        $bh1 = openssl_digest(hex2bin($sk), 'sha256', true);
        $h2 = bin2hex(openssl_digest($bh1, 'sha256', true));
        $f4 = substr($h2, 0, 8);
        if ($f4 !== $checksum) throw new \Exception('WIF has an invalid checksum');
        $skr = openssl_pkey_get_private(self::secretToPem($secret));
        $ki = openssl_pkey_get_details($skr);
        $public = '04'.bin2hex($ki['ec']['x']).bin2hex($ki['ec']['y']);
        return array($secret, $public, $network);
    }
    public function __construct($wif = false) {
        if ($wif !== false) $this->fromWif($wif);
        else $this->create();
    }
    public function setNetwork($network) {
        $this->network = $network;
    }
    public function create() {
        list($this->secret, $this->public) = self::newKeyPair();
        $this->address = self::publicToAddress($this->public, self::$ADDR_NETWORKS[$this->network]);
    }
    public function fromWif($wif) {
        $net = '';
        list($this->secret, $this->public, $net) = self::wifToKeyPair($wif);
        $this->network = array_search($net, self::$WIF_NETWORKS, true);
        if ($this->network === false) throw new \Exception('WIF has an unknown network: 0x'.$net);
        $this->address = self::publicToAddress($this->public, self::$ADDR_NETWORKS[$this->network]);
    }
    public function getSecret() {
        return $this->secret;
    }
    public function getPublic() {
        return $this->public;
    }
    public function getAddress() {
        return $this->address;
    }
    public function getNetwork() {
        return $this->network;
    }
    public function getWif() {
        return self::secretToWif($this->secret, self::$WIF_NETWORKS[$this->network]);
    }
}