<?php

$ch = curl_init('http://localhost/skms/faq/1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Port 80 (Apache) /skms/faq/1 HTTP Code: " . $httpCode . "\n";
