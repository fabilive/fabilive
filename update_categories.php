<?php
use App\Models\Category;

$categories = Category::all();
$updated = 0;
foreach($categories as $category) {
    $name = strtolower($category->name);
    if(strpos($name, 'electronic') !== false) {
        $category->image = 'category_electronic.png';
    } elseif(strpos($name, 'fashion') !== false || strpos($name, 'beauty') !== false) {
        $category->image = 'category_fashion.png';
    } elseif(strpos($name, 'camera') !== false || strpos($name, 'photo') !== false) {
        $category->image = 'category_camera.png';
    } elseif(strpos($name, 'phone') !== false || strpos($name, 'table') !== false) {
        $category->image = 'category_smartphone.png';
    } elseif(strpos($name, 'sport') !== false || strpos($name, 'outdoor') !== false) {
        $category->image = 'category_sport.png';
    } elseif(strpos($name, 'jewelry') !== false || strpos($name, 'watch') !== false) {
        $category->image = 'category_jewelry.png';
    } elseif(strpos($name, 'surveillance') !== false || strpos($name, 'safety') !== false) {
        $category->image = 'category_surveillance.png';
    }
    
    if($category->isDirty('image')) {
        $category->save();
        $updated++;
    }
}
echo "Updated $updated categories successfully.";
