<?php

$ch = curl_init('http://127.0.0.1:8000/skms/faq/1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Port 8000 /skms/faq/1 (published) HTTP Code: " . $httpCode . "\n";
