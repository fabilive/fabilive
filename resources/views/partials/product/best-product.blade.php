<div class="col">
    <div class="product type-product">
        <div class="product-wrapper">
            <div class="product-image">
                @if($prod->{'3d_model'})
                <div class="product-3d-container">
                    <model-viewer class="product-3d-viewer" src="{{ asset($prod->{'3d_model'}) }}" ar ar-modes="webxr scene-viewer quick-look" camera-controls touch-action="pan-y" shadow-intensity="1" auto-rotate></model-viewer>
                </div>
                @else
                <a href="{{ route('front.product', $prod->slug ?: 'missing-slug') }}" class="woocommerce-LoopProduct-link"><img src="{{ $prod->thumbnail }}" alt="Product Image"></a>
                @endif
                @if (round($prod->offPercentage() )>0)
                <div class="on-sale">-{{ round($prod->offPercentage() )}}%</div>
                @endif
            </div>
            <div class="product-info">
                <h3 class="product-title"><a href="{{ route('front.product', $prod->slug ?: 'missing-slug') }}">{{ $prod->showName() }}</a></h3>
                <div class="product-price">
                    <div class="price">
                        <ins>{{ $prod->showPrice() }} </ins>
                        <del>{{ $prod->showPreviousPrice() }}</del>
                    </div>
                </div>
               
                <div class="shipping-feed-back">
                    <div class="star-rating">
                        <div class="rating-wrap">
                            <p><i class="fas fa-star"></i><span> {{ number_format($prod->ratings_avg_rating,1) }} ({{ $prod->ratings_count }})</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>