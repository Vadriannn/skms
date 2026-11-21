<?php

$ch = curl_init('http://127.0.0.1:8000/skms/faq/6');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Port 8000 /skms/faq/6 HTTP Code: " . $httpCode . "\n";

$ch2 = curl_init('http://localhost/skms/faq/6');
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
$res2 = curl_exec($ch2);
$httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
curl_close($ch2);
echo "Port 80 (Apache) /skms/faq/6 HTTP Code: " . $httpCode2 . "\n";
