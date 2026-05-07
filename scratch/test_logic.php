<?php
$total = 9898.00;
$discount = 500;
$total_after = $total - $discount;
$data6 = round($total_after, 2);
$delivery = 999;
$ttotal = $data6 + $delivery;
echo "Original: $total\n";
echo "Discount: $discount\n";
echo "After Discount (data6): $data6\n";
echo "Delivery: $delivery\n";
echo "Final ttotal: $ttotal\n";
