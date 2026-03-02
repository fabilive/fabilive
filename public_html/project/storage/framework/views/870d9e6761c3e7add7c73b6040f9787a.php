

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <?php if($user->checkWarning()): ?>
    <div class="alert alert-danger validation text-center">
        <h3><?php echo e($user->displayWarning()); ?> </h3> <a
            href="<?php echo e(route('vendor-warning',$user->verifies()->where('admin_warning','=','1')->latest('id')->first()->id)); ?>">
            <?php echo e(__('Verify Now')); ?> </a>
    </div>
    <?php endif; ?>
    <?php echo $__env->make('alerts.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row row-cards-one">
        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg1">
                <div class="left">
                    <h5 class="title"><?php echo e(__('Orders Pending!')); ?> </h5>
                    <span class="number"><?php echo e(count($pending)); ?></span>
                    <a href="<?php echo e(route('vendor-order-index')); ?>" class="link"><?php echo e(__('View All')); ?></a>
                </div>
                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="icofont-dollar"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg2">
                <div class="left">
                    <h5 class="title"><?php echo e(__('Orders Processing!')); ?></h5>
                    <span class="number"><?php echo e(count($processing)); ?></span>
                    <a href="<?php echo e(route('vendor-order-index')); ?>" class="link"><?php echo e(__('View All')); ?></a>
                </div>
                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="icofont-truck-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg3">
                <div class="left">
                    <h5 class="title"><?php echo e(__('Orders Completed!')); ?></h5>
                    <span class="number"><?php echo e(count($completed)); ?></span>
                    <a href="<?php echo e(route('vendor-order-index')); ?>" class="link"><?php echo e(__('View All')); ?></a>
                </div>
                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="icofont-check-circled"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg4">
                <div class="left">
                    <h5 class="title"><?php echo e(__('Total Products!')); ?></h5>
                    <span class="number"><?php echo e(count($user->products)); ?></span>
                    <a href="<?php echo e(route('vendor-prod-index')); ?>" class="link"><?php echo e(__('View All')); ?></a>
                </div>
                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="icofont-cart-alt"></i>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg5">
                <div class="left">
                    <h5 class="title"><?php echo e(__('Total Item Sold!')); ?></h5>
                    <span class="number"><?php echo e(App\Models\VendorOrder::where('user_id','=',$user->id)->where('status','=','completed')->sum('qty')); ?></span>
                </div>
                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="icofont-shopify"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg6">
                
                <div class="left">
                    <h5 class="title"><?php echo e(__('Current Balance')); ?></h5>
                    <span class="number"><?php echo e(App\Models\Product::vendorConvertPrice($actual_balance)); ?></span>
                </div>
                
                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="icofont-dollar-true"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard bg3">
                
                <div class="left">
    <?php
        // Get all vendor orders for the logged-in user with their associated orders
        $vendorOrders = App\Models\VendorOrder::with('order')->where('user_id', Auth::user()->id)->get();

        // Total earning from vendor orders
        $totalPrice = $vendorOrders->sum('price');

        // Total commission from related orders
        $totalCommission = $vendorOrders->sum(function($vo) {
            return optional($vo->order)->commission ?? 0;
        });

        // Net total earning
        $netEarning = $totalPrice - $totalCommission;
    ?>

    <h5 class="title"><?php echo e(__('Total Earning')); ?></h5>
    <span class="number"><?php echo e(App\Models\Product::vendorConvertPrice($netEarning)); ?></span>
</div>


                <div class="right d-flex align-self-center">
                    <div class="icon">
                        <i class="icofont-dollar-true"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="row row-cards-one">
        <div class="col-md-6 col-lg-6 col-xl-6">
            <div class="card">
                <h5 class="card-header"><?php echo e(__('Recent Product(s)')); ?></h5>
                <div class="card-body">

                    <div class="table-responsiv dashboard-home-table">
                        <table id="pproducts" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Featured Image')); ?></th>
                                    <th><?php echo e(__('Name')); ?></th>
                                    <th><?php echo e(__('Category')); ?></th>
                                    <th><?php echo e(__('Type')); ?></th>
                                    <th><?php echo e(__('Price')); ?></th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $pproducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><img
                                            src="<?php echo e(filter_var($data->photo, FILTER_VALIDATE_URL) ?$data->photo:asset('assets/images/products/'.$data->photo)); ?>">
                                    </td>
                                    <td><?php echo e(mb_strlen(strip_tags($data->name),'UTF-8') > 50 ?
                                        mb_substr(strip_tags($data->name),0,50,'UTF-8').'...' : strip_tags($data->name)); ?></td>
                                    <td><?php echo e($data->category->name); ?>

                                        <?php if(isset($data->subcategory)): ?>
                                        <br>
                                        <?php echo e($data->subcategory->name); ?>

                                        <?php endif; ?>
                                        <?php if(isset($data->childcategory)): ?>
                                        <br>
                                        <?php echo e($data->childcategory->name); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($data->type); ?></td>
                                    <td> <?php echo e($data->showPrice()); ?> </td>
                                    <td>
                                        <div class="action-list"><a href="<?php echo e(route('vendor-prod-edit',$data->id)); ?>"><i
                                                    class="fas fa-eye"></i> <?php echo e(__('Details')); ?></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
        <div class="col-md-6 col-lg-6 col-xl-6">
            <div class="card">
                <h5 class="card-header"><?php echo e(__('Recent Order(s)')); ?></h5>
                <div class="card-body">

                    <div class="my-table-responsiv">
                        <table class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>

                                    <th><?php echo e(__('Order Number')); ?></th>
                                    <th><?php echo e(__('Order Date')); ?></th>
                                </tr>
                                <?php $__currentLoopData = $rorders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($data->order_number); ?></td>
                                    <td><?php echo e(date('Y-m-d',strtotime($data->created_at))); ?></td>
                                    <td>
                                        <div class="action-list"><a href="<?php echo e(route('vendor-order-show',$data->order_number)); ?>"><i class="fas fa-eye"></i> <?php echo e(__('Details')); ?></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cards-one">

        <div class="col-md-12 col-lg-12 col-xl-12">
            <div class="card">
                <h5 class="card-header"><?php echo e(__('Total Sales in Last 30 Days')); ?></h5>
                <div class="card-body">

                    <canvas id="lineChart"></canvas>

                </div>
            </div>

        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

<script type="text/javascript">
    (function($) {
		"use strict";

    displayLineChart();

    function displayLineChart() {
        var data = {
            labels: [
            <?php echo $days; ?>

            ],
            datasets: [{
                label: "Prime and Fibonacci",
                fillColor: "#3dbcff",
                strokeColor: "#0099ff",
                pointColor: "rgba(220,220,220,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [
                <?php echo $sales; ?>

                ]
            }]
        };
        var ctx = document.getElementById("lineChart").getContext("2d");
        var options = {
            responsive: true
        };
        var lineChart = new Chart(ctx).Line(data, options);
    }

    $('#poproducts').dataTable( {
      "ordering": false,
          'lengthChange': false,
          'searching'   : false,
          'ordering'    : false,
          'info'        : false,
          'autoWidth'   : false,
          'responsive'  : true,
          'paging'  : false
    } );

    $('#pproducts').dataTable( {
      "ordering": false,
      'lengthChange': false,
          'searching'   : false,
          'ordering'    : false,
          'info'        : false,
          'autoWidth'   : false,
          'responsive'  : true,
          'paging'  : false
    } );


     
    })(jQuery);
    
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vendor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/vendor/index.blade.php ENDPATH**/ ?>