<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use App\Helpers\PriceHelper;

class Product extends Model
{
    protected $fillable = ['user_id', 'category_id', 'product_type',
        'product_location', 'product_city', 'affiliate_link', 'sku', 'subcategory_id', 'country_id',
        'childcategory_id', 'attributes', 'name', 'photo', 'size', 'size_qty', 'size_price', 'color', 'details',
        'price', 'previous_price', 'stock', 'policy', 'status', 'views', 'tags', 'featured', 'best', 'top', 'hot',
        'latest', 'big', 'trending', 'sale', 'features', 'colors', 'product_condition', 'ship', 'meta_tag',
        'meta_description', 'youtube', 'type', 'file', 'license', 'license_qty', 'link', 'platform', 'region',
        'licence_type', 'measure', 'discount_date', 'is_discount', 'whole_sell_qty', 'whole_sell_discount',
        'catalog_id', 'slug', 'flash_count', 'hot_count', 'new_count', 'sale_count', 'best_seller_count',
        'popular_count', 'top_rated_count', 'big_save_count', 'trending_count', 'page_count',
        'seller_product_count', 'wishlist_count', 'vendor_page_count', 'min_price', 'max_price',
        'product_page', 'post_count', 'minimum_qty', 'preordered', 'color_all', 'size_all', 'stock_check', 'delivery_fee', 'delivery_unit', 'product_servicearea',
        'cross_products', '3d_model', 'discount_date_start', 'discount_date_end', 'state_id'];

    public $selectable = ['id', 'user_id', 'name', 'slug', 'features', 'colors', 'thumbnail', 'price', 'previous_price', 'attributes', 'size', 'size_price', 'discount_date', 'color_all', 'size_all', 'stock_check', 'category_id', 'details', 'type', '3d_model', 'discount_date_start', 'discount_date_end'];

    public static function boot()
    {
        parent::boot();
        
        static::saved(function() {
            cache()->forget('homepage_latest_products');
            cache()->forget('homepage_hot_products');
            cache()->forget('homepage_sale_products');
            cache()->forget('homepage_best_products');
            cache()->forget('homepage_featured_categories');
            cache()->forget('homepage_arrivals');
        });

        static::deleted(function() {
            cache()->forget('homepage_latest_products');
            cache()->forget('homepage_hot_products');
            cache()->forget('homepage_sale_products');
            cache()->forget('homepage_best_products');
        });
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault();
    }

    public function serviceArea()
    {
        return $this->belongsTo(\App\Models\ServiceArea::class, 'product_location');
    }

    public function cities()
    {
        return $this->belongsTo(\App\Models\City::class, 'product_city');
    }

