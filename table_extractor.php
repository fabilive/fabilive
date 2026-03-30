<?php
$content = file_get_contents('seed.sql');
if (preg_match('/CREATE TABLE[ \n\r]+`?manage_agreements`?[ \n\r]+\((.*?)\)[ \n\r]*;/is', $content, $matches)) {
    echo $matches[0];
} else {
    echo "Table manage_agreements not found in seed.sql\n";
}

if (preg_match('/CREATE TABLE[ \n\r]+`?user_subscriptions`?[ \n\r]+\((.*?)\)[ \n\r]*;/is', $content, $matches)) {
    echo $matches[0];
} else {
    echo "Table user_subscriptions not found in seed.sql\n";
}
