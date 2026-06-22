<?php
require __DIR__ . '/../vendor/autoload.php';
$c = 'App\\Models\\OtpCode';
echo class_exists($c) ? "OK\n" : "NO\n";
