# BitcoinKeys

<img src="bitcoin.png" alt="Bitcoin" width="100" height="100" />

BitcoinKeys is a PHP class which can create and handle WIF (Wallet Import Format) private keys and (re)build their related P2PKH (Pay to PubkeyHash) addresses. The class is fully based on the PHP's OpenSSL extension and the class Base58 (included).

P2PKH is the very first Bitcoin address format, starting with 1. The class will also handle private keys in WIF format, beginning with 5, based on the eliptic curve `secp256k1` (y² = x³ + 7). Private keys are 32 bytes long (256 bit), beginning from `0x01` to `0xFFFF FFFF FFFF FFFF FFFF FFFF FFFF FFFE BAAE DCE6 AF48 A03B BFD2 5E8C D036 4140`. Public keys are 65 bytes long (64 bytes, plus 1 byte prefix `04`).

The project also includes the class Base58, which encodes big integers (keys) with human friendly characters, the A to Z letters in both upper and lowercase, the numbers from 1 to 9, without the number "0" (zero), without the uppercase letter O (o), without the lowercase letter l (L) and without the uppercase I (i), since those 4 characters are known to bring confusions and errors in hominids sapiens sapiens.

## Security warnings

Private keys must be produced locally and kept in a safe place. Transfer over network may be done through a secure protocol using SSL for instance and private keys, or wallets, should be encrypted thanks to a method like [BIP38](https://github.com/bitcoin/bips/blob/master/bip-0038.mediawiki).

## Examples

```php
// Initialization
require_once('BitcoinKeys.php');

// Generate a new key
$bk = new BitcoinKeys();

// Print the private key in WIF format
echo "WIF: ".$bk->getWif()."\n";

// Print the address
echo "Addr: ".$bk->getAddress()."\n";

// Import private key in WIF format
$bk = new BitcoinKeys('5Kb8kLf9zgWQnogidDA76MzPL6TsZZY36hWXMssSzNydYXYB9KF');

// Print the private key in hex format
echo "Secret: ".$bk->getSecret()."\n";

// Print the public key in hex format
echo "Public: ".$bk->getPublic()."\n";

// Change network
$bk->setNetwork(BitcoinNetworks::Test);
```

## Static methods

You may also be interested by a bunch of static methods available:

`BitcoinKeys::newKeyPair()` will return a array with a new randomly generated key pair (private, public), both in hex format.

`BitcoinKeys::secretToWif($secret, $network)` will build a private key in WIF format from a private key in hex format. Network can be mainnet `80`, the default, or testnet `ef` (hex format).

`BitcoinKeys::publicToAddress($public, $network)` will build a P2PKH address from a public key in hex format. Network can be mainnet `00`, the default, or testnet `6f` (hex format).

`BitcoinKeys::secretToPem($secret)` will return a the private key in PEM format to satisfy OpenSSL, from a private key given in hex format. 

`BitcoinKeys::wifToKeyPair($wif)` will return a key pair (private, public), and the network, in an array, in hex format from a WIF formatted key. Network can be mainnet `00` or testnet `6f` (hex format), other values may raise exceptions.

## Readings

[Elliptic-curve keys in basic blockchain programming](https://davidederosa.com/basic-blockchain-programming/elliptic-curve-keys/)
[Private key](https://en.bitcoin.it/wiki/Private_key)
[Base58Check encoding](https://en.bitcoin.it/wiki/Base58Check_encoding)
[Wallet Import Format](https://en.bitcoin.it/wiki/Wallet_import_format)
[TP's Go Bitcoin Tests](https://gobittest.appspot.com/)

## License

Copyright (c) 2021 Alexandre Vialle

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
3. Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
