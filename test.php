<?php

header('Content-Type: text/plain; charset=utf-8');

require_once('BitcoinKeys.php');

echo "Create Mainnet\n";
$bk = new BitcoinKeys();
echo "Network: ".$bk->getNetwork()."\n";
echo "Secret: ".$bk->getSecret()."\n";
echo "WIF: ".$bk->getWif()."\n";
echo "Public: ".$bk->getPublic()."\n";
echo "Addr: ".$bk->getAddress()."\n";
echo "\n";

echo "Create Testnet\n";
$bk = new BitcoinKeys();
$bk->setNetwork(BitcoinNetworks::Test);
echo "Network: ".$bk->getNetwork()."\n";
echo "Secret: ".$bk->getSecret()."\n";
echo "WIF: ".$bk->getWif()."\n";
echo "Public: ".$bk->getPublic()."\n";
echo "Addr: ".$bk->getAddress()."\n";
echo "\n";

echo "FromWif Mainnet (5Kb8kLf9zgWQnogidDA76MzPL6TsZZY36hWXMssSzNydYXYB9KF)\n";
$bk = new BitcoinKeys('5Kb8kLf9zgWQnogidDA76MzPL6TsZZY36hWXMssSzNydYXYB9KF');
echo "Network: ".$bk->getNetwork()."\n";
echo "Secret: ".$bk->getSecret()."\n";
echo "WIF: ".$bk->getWif()."\n";
echo "Public: ".$bk->getPublic()."\n";
echo "Addr (1CC3X2gu58d6wXUWMffpuzN9JAfTUWu4Kj): ".$bk->getAddress()."\n";
echo "\n";
