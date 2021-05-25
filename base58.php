<?php

// Code from Jan Moritz Lindemann
// This is free and unencumbered software released into the public domain.
// Anyone is free to copy, modify, publish, use, compile, sell, or distribute
// this software, either in source code form or as a compiled binary, for any
// purpose, commercial or non-commercial, and by any means. In jurisdictions
// that recognize copyright laws, the author or authors of this software
// dedicate any and all copyright interest in the software to the public domain.
// We make this dedication for the benefit of the public at large and to the
// detriment of our heirs and successors. We intend this dedication to be an
// overt act of relinquishment in perpetuity of all present and future rights to
// this software under copyright law.
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN
// ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
// WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE. For more
// information, please refer to http://unlicense.org/

class base58 {
    static function encode($data, $littleEndian = true) {
        $res = '';
        $dataIntVal = gmp_init($data, 16);
        while (gmp_cmp($dataIntVal, gmp_init(0, 10)) > 0) {
            $qr = gmp_div_qr($dataIntVal, gmp_init(58, 10));
            $dataIntVal = $qr[0];
            $reminder = gmp_strval($qr[1]);
            $p = self::permutation($reminder);
            if ($p === false) throw new \Exception('Something went wrong during base58 encoding');
            $res .= $p;
        }

        //get number of leading zeros
        $leading = '';
        $i = 0;
        while (substr($data, $i, 1) === '0') {
            if ($i!== 0 && $i%2) $leading .= '1';
            $i++;
        }

        if ($littleEndian) return strrev($res . $leading);
        return $res.$leading;
    }

    static function decode($encodedData, $littleEndian = true) {
        $res = gmp_init(0, 10);
        $length = strlen($encodedData);
        if ($littleEndian) $encodedData = strrev($encodedData);

        for ($i = $length - 1; $i >= 0; $i--) {
            $p = self::permutation(substr($encodedData, $i, 1), true);
            if ($p === false) throw new \Exception('Something went wrong during base58 decoding');
            $res = gmp_add(gmp_mul($res, gmp_init(58, 10)), $p);
        }

        $res = gmp_strval($res, 16);
        $i = $length - 1;
        while(substr($encodedData, $i, 1) === '1') {
            $res = '00' . $res;
            $i--;
        }

        if(strlen($res)%2 !== 0) {
            $res = '0' . $res;
        }

        return $res;
    }

    static function permutation($char, $reverse = false) {
        $table = [
                  '1','2','3','4','5','6','7','8','9','A','B','C','D',
                  'E','F','G','H','J','K','L','M','N','P','Q','R','S','T','U','V','W',
                  'X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','m','n','o',
                  'p','q','r','s','t','u','v','w','x','y','z'
                 ];

        if ($reverse) {
            $reversedTable = [];
            foreach($table as $key => $element) $reversedTable[$element] = $key;

            if (isset($reversedTable[$char])) return $reversedTable[$char];
            return false;
        }

        if (isset($table[$char])) return $table[$char];
        return false;
    }
}