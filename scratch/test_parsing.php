<?php
$total_from_frontend = "10.000"; // Suppose thousand separator is dot
$parsed = (float) preg_replace('/[^0-9\.]/ui', '', $total_from_frontend);
echo "Input: $total_from_frontend, Parsed: $parsed\n";

$total_from_frontend = "500,00"; // Suppose decimal separator is comma
$parsed = (float) preg_replace('/[^0-9\.]/ui', '', $total_from_frontend);
echo "Input: $total_from_frontend, Parsed: $parsed\n";
