<?php
    $categories = App\Models\Category::with('subs')->withCount('subs')->where('status',1)->get();
    
?>
<div class="col-xl-3">
    <div id="sidebar" class="widget-title-bordered-full">
        <div class="dashbaord-sidebar-close d-xl-none">
    <i class="fas fa-times"></i>
  </div>
        <form id="catalogForm" action="<?php echo e(route('front.category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')])); ?>" method="GET">

        <div id="woocommerce_product_categories-4" class="widget woocommerce widget_product_categories widget-toggle">
            <h2 class="widget-title"><?php echo e(__('Product categories')); ?></h2>
            <ul class="product-categories">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="cat-item cat-parent">
                    <a href="<?php echo e(route('front.category', $category->slug)); ?><?php echo e(!empty(request()->input('search')) ? '?search='.request()->input('search') : ''); ?>" class="category-link" id="cat"><?php echo e($category->name); ?> <span class="count"></span></a>

                    <?php if($category->subs_count > 0): ?>
                        <span class="has-child"></span>
                        <ul class="children">
                            <?php $__currentLoopData = $category->subs()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="cat-item cat-parent">
                                <a href="<?php echo e(route('front.category', [$category->slug, $subcategory->slug])); ?><?php echo e(!empty(request()->input('search')) ? '?search='.request()->input('search') : ''); ?>" class="category-link <?php echo e(isset($subcat) ? ($subcat->id == $subcategory->id ? 'active' : '') : ''); ?>"><?php echo e($subcategory->name); ?> <span class="count"></span></a>

                                <?php if($subcategory->childs->count()!=0): ?>
                                    <span class="has-child"></span>
                                    <ul class="children">
                                        <?php $__currentLoopData = $subcategory->childs()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $childelement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="cat-item ">
                                            <a href="<?php echo e(route('front.category', [$category->slug, $subcategory->slug, $childelement->slug])); ?><?php echo e(!empty(request()->input('search')) ? '?search='.request()->input('search') : ''); ?>" class="category-link <?php echo e(isset($childcat) ? ($childcat->id == $childelement->id ? 'active' : '') : ''); ?>"> <?php echo e($childelement->name); ?> <span class="count"></span></a>
                                        </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>

                                <?php endif; ?>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php endif; ?>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>

        <div id="bigbazar-price-filter-list-1" class="widget bigbazar_widget_price_filter_list widget_layered_nav widget-toggle mx-3">
            <h2 class="widget-title"><?php echo e(__('Filter by Price')); ?></h2>
            <ul class="price-filter-list">
                <div class="price-range-block">
                    <div id="slider-range" class="price-filter-range" name="rangeInput"></div>
                    <div class="livecount">
                        <input type="number" name="min"  oninput="" id="min_price" class="price-range-field" />
                        <span>
                            <?php echo e(__('To')); ?>

                        </span>
                        <input type="number" name="max"  oninput="" id="max_price" class="price-range-field" />
                    </div>
                </div>

                <button class="filter-btn btn btn-primary mt-3 mb-4" type="submit"><?php echo e(__('Search')); ?></button>
            </ul>
        </div>

    </form>


    <?php if((!empty($cat) && !empty(json_decode($cat->attributes, true))) || (!empty($subcat) && !empty(json_decode($subcat->attributes, true))) || (!empty($childcat) && !empty(json_decode($childcat->attributes, true)))): ?>

    <form id="attrForm" action="<?php echo e(route('front.category',[Request::route('category'),Request::route('subcategory'),Request::route('childcategory')])); ?>" method="post">

        <?php if(!empty($cat) && !empty(json_decode($cat->attributes, true))): ?>
        <?php $__currentLoopData = $cat->attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <div id="bigbazar-attributes-filter-<?php echo e($attr->name); ?>" class="widget woocommerce bigbazar-attributes-filter widget_layered_nav widget-toggle">
            <h2 class="widget-title"><?php echo e($attr->name); ?></h2>
            <ul class="swatch-filter-pa_color">
                <?php if(!empty($attr->attribute_options)): ?>
                      <?php $__currentLoopData = $attr->attribute_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <div class="form-check ml-0 pl-0">
                        <input name="<?php echo e($attr->input_name); ?>[]" class="form-check-input attribute-input" type="checkbox" id="<?php echo e($attr->input_name); ?><?php echo e($option->id); ?>" value="<?php echo e($option->name); ?>">
                        <label class="form-check-label" for="<?php echo e($attr->input_name); ?><?php echo e($option->id); ?>"><?php echo e($option->name); ?></label>
                      </div>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
            </ul>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

        <?php if(!empty($subcat) && !empty(json_decode($subcat->attributes, true))): ?>
            <?php $__currentLoopData = $subcat->attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div id="bigbazar-attributes-filter-<?php echo e($attr->name); ?>" class="widget woocommerce bigbazar-attributes-filter widget_layered_nav widget-toggle">
                    <h2 class="widget-title"><?php echo e($attr->name); ?></h2>
                    <ul class="swatch-filter-pa_color">
                        <?php if(!empty($attr->attribute_options)): ?>
                              <?php $__currentLoopData = $attr->attribute_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <div class="form-check ml-0 pl-0">
                                <input name="<?php echo e($attr->input_name); ?>[]" class="form-check-input attribute-input" type="checkbox" id="<?php echo e($attr->input_name); ?><?php echo e($option->id); ?>" value="<?php echo e($option->name); ?>">
                                <label class="form-check-label" for="<?php echo e($attr->input_name); ?><?php echo e($option->id); ?>"><?php echo e($option->name); ?></label>
                              </div>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                    </ul>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

    <?php if(!empty($childcat) && !empty(json_decode($childcat->attributes, true))): ?>
        <?php $__currentLoopData = $childcat->attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $attr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div id="bigbazar-attributes-filter-<?php echo e($attr->name); ?>" class="widget woocommerce bigbazar-attributes-filter widget_layered_nav widget-toggle px-3">
                <h2 class="widget-title"><?php echo e($attr->name); ?></h2>
                <ul class="swatch-filter-pa_color">
                    <?php if(!empty($attr->attribute_options)): ?>
                          <?php $__currentLoopData = $attr->attribute_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <div class="form-check ml-0 pl-0">
                            <input name="<?php echo e($attr->input_name); ?>[]" class="form-check-input attribute-input" type="checkbox" id="<?php echo e($attr->input_name); ?><?php echo e($option->id); ?>" value="<?php echo e($option->name); ?>">
                            <label class="form-check-label" for="<?php echo e($attr->input_name); ?><?php echo e($option->id); ?>"><?php echo e($option->name); ?></label>
                          </div>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                </ul>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
     <?php endif; ?>

    </form>
    <?php endif; ?>
        <div class="row mx-0">
            <div class="col-12">
                <div class="section-head border-bottom d-flex justify-content-between align-items-center">
                    <div class="d-flex section-head-side-title">
                        <h5 class="font-700 text-dark mb-0"><?php echo e(__('Recent Product')); ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="product-style-2 owl-carousel owl-nav-hover-primary nav-top-right single-carousel dot-disable product-list e-bg-white">
                
                    <?php $__currentLoopData = $latest_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <div class="item">
                        <div class="row row-cols-1">
                       
                            <?php $__currentLoopData = $item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      
                            <div class="col mb-1">
                                <div class="product type-product">
                                    <div class="product-wrapper">
                                        <div class="product-image">
                                            <a href="<?php echo e(route('front.product', $prod['slug'])); ?>" class="woocommerce-LoopProduct-link"><img src="<?php echo e($prod['thumbnail'] ? asset('assets/images/thumbnails/'.$prod['thumbnail'] ):asset('assets/images/noimage.png')); ?>" alt="Product Image"></a>
                                            <div class="wishlist-view">
                                                <div class="quickview-button">
                                                    <a class="quickview-btn" href="<?php echo e(route('front.product', $prod['slug'])); ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Quick View" aria-label="Quick View"><?php echo e(__('Quick View')); ?></a>
                                                </div>
                                                <div class="whishlist-button">
                                                    <a class="add_to_wishlist" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e(__('Wishlist')); ?>" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist"><?php echo e(__('Wishlist')); ?></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-info">
                                            <h3 class="product-title"><a href="<?php echo e(route('front.product', $prod['slug'])); ?>"><?php echo e($prod['name']); ?></a></h3>
                                            <div class="product-price">
                                                <div class="price">
                                                    <ins><?php echo e(PriceHelper::showPrice($prod['price'])); ?></ins>
                                                    <del><?php echo e(PriceHelper::showPrice($prod['previous_price'])); ?></del>
                                                </div>
                                                <div class="on-sale"><span><?php echo e(round($prod->offPercentage())); ?></span><span>% off</span></div>
                                            </div>
                                            <div class="shipping-feed-back">
                                                <div class="star-rating">
                                                    <div class="rating-wrap">
                                                        <p><i class="fas fa-star"></i><span>  <?php echo e(number_format($prod->ratings_avg_rating,1)); ?></span></p>
                                                    </div>
                                                    <div class="rating-counts-wrap">
                                                        <p>(<?php echo e($prod->ratings_count); ?>)</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/partials/catalog/catalog.blade.php ENDPATH**/ ?>