    public function state()
    {
        return $this->belongsTo(\App\Models\State::class, 'state_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function scopeHome($query)
    {
        return $query->where('status', '=', 1)->select($this->selectable)->latest('id');
    }

    public function isDiscountActive()
    {
        if (empty($this->discount_date_start)) {
            return false;
        }

        try {
            $now = \Carbon\Carbon::now();

            // Helper function to handle dd/mm/yyyy or other formats
            $parseDate = function ($dateStr) {
                if (empty($dateStr)) {
                    return null;
                }
                if (str_contains($dateStr, '/')) {
                    try {
                        return \Carbon\Carbon::createFromFormat('d/m/Y', $dateStr);
                    } catch (\Exception $e) {
                        return \Carbon\Carbon::parse($dateStr);
                    }
                }

                return \Carbon\Carbon::parse($dateStr);
            };

            $start = $parseDate($this->discount_date_start);
            $end = $parseDate($this->discount_date_end);

            if ($start && $now->lt($start)) {
                return false;
            }

            if ($end && $now->gt($end)) {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function getPriceAttribute($value)
    {
        // If discount is not active and we have a previous price (Regular Price),
        // use it as the main price.
        if (! $this->isDiscountActive() && ! empty($this->previous_price) && $this->previous_price > 0) {
            return $this->previous_price;
        }

        return $value;
    }

    /**
     * Handle Cloudinary or full URLs correctly in the photo attribute.
     */
    protected static $pathCache = [];
    protected static $pubPath = null;

    private function resolveImagePath($filename, $preferThumb = false)
    {
        if (empty($filename)) return asset('assets/images/noimage.png');
        if (str_starts_with($filename, 'http')) return $filename;
        $filename = ltrim($filename, '/');
        
        // Request-level cache
        if (isset(self::$pathCache[$filename])) return self::$pathCache[$filename];

        if (!self::$pubPath) self::$pubPath = public_path();

        $dirs = ['thumbnails/', 'products/', 'product/', 'galleries/'];
        $trials = $preferThumb ? $dirs : array_reverse($dirs);
        $trials[] = ''; // Root search

        foreach ($trials as $dir) {
            $relPath = 'assets/images/' . $dir . $filename;
            // Single, prioritized check on the most likely public path
            if (file_exists(self::$pubPath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relPath))) {
                self::$pathCache[$filename] = asset($relPath);
                return self::$pathCache[$filename];
            }
        }

        self::$pathCache[$filename] = asset('assets/images/noimage.png');
        return self::$pathCache[$filename];
    }

    public function getPhotoAttribute($value)
    {
        return $this->resolveImagePath($value, false);
    }

    /**
     * Handle Cloudinary or full URLs correctly in the thumbnail attribute.
     */
    public function getThumbnailAttribute($value)
    {
        if (empty($value)) {
            $value = $this->attributes['photo'] ?? null;
        }
        return $this->resolveImagePath($value, true);
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category')->withDefault();
    }

    public function subcategory()
    {
        return $this->belongsTo('App\Models\Subcategory')->withDefault();
    }

    public function childcategory()
    {
        return $this->belongsTo('App\Models\Childcategory')->withDefault();
    }

    public function wishlist()
    {
        return $this->belongsTo('App\Models\Wishlist')->withDefault();
    }

    public function galleries()
    {
        return $this->hasMany('App\Models\Gallery');
    }

    public function ratings()
    {
        return $this->hasMany('App\Models\Rating');
    }

    public function wishlists()
    {
        return $this->hasMany('App\Models\Wishlist');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function clicks()
    {
        return $this->hasMany('App\Models\ProductClick');
    }

    public function reports()
    {
        return $this->hasMany('App\Models\Report', 'product_id');
    }

    public function IsSizeColor($value)
    {
        $sizes = array_unique($this->size);

        return in_array($value, $sizes);
    }

    public function checkVendor()
    {
        return $this->user_id != 0 ? '<small class="ml-2"> '.__('VENDOR').': <a href="'.route('admin-vendor-show', $this->user_id).'" target="_blank">'.$this->user->shop_name.'</a></small>' : '';
    }

    public function vendorPrice()
    {
        $gs = \App\Models\Generalsetting::safeFirst();
        $price = $this->price;

        return $price;
    }

    public function vendorSizePrice()
    {
        $gs = \App\Models\Generalsetting::safeFirst();
        $price = $this->price;
        if ($this->user_id != 0) {
            $price = $this->price + $gs->fixed_commission + ($this->price / 100) * $gs->percentage_commission;
        }
        if (! empty($this->size)) {
            $size_prices = $this->size_price;
            if (is_array($size_prices) && isset($size_prices[0])) {
                $price += $size_prices[0];
            }
        }

        // Attribute Section

        $attributes = $this->attributes['attributes'];
        if (! empty($attributes)) {
            $attrArr = json_decode($attributes, true);
        }

        if (! empty($attrArr)) {
            foreach ($attrArr as $attrKey => $attrVal) {
                if (is_array($attrVal) && array_key_exists('details_status', $attrVal) && $attrVal['details_status'] == 1) {

                    foreach ($attrVal['values'] as $optionKey => $optionVal) {
                        $price += $attrVal['prices'][$optionKey];
                        // only the first price counts
                        break;
                    }
                }
            }
        }

        // Attribute Section Ends
        return $price;
    }

    public function setCurrency()
    {
        $gs = \App\Models\Generalsetting::safeFirst();
        $price = $this->price;
        $curr = null;
        try {
            if (Session::has('currency')) {
                $curr = cache()->remember('session_currency', now()->addDay(), function () {
                    return Currency::find(Session::get('currency'));
                });
            } else {
                $curr = cache()->remember('default_currency', now()->addDay(), function () {
                    return Currency::where('is_default', '=', 1)->first();
                });
            }
        } catch (\Exception $e) {}

        if (!$curr) {
            $curr = new \stdClass();
            $curr->sign = "CFA";
            $curr->value = 1;
        }

        // Force value to 1 for CFA/XFA to avoid unintended conversions
        if (in_array($curr->sign, ['CFA', 'XFA'])) {
            $curr->value = 1;
        }

        $price = $price * $curr->value;
        $price = PriceHelper::showPrice($price);
        if ($gs->currency_format == 0) {
            return $curr->sign.$price;
        } else {
            return $price.$curr->sign;
        }
    }

    public function showPrice()
    {
        $gs = \App\Models\Generalsetting::safeFirst();

        // getPriceAttribute already handles the Regular vs Sale logic
        $price = $this->price;


        if (! empty($this->size)) {
            $size_prices = $this->size_price;
            if (is_array($size_prices) && isset($size_prices[0])) {
                $price += $size_prices[0];
            }
        }

        // Attribute Section
        $attributes = $this->attributes;
        $attrArr = is_array($attributes) ? $attributes : json_decode($attributes, true);

        if (! empty($attrArr)) {
            foreach ($attrArr as $attrKey => $attrVal) {
                if (is_array($attrVal) && array_key_exists('details_status', $attrVal) && $attrVal['details_status'] == 1) {
                    foreach ($attrVal['values'] as $optionKey => $optionVal) {
                        $price += $attrVal['prices'][$optionKey];
                        break;
                    }
                }
            }
        }

        if (Session::has('currency')) {
            $curr = null;
            try {
                $curr = cache()->remember('session_currency', now()->addDay(), function () {
                    return Currency::find(Session::get('currency'));
                });
            } catch (\Exception $e) {}
        } else {
            $curr = null;
            try {
                $curr = cache()->remember('default_currency', now()->addDay(), function () {
                    return Currency::where('is_default', '=', 1)->first();
                });
            } catch (\Exception $e) {}
        }

        if (!$curr) {
            $curr = new \stdClass();
            $curr->sign = "CFA";
            $curr->value = 1;
        }

        // Force value to 1 for CFA/XFA to avoid unintended conversions
        if (in_array($curr->sign, ['CFA', 'XFA'])) {
            $curr->value = 1;
        }

        $price = $price * $curr->value;
        $price = PriceHelper::showPrice($price);

        if ($gs->currency_format == 0) {
            return $curr->sign.$price;
        } else {
            return $price.$curr->sign;
        }
    }

    public function adminShowPrice()
    {
        $gs = \App\Models\Generalsetting::safeFirst();
        $price = $this->price;


        if (! empty($this->size)) {
            $size_prices = $this->size_price;
            if (is_array($size_prices) && isset($size_prices[0])) {
                $price += $size_prices[0];
            }
        }

        // Attribute Section

        $attributes = $this->attributes['attributes'];
        if (! empty($attributes)) {
            $attrArr = json_decode($attributes, true);
        }

        if (! empty($attrArr)) {
            foreach ($attrArr as $attrKey => $attrVal) {
                if (is_array($attrVal) && array_key_exists('details_status', $attrVal) && $attrVal['details_status'] == 1) {

                    foreach ($attrVal['values'] as $optionKey => $optionVal) {
                        $price += $attrVal['prices'][$optionKey];
                        // only the first price counts
                        break;
                    }
                }
            }
        }

        // Attribute Section Ends

        $curr = null;
        try {
            $curr = Currency::where('is_default', '=', 1)->first();
        } catch (\Exception $e) {}

        if (!$curr) {
            $curr = new \stdClass();
            $curr->sign = "CFA";
            $curr->value = 1;
        }

        // Force value to 1 for CFA/XFA to avoid unintended conversions
        if (in_array($curr->sign, ['CFA', 'XFA'])) {
            $curr->value = 1;
        }

        $price = $price * $curr->value;
        $price = PriceHelper::showPrice($price);

        if ($gs->currency_format == 0) {
            return $curr->sign.$price;
        } else {
            return $price.$curr->sign;
        }
    }

    public function showPreviousPrice()
    {
        // Only show previous price if a discount is active AND previous_price exists
        if (! $this->isDiscountActive() || empty($this->previous_price) || $this->previous_price <= $this->price) {
            return '';
        }

        $gs = \App\Models\Generalsetting::safeFirst();

        $price = $this->previous_price;


        if (! empty($this->size)) {
            $size_prices = $this->size_price;
            if (is_array($size_prices) && isset($size_prices[0])) {
                $price += $size_prices[0];
            }
        }

        // Attribute Section
        $attributes = $this->attributes;
        $attrArr = is_array($attributes) ? $attributes : json_decode($attributes, true);

        if (! empty($attrArr)) {
            foreach ($attrArr as $attrKey => $attrVal) {
                if (is_array($attrVal) && array_key_exists('details_status', $attrVal) && $attrVal['details_status'] == 1) {
                    foreach ($attrVal['values'] as $optionKey => $optionVal) {
                        $price += $attrVal['prices'][$optionKey];
                        break;
                    }
                }
            }
        }

        if (Session::has('currency')) {
            $curr = null;
            try {
                $curr = cache()->remember('session_currency', now()->addDay(), function () {
                    return Currency::find(Session::get('currency'));
                });
            } catch (\Exception $e) {}
        } else {
            $curr = null;
            try {
                $curr = cache()->remember('default_currency', now()->addDay(), function () {
                    return Currency::where('is_default', '=', 1)->first();
                });
            } catch (\Exception $e) {}
        }

        if (!$curr) {
            $curr = new \stdClass();
            $curr->sign = "CFA";
            $curr->value = 1;
        }

        // Force value to 1 for CFA/XFA to avoid unintended conversions
        if (in_array($curr->sign, ['CFA', 'XFA'])) {
            $curr->value = 1;
        }

        $price = $price * $curr->value;
        $price = PriceHelper::showPrice($price);

        if ($gs->currency_format == 0) {
            return $curr->sign.$price;
        } else {
            return $price.$curr->sign;
        }
    }

    public static function convertPrice($price)
    {
        $gs = \App\Models\Generalsetting::safeFirst();
        if (Session::has('currency')) {
            $curr = null;
            try {
                $curr = cache()->remember('session_currency', now()->addDay(), function () {
                    return Currency::find(Session::get('currency'));
                });
            } catch (\Exception $e) {}
        } else {
            $curr = null;
            try {
                $curr = cache()->remember('default_currency', now()->addDay(), function () {
                    return Currency::where('is_default', '=', 1)->first();
                });
            } catch (\Exception $e) {}
        }

        if (!$curr) {
            $curr = new \stdClass();
            $curr->sign = "CFA";
            $curr->value = 1;
        }
        $price = $price * $curr->value;
        $price = PriceHelper::showPrice($price);
        if ($gs->currency_format == 0) {
            return $curr->sign.$price;
        } else {
            return $price.$curr->sign;
        }
    }

    public static function vendorConvertPrice($price)
    {
        $gs = \App\Models\Generalsetting::safeFirst();
        $curr = null;
        try {
            $curr = Currency::where('is_default', '=', 1)->first();
        } catch (\Exception $e) {}

        if (!$curr) {
            $curr = new \stdClass();
            $curr->sign = "CFA";
            $curr->value = 1;
        }
        $price = $price * $curr->value;
        $price = PriceHelper::showPrice($price);
        if ($gs->currency_format == 0) {
            return $curr->sign.$price;
        } else {
            return $price.$curr->sign;
        }
    }

    public function showName()
    {
        $name = mb_strlen($this->name, 'UTF-8') > 50 ? mb_substr($this->name, 0, 50, 'UTF-8').'...' : $this->name;

        return $name;
    }

    public function emptyStock()
    {
        $stck = (string) $this->stock;
        if ($stck == '0') {
            return true;
        }

        return false;
    }

    public static function showTags()
    {
        $tags = null;
        $tagz = '';
        $name = Product::where('status', '=', 1)->pluck('tags')->toArray();
        foreach ($name as $nm) {
            if (! empty($nm)) {
                foreach ($nm as $n) {
                    $tagz .= $n.',';
                }
            }
        }
        $tags = array_unique(explode(',', $tagz));

        return $tags;
    }

    public function is_decimal($val)
    {
        return is_numeric($val) && floor($val) != $val;
    }

    public function getSizeAttribute($value)
    {
        if ($value == null) {
            return '';
        }

        return explode(',', $value);
    }

    public function getSizeQtyAttribute($value)
    {
        if ($value == null) {
            return '';
        }

        return explode(',', $value);
    }

    public function getSizePriceAttribute($value)
    {
        if ($value == null) {
            return '';
        }

        return explode(',', $value);
    }

    public function getColorAttribute($value)
    {
        if ($value == null) {
            return '';
        }

        return explode(',', $value);
    }

    public function getTagsAttribute($value)
    {
        if ($value == null) {
            return '';
        }

        return explode(',', $value);
    }

    public function getMetaTagAttribute($value)
    {
        if ($value == null) {
            return '';
        }

        return explode(',', $value);
    }

    public function getFeaturesAttribute($value)
    {
        if ($value == null) {
            return '';
        }

        return explode(',', $value);
    }

    public function getColorsAttribute($value)
    {
        if ($value == null) {
            return '';
        }

        return explode(',', $value);
    }

    public function getLicenseAttribute($value)
    {
        if ($value == null) {
            return '';
        }

        return explode(',,', $value);
    }

    public function getLicenseQtyAttribute($value)
    {
        if ($value == null) {
            return '';
        }

        return explode(',', $value);
    }

    public function getWholeSellQtyAttribute($value)
    {
        if ($value == null) {
            return '';
        }

        return explode(',', $value);
    }

    public function getWholeSellDiscountAttribute($value)
    {
        if ($value == null) {
            return '';
        }

        return explode(',', $value);
    }

    public function offPercentage()
    {
        if (! $this->isDiscountActive() || empty($this->previous_price) || $this->previous_price <= $this->price) {
            return 0;
        }

        $gs = \App\Models\Generalsetting::safeFirst();
        $price = $this->price;
        $preprice = $this->previous_price;

        if ($this->user_id != 0) {
            $price = $this->price;
            $preprice = $this->previous_price;
        }

        if (! empty($this->size)) {
            $size_prices = $this->size_price;
            if (is_array($size_prices) && isset($size_prices[0])) {
                $price += $size_prices[0];
                $preprice += $size_prices[0];
            }
        }

        // Attribute Section
        $attributes = $this->attributes;
        $attrArr = is_array($attributes) ? $attributes : json_decode($attributes, true);

        if (! empty($attrArr)) {
            foreach ($attrArr as $attrKey => $attrVal) {
                if (is_array($attrVal) && array_key_exists('details_status', $attrVal) && $attrVal['details_status'] == 1) {
                    foreach ($attrVal['values'] as $optionKey => $optionVal) {
                        $price += $attrVal['prices'][$optionKey];
                        $preprice += $attrVal['prices'][$optionKey];
                        break;
                    }
                }
            }
        }

        if (Session::has('currency')) {
            $curr = cache()->remember('session_currency', now()->addDay(), function () {
                return Currency::find(Session::get('currency'));
            });
        } else {
            $curr = cache()->remember('default_currency', now()->addDay(), function () {
                return Currency::where('is_default', '=', 1)->first();
            });
        }

        $price = $price * $curr->value;
        $preprice = $preprice * $curr->value;

        if ($preprice > 0 && $preprice > $price) {
            $Percentage = (($preprice - $price) * 100) / $preprice;

            return round($Percentage);
        }

        return 0;
    }

    public static function filterProducts($collection)
    {
        foreach ($collection as $key => $data) {
            if ($data->user_id != 0) {
                if ($data->user->is_vendor != 2) {
                    unset($collection[$key]);
                }
            }
            if (isset($_GET['max'])) {
                if ($data->vendorSizePrice() >= $_GET['max']) {
                    unset($collection[$key]);
                }
            }
            $data->price = $data->vendorSizePrice();
        }

        return $collection;
    }

    // MOBILE API SECTION

    public function ApishowPrice()
    {
        $gs = \App\Models\Generalsetting::safeFirst();
        $price = $this->price;

        if ($this->user_id != 0) {
            $price = $this->price + $gs->fixed_commission + ($this->price / 100) * $gs->percentage_commission;
        }

        if (! empty($this->size)) {
            $size_prices = $this->size_price;
            if (is_array($size_prices) && isset($size_prices[0])) {
                $price += $size_prices[0];
            }
        }

        // Attribute Section

        $attributes = $this->attributes['attributes'];
        if (! empty($attributes)) {
            $attrArr = json_decode($attributes, true);
        }

        if (! empty($attrArr)) {
            foreach ($attrArr as $attrKey => $attrVal) {
                if (is_array($attrVal) && array_key_exists('details_status', $attrVal) && $attrVal['details_status'] == 1) {

                    foreach ($attrVal['values'] as $optionKey => $optionVal) {
                        $price += $attrVal['prices'][$optionKey];
                        // only the first price counts
                        break;
                    }
                }
            }
        }

        // Attribute Section Ends

        if (Session::has('currency')) {
            $curr = cache()->remember('session_currency', now()->addDay(), function () {
                return Currency::find(Session::get('currency'));
            });
        } else {
            $curr = cache()->remember('default_currency', now()->addDay(), function () {
                return Currency::where('is_default', '=', 1)->first();
            });
        }

        $price = $price * $curr->value;
        $price = PriceHelper::apishowPrice($price);

        return $price;
    }

    public function ApishowDetailsPrice()
    {
        $gs = \App\Models\Generalsetting::safeFirst();
        $price = $this->price;

        if ($this->user_id != 0) {
            $price = $this->price + $gs->fixed_commission + ($this->price / 100) * $gs->percentage_commission;
        }

        // Attribute Section

        $attributes = $this->attributes['attributes'];
        if (! empty($attributes)) {
            $attrArr = json_decode($attributes, true);
        }

        if (! empty($attrArr)) {
            foreach ($attrArr as $attrKey => $attrVal) {
                if (is_array($attrVal) && array_key_exists('details_status', $attrVal) && $attrVal['details_status'] == 1) {

                    foreach ($attrVal['values'] as $optionKey => $optionVal) {
                        $price += $attrVal['prices'][$optionKey];
                        // only the first price counts
                        break;
                    }
                }
            }
        }

        // Attribute Section Ends

        if (Session::has('currency')) {
            $curr = null;
            try {
                $curr = cache()->remember('session_currency', now()->addDay(), function () {
                    return Currency::find(Session::get('currency'));
                });
            } catch (\Exception $e) {}
        } else {
            $curr = null;
            try {
                $curr = cache()->remember('default_currency', now()->addDay(), function () {
                    return Currency::where('is_default', '=', 1)->first();
                });
            } catch (\Exception $e) {}
        }

        if (!$curr) {
            $curr = new \stdClass();
            $curr->sign = "CFA";
            $curr->value = 1;
        }

        $price = $price * $curr->value;
        $price = PriceHelper::apishowPrice($price);

        return $price;
    }

    public function ApishowPreviousPrice()
    {
        $gs = \App\Models\Generalsetting::safeFirst();
        $price = $this->previous_price;
        if (! $price) {
            return '';
        }
        if ($this->user_id != 0) {
            $price = $this->previous_price + $gs->fixed_commission + ($this->previous_price / 100) * $gs->percentage_commission;
        }

        if (! empty($this->size)) {
            $size_prices = $this->size_price;
            if (is_array($size_prices) && isset($size_prices[0])) {
                $price += $size_prices[0];
            }
        }

        // Attribute Section

        $attributes = $this->attributes['attributes'];
        if (! empty($attributes)) {
            $attrArr = json_decode($attributes, true);
        }
        // dd($attrArr);
        if (! empty($attrArr)) {
            foreach ($attrArr as $attrKey => $attrVal) {
                if (is_array($attrVal) && array_key_exists('details_status', $attrVal) && $attrVal['details_status'] == 1) {

                    foreach ($attrVal['values'] as $optionKey => $optionVal) {
                        $price += $attrVal['prices'][$optionKey];
                        // only the first price counts
                        break;
                    }
                }
            }
        }

        // Attribute Section Ends

        if (Session::has('currency')) {
            $curr = cache()->remember('session_currency', now()->addDay(), function () {
                return Currency::find(Session::get('currency'));
            });
        } else {
            $curr = cache()->remember('default_currency', now()->addDay(), function () {
                return Currency::where('is_default', '=', 1)->first();
            });
        }

        $price = $price * $curr->value;
        $price = PriceHelper::apishowPrice($price);

        return $price;
    }
}
