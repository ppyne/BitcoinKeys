<?php

// Example of cute SVG identicon for bitcoin addresses

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

header('Content-Type: image/svg+xml');

require_once('BitcoinKeys.php');

$bk = new BitcoinKeys();

$checksum = crc32($bk->getAddress());
$arr = str_split(sprintf("%032b\n", $checksum));

// Color hue
$h = $checksum % 360;

?><?xml version="1.0" encoding="utf-8"?>
<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 32" enable-background="new 0 0 32 32" xml:space="preserve" width="32" height="32">
<?php 
for ($i = 0; $i < 4; $i++) {
    for ($j = 0; $j < 4; $j++) {
        if ($arr[$i*4 + $j] === "1") {
?>
<rect x="<?php echo $j*4; ?>" y="<?php echo $i*4; ?>" fill="hsl(<?php echo $h; ?>,70%,50%)" width="4" height="4"/>
<rect x="<?php echo 32 - 4*($j+1); ?>" y="<?php echo $i*4; ?>" fill="hsl(<?php echo $h; ?>,70%,50%)" width="4" height="4"/>
<rect x="<?php echo $j*4; ?>" y="<?php echo 32 - 4*($i+1); ?>" fill="hsl(<?php echo $h; ?>,70%,50%)" width="4" height="4"/>
<rect x="<?php echo 32 - 4*($j+1); ?>" y="<?php echo 32 - 4*($i+1); ?>" fill="hsl(<?php echo $h; ?>,70%,50%)" width="4" height="4"/>
<?php
        }
    }
}
?>
</svg>