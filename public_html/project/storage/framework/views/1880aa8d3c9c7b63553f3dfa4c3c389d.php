<div class="full-row pb-0">
  <div class="container">
      <div class="row single-product-wrapper">
          <div class="col-12 col-lg-4 mb-4 mb-lg-0">
              <div class="product-images overflow-hidden">
                  <div class="images-inner">
                      <div class="woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images" data-columns="4" style="opacity: 1; transition: opacity 0.25s ease-in-out 0s;">
                          <figure class="woocommerce-product-gallery__wrapper">
                              <div class="bg-light">
                                  <img  id="single-image-zoom" src="<?php echo e(filter_var($productt->photo, FILTER_VALIDATE_URL) ?$productt->photo:asset('assets/images/products/'.$productt->photo)); ?>" alt="Thumb Image" data-zoom-image="<?php echo e(filter_var($productt->photo, FILTER_VALIDATE_URL) ?$productt->photo:asset('assets/images/products/'.$productt->photo)); ?>" />
                              </div>

                              <div id="gallery_09" class="product-slide-thumb">
                                  <div class="owl-carousel four-carousel dot-disable nav-arrow-middle owl-mx-5">
                                  <?php $__currentLoopData = $productt->galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                      <div class="item">
                                          <a class="active" href="<?php echo e(asset('assets/images/galleries/'.$gal->photo)); ?>" data-image="<?php echo e(asset('assets/images/galleries/'.$gal->photo)); ?>" data-zoom-image="<?php echo e(asset('assets/images/galleries/'.$gal->photo)); ?>">
                                              <img src="<?php echo e(asset('assets/images/galleries/'.$gal->photo)); ?>" alt="Thumb Image" />
                                          </a>
                                      </div>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  </div>
                              </div>
                          </figure>
                      </div>
                  </div>
              </div>
          </div>

          <div class="col-12 col-lg-5 col-md-8">
              <div class="summary entry-summary">
                  <div class="summary-inner">
                      <div class="entry-breadcrumbs w-100">
                          <nav class="breadcrumb-divider-slash" aria-label="breadcrumb">
                              <ol class="breadcrumb pro-bread">
                                  <li class="breadcrumb-item"><a href="<?php echo e(route('front.index')); ?>"><?php echo e(__('Home')); ?></a></li>
                                  <li class="breadcrumb-item"><a href="<?php echo e(route('front.category',$productt->category->slug)); ?>"><?php echo e($productt->category->name); ?></a></li>
                                  <?php if($productt->subcategory_id != null): ?>
                                  <li class="breadcrumb-item">
                                      <a href="<?php echo e(route('front.category',[$productt->category->slug, $productt->subcategory->slug])); ?>">
                                      <?php echo e($productt->subcategory->name); ?>

                                      </a>
                                  </li>
                                  <?php endif; ?>
                                  <?php if($productt->childcategory_id != null): ?>
                                  <li class="breadcrumb-item">
                                      <a href="<?php echo e(route('front.category',[ $productt->category->slug,$productt->subcategory->slug,$productt->childcategory->slug])); ?>">
                                      <?php echo e($productt->childcategory->name); ?>

                                      </a>
                                  </li>
                                  <?php endif; ?>

                              </ol>
                          </nav>
                      </div>
                      <h1 class="product_title entry-title"><?php echo e($productt->name); ?></h1>

                      <div class="pro-details">
                         <div class="pro-info">
                              <div class="woocommerce-product-rating">
                                  <div class="fancy-star-rating">
                                      <div class="rating-wrap"> <span class="fancy-rating good"><?php echo e(App\Models\Rating::ratings($productt->id)); ?> ★</span>
                                      </div>
                                      <div class="rating-counts-wrap">
                                          <a href="#reviews" class="bigbazar-rating-review-link" rel="nofollow"> <span class="rating-counts"> (<?php echo e(App\Models\Rating::ratingCount($productt->id)); ?>) </span> </a>
                                      </div>
                                  </div>
                              </div>

                              <p class="price">
                                  <span class="woocommerce-Price-amount amount mr-4">
                                      <bdi><span class="woocommerce-Price-currencySymbol" id="sizeprice"><?php echo e($productt->showPrice()); ?></bdi>
                                  </span>
                                  <del class="ml-3"><small><?php echo e($productt->showPreviousPrice()); ?></small></del>
                                 <span class="on-sale"><span><?php echo e(round($productt->offPercentage() )); ?></span>% Off</span>

                              </p>

                            <?php if($productt->type == 'Physical'): ?>
                               <?php if($productt->emptyStock()): ?>
                                     <div class="stock-availability out-stock"><?php echo e(('Out Of Stock')); ?></div>
                                     <?php else: ?>
                                     <div class="stock-availability in-stock text-bold"><?php echo e($gs->show_stock == 0 ? '' : $productt->stock); ?> <?php echo e(('In Stock')); ?></div>
                               <?php endif; ?>
                            <?php endif; ?>



                         
                         <div class="product-offers">
                            <ul class="product-offers-list">
                               <?php if($productt->ship != null): ?>
                               <li class="product-offer-item"><span class="h6"><?php echo e(__('Estimated Shipping Time:')); ?></span> <?php echo e($productt->ship); ?>

                               </li>
                               <?php endif; ?>
                               <?php if( $productt->sku != null ): ?>
                               <li class="product-offer-item product-id<?php echo e($productt->product_type == 'affiliate' ? 'mt-4' : ''); ?>"><span class="h6"><?php echo e(__('Product SKU:')); ?> </span> <?php echo e($productt->sku); ?>

                               </li>
                               <?php endif; ?>
                                  
                                  <?php if($productt->type == 'License'): ?>
                                  <?php if($productt->platform != null): ?>
                                  <li class="product-offer-item license-id"><span class="h6"><?php echo e(__('Platform:')); ?></span> <?php echo e($productt->platform); ?>

                                  </li>
                                  <?php endif; ?>
                                  <?php if($productt->region != null): ?>
                                  <li class="product-offer-item license-id"><span class="h6"><?php echo e(__('Region:')); ?></span> <?php echo e($productt->region); ?>

                                  </li>
                                  <?php endif; ?>
                                  <?php if($productt->licence_type != null): ?>
                                  <li class="product-offer-item license-id"><span class="h6"> <?php echo e(__('License Type:')); ?></span> <?php echo e($productt->licence_type); ?>

                                  </li>
                                  <?php endif; ?>
                               <?php endif; ?>
                               
                            </ul>
                         </div>
                         </div>
                         
                          </div>
                         <?php if($productt->stock_check == 1): ?>
                              <?php if(!empty($productt->size)): ?>
                              <div class="product-size">
                                  <p class="title"><?php echo e(__('Size :')); ?></p>
                                  <ul class="siz-list">
                                    <?php $__currentLoopData = array_unique($productt->size); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <li class="<?php echo e($loop->first ? 'active' : ''); ?>" data-key="<?php echo e(str_replace(' ','',$data1)); ?>">
                                        <span class="box">
                                          <?php echo e($data1); ?>

                                        </span>
                                      </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  </ul>
                                </div>
                         <?php endif; ?>
                         

    <?php if(!empty($productt->color)): ?>

      <div class="product-color">
          <div class="title"><?php echo e(__('Color :')); ?></div>
          <ul class="color-list">
            <?php $__currentLoopData = $productt->color; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li class="<?php echo e($loop->first ? 'active' : ''); ?> <?php echo e($productt->IsSizeColor($productt->size[$key]) ? str_replace(' ','',$productt->size[$key]) : ''); ?> <?php echo e($productt->size[$key] == $productt->size[0] ? 'show-colors' : ''); ?>">
                <span class="box" data-color="<?php echo e($productt->color[$key]); ?>" style="background-color: <?php echo e($productt->color[$key]); ?>">

                  <input type="hidden" class="size" value="<?php echo e($productt->size[$key]); ?>">
                  <input type="hidden" class="size_qty" value="<?php echo e($productt->size_qty[$key]); ?>">
                  <input type="hidden" class="size_key" value="<?php echo e($key); ?>">
                  <input type="hidden" class="size_price" value="<?php echo e(round($productt->size_price[$key] * $curr->value,2)); ?>">

                </span>
              </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </ul>
       </div>

    <?php endif; ?>

      
      <?php else: ?>
      <?php if(!empty($productt->size_all)): ?>
      <div class="product-size" data-key="false">
       <p class="title"><?php echo e(__('Size :')); ?></p>
       <ul class="siz-list">
             <?php $__currentLoopData = array_unique(explode(',',$productt->size_all)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
             <li class="<?php echo e($loop->first ? 'active' : ''); ?>" data-key="<?php echo e(str_replace(' ','',$data1)); ?>">
                <span class="box">
                <?php echo e($data1); ?>

                <input type="hidden" class="size" value="<?php echo e($data1); ?>">
                <input type="hidden" class="size_key" value="<?php echo e($key); ?>">
                </span>
             </li>
             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
       </ul>
      </div>
      <?php endif; ?>
      <?php if(!empty($productt->color_all)): ?>
       <div class="product-color" data-key="false">
       <div class="title"><?php echo e(__('Color :')); ?></div>
          <ul class="color-list">

                <?php $__currentLoopData = explode(',',$productt->color_all); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $color1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <li class="<?php echo e($loop->first ? 'active' : ''); ?> show-colors">
                   <span class="box" data-color="<?php echo e($color1); ?>" style="background-color: <?php echo e($color1); ?>">
                   <input type="hidden" class="size_price" value="0">
                   </span>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </ul>
       </div>
       <?php endif; ?>
  <?php endif; ?>
              <input type="hidden" id="product_price" value="<?php echo e(round($productt->vendorPrice() * $curr->value,2)); ?>">
              <input type="hidden" id="product_id" value="<?php echo e($productt->id); ?>">
              <input type="hidden" id="curr_pos" value="<?php echo e($gs->currency_format); ?>">
              <input type="hidden" id="curr_sign" value="<?php echo e($curr->sign); ?>">
                  

      <?php if(!empty($productt->size)): ?>
      <input type="hidden" id="stock" value="<?php echo e($productt->size_qty[0]); ?>">
      <?php else: ?>
      <?php if(!$productt->emptyStock()): ?>
        <input type="hidden" id="stock" value="<?php echo e($productt->stock); ?>">
      <?php elseif($productt->type != 'Physical'): ?>
        <input type="hidden" id="stock" value="0">
      <?php else: ?>
        <input type="hidden" id="stock" value="">
      <?php endif; ?>
    <?php endif; ?>
   
    <?php if($productt->is_discount==1 && $productt->discount_date >= date('Y-m-d') && $productt->user->is_vendor==2): ?>
    <div class="time-count time-box text-center my-30 flex-between w-75" data-countdown="<?php echo e($productt['discount_date']); ?>"></div>
  
    <?php endif; ?>
    
                         <div class="d-flex flex-wrap mt-3">
                            <?php if($productt->product_type != "affiliate" && $productt->type == 'Physical'): ?>
                               <div class="multiple-item-price m-1 me-3">
                                  <div class="qty">
                                     <ul class="qty-buttons">
                                     <li>
                                        <span class="qtminus">
                                           <i class="icofont-minus"></i>
                                        </span>
                                     </li>
                                     <li>
                                      <input class="qttotal" type="text" id="order-qty" value="<?php echo e($productt->minimum_qty == null ? '1' : (int)$productt->minimum_qty); ?>">
                                      <input type="hidden" id="affilate_user" value="<?php echo e($affilate_user); ?>">
                                      <input type="hidden" id="product_minimum_qty" value="<?php echo e($productt->minimum_qty == null ? '0' : $productt->minimum_qty); ?>">
                                    </li>
                                     <li>
                                        <span class="qtplus">
                                           <i class="icofont-plus"></i>
                                        </span>
                                     </li>
                                     </ul>
                                  </div>
                               </div>
                          <?php endif; ?>


                          
                          <ul>
                          <?php if($productt->product_type == "affiliate"): ?>

                              <li class="addtocart m-1">
                                <a  href="javascript:;" class="affilate-btn"  data-href="<?php echo e($productt->affiliate_link); ?>" target="_blank"> <?php echo e(__('Buy Now')); ?></a>
                              </li>
                              <?php else: ?>
                              <?php if($productt->emptyStock()): ?>
                              <li class="addtocart m-1">
                                <a href="javascript:;" class="cart-out-of-stock">

                                  <?php echo e(__('Out Of Stock')); ?></a>
                              </li>
                              <?php else: ?>
                              <?php if($productt->type != "Listing"): ?>
                                <li class="addtocart m-1">
                                  <a href="javascript:;" id="addcrt"><?php echo e(__('Add to Cart')); ?></a>
                                </li>

                                <li class="addtocart m-1">
                                  <a id="qaddcrt" href="javascript:;">
                                    <?php echo e(__('Buy Now')); ?>

                                  </a>
                                </li>
                              <?php endif; ?>

                              <?php if($productt->type == "Listing"): ?>
                                  <?php if(auth()->check()): ?>
                                    <?php if($productt->user_id != 0): ?>
                                      <li>
                                        <a class="view-stor btn--base p-2" href="javascript:;" data-bs-toggle="modal" data-bs-target="#vendorform">
                                          <i class="icofont-ui-chat"></i>
                                          <?php echo e(__('Contact Seller')); ?>

                                        </a>
                                      </li>
                                    <?php else: ?>
                                      <li>
                                        <a class="view-stor btn--base p-2" href="javascript:;" data-bs-toggle="modal" data-bs-target="#sendMessage">
                                          <i class="icofont-ui-chat"></i>
                                          <?php echo e(__('Contact Seller')); ?>

                                        </a>
                                      </li>
                                    <?php endif; ?>
                                  <?php else: ?>
                                    <li>
                                      <a class="view-stor btn--base p-2" href="<?php echo e(route('user.login')); ?>" >
                                        <i class="icofont-ui-chat"></i>
                                        <?php echo e(__('Contact Seller')); ?>

                                      </a>
                                    </li>
                                  <?php endif; ?>
                              <?php endif; ?>

                              <?php endif; ?>
                            </ul>
                         <?php endif; ?>
                   </div>
                      <div class="yith-wcwl-add-to-wishlist wishlist-fragment mt-3">
                          <?php if(Auth::check()): ?>
                          <div class="wishlist-button">
                              <a class="add_to_wishlist new" id="add-to-wish" href="javascript:;" data-href="<?php echo e(route('user-wishlist-add',$productt->id)); ?>"data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist"><?php echo e(__('Wishlist')); ?></a>
                          </div>
                          <?php else: ?>
                          <div class="wishlist-button">
                              <a class="add_to_wishlist" href="<?php echo e(route('user.login')); ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist"><?php echo e(__('Wishlist')); ?></a>
                          </div>
                          <?php endif; ?>

                          <?php if($productt->type != "Listing"): ?>
                            <div class="compare-button">
                              <a class="compare button" data-href="<?php echo e(route('product.compare.add',$productt->id)); ?>" href="javascrit:;" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Compare" aria-label="Compare"><?php echo e(__('Compare')); ?></a>
                            </div>
                          <?php endif; ?>

                      </div>


                       <?php if($gs->is_report): ?>

    

                  <?php if(Auth::guard('web')->check()): ?>

                  <div class="report-area">
                      <a class="report-item" href="javascript:;" data-bs-toggle="modal" data-bs-target="#report-modal"><i class="fas fa-flag"></i> <?php echo e(__('Report This Item')); ?></a>
                  </div>
                  <?php else: ?>
                  <div class="report-area">
                      <a class="report-item" href="<?php echo e(route('user.login')); ?>"><i class="fas fa-flag"></i> <?php echo e(__('Report This Item')); ?> </a>
                  </div>
                  <?php endif; ?>

    

    <?php endif; ?>

                          <div class="my-4 social-linkss social-sharing a2a_kit a2a_kit_size_32">
                              <h5 class="mb-2"><?php echo e(__('Share Now')); ?></h5>
                              <ul class="social-icons py-1 share-product social-linkss py-md-0">
                                  <li>
                                  <a class="facebook a2a_button_facebook" href="">
                                      <i class="fab fa-facebook-f"></i>
                                  </a>
                                  </li>
                                  <li>
                                  <a class="twitter a2a_button_twitter" href="">
                                      <i class="fab fa-twitter"></i>
                                  </a>
                                  </li>
                                  <li>
                                  <a class="linkedin a2a_button_linkedin" href="">
                                      <i class="fab fa-linkedin-in"></i>
                                  </a>
                                  </li>
                                  <li>
                                  <a class="pinterest a2a_button_pinterest" href="">
                                      <i class="fab fa-pinterest-p"></i>
                                  </a>
                                  </li>
                                  <li>
                                      <a class="instagram a2a_button_whatsapp" href="">
                                      <i class="fab fa-whatsapp"></i>
                                      </a>
                                  </li>
                              </ul>

                          </div>
                          <script async src="https://static.addtoany.com/menu/page.js"></script>

                          <?php if(!empty($productt->attributes)): ?>
                      <?php
                        $attrArr = json_decode($productt->attributes, true);
                      ?>
                    <?php endif; ?>
                    <?php if(!empty($attrArr)): ?>
                      <div class="product-attributes my-4">
                        <div class="row gy-4">
                        <?php $__currentLoopData = $attrArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attrKey => $attrVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <?php if(array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1): ?>

                        <div class="col-lg-6">
                            <div class="form-group">
                              <strong class="text-capitalize mb-2 d-block"><?php echo e(str_replace("_", " ", $attrKey)); ?> :</strong>
                              <div class="">
                              <?php $__currentLoopData = $attrVal['values']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionKey => $optionVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="custom-control custom-radio form-check">
                                  <input type="hidden" class="keys" value="">
                                  <input type="hidden" class="values" value="">
                                  <input type="radio" id="<?php echo e($attrKey); ?><?php echo e($optionKey); ?>" name="<?php echo e($attrKey); ?>" class="form-check-input custom-control-input product-attr"  data-key="<?php echo e($attrKey); ?>" data-price = "<?php echo e($attrVal['prices'][$optionKey] * $curr->value); ?>" value="<?php echo e($optionVal); ?>" <?php echo e($loop->first ? 'checked' : ''); ?>>
                                  <label class="form-check-label" for="<?php echo e($attrKey); ?><?php echo e($optionKey); ?>"><?php echo e($optionVal); ?>


                                  <?php if(!empty($attrVal['prices'][$optionKey])): ?>
                                    +
                                    <?php echo e($curr->sign); ?> <?php echo e($attrVal['prices'][$optionKey] * $curr->value); ?>

                                  <?php endif; ?>
                                  </label>
                                </div>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </div>
                            </div>
                        </div>
                          <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                      </div>
                    <?php endif; ?>

                  </div>
              </div>
          </div>
          <div class="col-lg-3 col-md-4">
             <div class="pro-details-sidebar-item mb-4">
                <span><?php echo e(__('Sold By')); ?></span>
                <h5><?php if( $productt->user_id  != 0): ?>

                  <?php if(isset($productt->user)): ?>
                    <?php echo e($productt->user->shop_name); ?>

                  <?php endif; ?>
                  <?php if($productt->user->checkStatus()): ?>
                  <br>
                  <a class="verify-link" href="javascript:;" data-toggle="tooltip" data-placement="top" title=""
                    data-original-title="<?php echo e(__('Verified')); ?>">
                    <i class="fas fa-check-circle"></i>
                  </a>
                  <?php endif; ?>
                <?php else: ?>
                <?php echo e(App\Models\Admin::find(1)->shop_name); ?>

                <?php endif; ?></h5>
                <?php if( $productt->user_id  != 0): ?>
                <h3><?php echo e(App\Models\Product::where('user_id','=',$productt->user_id)->get()->count()); ?></h3>
                <?php else: ?>
                <h3><?php echo e(App\Models\Product::where('user_id','=',0)->get()->count()); ?></h3>
                <?php endif; ?>
                
                <!--<p>-->
                <!--        <strong><?php echo e(__('Location:')); ?></strong>-->
                <!--        <?php if($productt->user_id != 0 && isset($productt->user->country)): ?>-->
                <!--            <?php echo e($productt->country->country_name); ?>-->
                <!--        <?php else: ?>-->
                <!--            <?php echo e(\App\Models\Admin::find(1)?->country?->country_name ?? 'N/A'); ?>-->
                <!--        <?php endif; ?>-->
                <!--    </p>-->
                
                <p>
    <strong><?php echo e(__('Location:')); ?></strong>
    <?php if($productt->product_location != null): ?>
        <?php echo e($productt->cities->city_name); ?>

    <?php else: ?>
        <?php echo e('N/A'); ?>

    <?php endif; ?>
</p>

                
                <h6><?php echo e(__('Total Items')); ?></h6>
                
                <?php if( $productt->user_id  != 0): ?>
            <li class="<?php echo e($gs->is_contact_seller == 0 ? 'contact_seller' : ''); ?> cnt-sell">
                <a href="<?php echo e(route('front.vendor',str_replace(' ', '-', $productt->user->shop_name))); ?>" class="view-stor btn--base">
                  <i class="icofont-ui-travel"></i>
                  <?php echo e(__('Visit Store')); ?>

                </a>
            </li>
            
            <?php if(auth()->guard()->check()): ?>
            <!--<li class="<?php echo e($gs->is_contact_seller == 0 ? 'contact_seller' : ''); ?> cnt-sell">-->
            <!--    <a style="cursor:pointer" id="openChat" class="view-stor btn--base">-->
            <!--        <i class="icofont-ui-chat"></i>-->
            <!--        <?php echo e(__('Chat With Seller')); ?>-->
            <!--    </a>-->

            <!--</li>-->
            <?php endif; ?>
          
            <?php endif; ?>

            

            <?php if($gs->is_contact_seller == 1): ?>

              

              <?php if(Auth::check()): ?>
            

                <?php if($productt->user_id != 0): ?>


                  <a class="view-stor btn--base" href="javascript:;" data-bs-toggle="modal" data-bs-target="#vendorform">
                    <i class="icofont-ui-chat"></i>
                    <?php echo e(__('Contact Seller')); ?>

                  </a>


                <?php else: ?>


                  <a class="view-stor btn--base" href="javascript:;" data-bs-toggle="modal" data-bs-target="#sendMessage">
                    <i class="icofont-ui-chat"></i>
                    <?php echo e(__('Contact Seller')); ?>

                  </a>


                <?php endif; ?>

              <?php else: ?>


              <a class="view-stor btn--base" href="<?php echo e(route('user.login')); ?>" >
                  <i class="icofont-ui-chat"></i>
                  <?php echo e(__('Contact Seller')); ?>

                </a>


              <?php endif; ?>

            <?php endif; ?>

<br>
            <?php if($productt->user_id != 0): ?>
              <?php if(Auth::check()): ?>
                  <?php if(Auth::user()->favorites()->where('vendor_id','=',$productt->user_id)->get()->count() > 0): ?>

                  <a class="fvrt btn--base" href="javascript:;">
                      <i class="icofont-check"></i>
                      <?php echo e(__('Favorite')); ?>

                  </a>
                  <?php else: ?>
                  <a class="view-stor favorite-prod btn--base" href="javascript:;" data-href="<?php echo e(route('user-favorite',[Auth::user()->id,$productt->user_id])); ?>">
                      <i class="icofont-plus"></i>
                      <?php echo e(__('Add To Favorite Seller')); ?>

                  </a>
                  <?php endif; ?>

              <?php else: ?>

              <a class="view-stor btn--base" href="<?php echo e(route('user.login')); ?>" >
                <i class="icofont-plus"></i>
                <?php echo e(__('Add To Favorite Seller')); ?>

              </a>

              <?php endif; ?>
            <?php endif; ?>

            
             </div>
             <?php if(!empty($productt->whole_sell_qty)): ?>
             <div class="pro-summary mb-4">
                <div class="price-summary">
                   <div class="price-summary-content">
                      <h5 class="text-center"><?php echo e(__('Wholesell')); ?></h5>
                      <ul class="price-summary-list">
                            <li class="regular-price"> <h6><?php echo e(__('Quantity')); ?></h6>
                               <span>
                                  <span class="woocommerce-Price-amount amount"><h6><?php echo e(__('Discount')); ?></h6>
                               </span>
                               </span>
                            </li>
                            <?php $__currentLoopData = $productt->whole_sell_qty; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="selling-price"> <label><?php echo e($productt->whole_sell_qty[$key]); ?>+</label> <span><span class="woocommerce-Price-amount amount"><?php echo e($productt->whole_sell_discount[$key]); ?>% <?php echo e(__('Off')); ?>

                               </span>
                               </span>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </ul>
                   </div>
                </div>
             </div>
             <?php endif; ?>



          </div>
      </div>
  </div>
</div>




<div class="message-modal">
  <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="vendorformLabel"><?php echo e(__('Send Message')); ?></h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
          <div class="container-fluid p-0">
            <div class="row">
              <div class="col-md-12">
                <div class="contact-form">
                  <form id="emailreply">
                    <?php echo e(csrf_field()); ?>

                    <ul>

                      <li>
                        <input type="email" class="form-control border mb-1" id="eml" name="email" placeholder="<?php echo e(__('Email *')); ?>" value="<?php echo e(auth()->user() ? auth()->user()->email : ''); ?>" required="" readonly>
                      </li>

                      <li>
                        <input type="text" class="form-control border mb-1" id="subj" name="subject" placeholder="<?php echo e(__('Subject *')); ?>" required="">
                      </li>

                      <li>
                        <textarea class="form-control textarea border mb-1" name="message" id="msg" placeholder="<?php echo e(__('Your Message *')); ?>" required=""></textarea>
                      </li>

                      <input type="hidden" name="name" value="<?php echo e(Auth::user() ? Auth::user()->name:''); ?>">
                      <input type="hidden" name="user_id" value="<?php echo e(Auth::user() ? Auth::user()->id:''); ?>">
                      <input type="hidden" name="vendor_id" value="<?php echo e($productt->user_id); ?>">

                    </ul>
                    <button class="submit-btn" id="emlsub" type="submit"><?php echo e(__('Send Message')); ?></button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>





<div class="message-modal">
  <div class="modal show" id="sendMessage" tabindex="-1" role="dialog" aria-labelledby="sendMessageLabel" aria-modal="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="sendMessageLabel"><?php echo e(__('Send Message')); ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container-fluid p-0">
            <div class="row">
              <div class="col-md-12">
                <div class="contact-form">
                <form action="<?php echo e(route('user-send-message')); ?>" class="emailreply">
                    <?php echo csrf_field(); ?>
                    <ul>
                      <li>
                        <input type="text" class="input-field" name="subject" placeholder="<?php echo e(__('Subject *')); ?>" required="">
                      </li>
                      <li>
                        <textarea class="input-field textarea" name="message" placeholder="<?php echo e(__('Your Message')); ?>" required=""></textarea>
                      </li>
                      <input type="hidden" name="type" value="Ticket">
                    </ul>
                    <button class="submit-btn" type="submit"><?php echo e(__('Send Message')); ?></button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chat Icon -->
<?php if(auth()->guard()->check()): ?>
<div id="chat-icon">
    <i class="icofont-ui-chat chat-icons"></i>
</div>
<?php endif; ?>


<div id="chat-popup" class="d-none">
    <div id="chat-header">
        Chat with <?php echo e($productt->user->name); ?>

        <button id="closeChat">×</button>
    </div>
    <div id="chat-body">
        <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <p class="message <?php echo e($message->sender_id == auth()->id() ? 'sent' : 'received'); ?>">
                <strong><?php echo e($message->sender->name); ?>:</strong> <?php echo e($message->content); ?>

            </p>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div id="chat-footer">
        <input type="text" id="message" placeholder="Type a message...">
        <button id="sendMessage" type="button">Send</button>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function scrollToBottom() {
        $("#chat-body").scrollTop($("#chat-body")[0].scrollHeight);
    }

    // Open Chat when clicking chat icon
    $("#chat-icon").on("click", function () {
        $("#chat-popup").removeClass("d-none").fadeIn();
        scrollToBottom();
    });

    // Close Chat when clicking close button
    $("#closeChat").on("click", function () {
        $("#chat-popup").fadeOut().addClass("d-none");
    });

    // Setup Pusher
    Pusher.logToConsole = true;
    var pusher = new Pusher("3ccc506b109bd00544fe", {
        cluster: "mt1",
        encrypted: true
    });

    var channel = pusher.subscribe("private-chat.<?php echo e(auth()->id()); ?>");

    // Listen for New Messages
    channel.bind("MessageSent", function (data) {
        console.log("Received:", data);
        $("#chat-body").append(`
            <p class="message received">
                <strong>${data.sender_name}:</strong> ${data.message}
            </p>
        `);
        scrollToBottom();
    });

    // Send Message with `$(document).on()`
    $(document).on("click", "#sendMessage", function () {
        let message = $("#message").val().trim();
        let user_id = "<?php echo e($user->id); ?>"; // Ensure this variable is available

        if (message === "") return; // Prevent empty messages

        $.ajax({
            url: "<?php echo e(route('chat.send')); ?>",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>" // Ensure CSRF token is sent
            },
            data: {
                user_id: user_id,
                message: message,
            },
            success: function (response) {
                console.log("Message Sent:", response);
                $("#chat-body").append(`
                    <p class="message sent"><strong>You:</strong> ${message}</p>
                `);
                $("#message").val(""); // Clear input field
                scrollToBottom();
            },
            error: function (xhr, status, error) {
                console.error("Error saving message:", xhr.responseText);
            }
        });
    });

    // Allow sending message on "Enter" key press
    $("#message").keypress(function (e) {
        if (e.which === 13) { // Enter key
            $("#sendMessage").click();
        }
    });
</script><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/partials/product-details/top.blade.php ENDPATH**/ ?>