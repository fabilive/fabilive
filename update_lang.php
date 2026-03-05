<?php

$files = glob(__DIR__ . '/resources/lang/*.json');
foreach($files as $file) {
    if(basename($file) != 'default.json') {
        $json = json_decode(file_get_contents($file), true);
        if(is_array($json)) {
            $json['Shipping Address'] = 'Delivery Location';
            $json['Shipping Cost'] = 'Delivery & Escrow Fee';
            $json['PickUp Location'] = 'Local Pickup Location';
            $json['Pick Up'] = 'Local Pickup';
            $json['Ship To Address'] = 'Rider Delivery';
            $json['Dispute'] = 'Dispute';
            $json['Disputes'] = 'Disputes';
            
            file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
            echo 'Updated ' . basename($file) . PHP_EOL;
        }
    }
}
echo "Translations updated globally.\n";
