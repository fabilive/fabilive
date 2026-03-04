<?php $__env->startSection('styles'); ?>
    <style type="text/css">
        .table-responsive {
            overflow-x: hidden;
        }

        table#example2 {
            margin-left: 10px;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading"><?php echo e(__('Rider Details')); ?> <a class="add-btn" href="<?php echo e(url()->previous()); ?>"><i
                                class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?></a></h4>
                    <ul class="links">
                        <li>
                            <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin-rider-index')); ?>"><?php echo e(__('Riders')); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin-rider-show', $data->id)); ?>"><?php echo e(__('Details')); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="add-product-content1 customar-details-area add-product-content2">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="user-image">
                                        <?php if($data->is_provider == 1): ?>
                                            <img src="<?php echo e($data->photo ? asset($data->photo) : asset('assets/images/' . $gs->user_image)); ?>"
                                                alt="No Image">
                                        <?php else: ?>
                                            <img src="<?php echo e($data->photo ? asset('assets/images/users/' . $data->photo) : asset('assets/images/' . $gs->user_image)); ?>"
                                                alt="No Image">
                                        <?php endif; ?>
                                        <a href="javascript:;" class="mybtn1 send" data-email="<?php echo e($data->email); ?>"
                                            data-toggle="modal" data-target="#vendorform"><?php echo e(__('Send Message')); ?></a>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="table-responsive show-table">
                                        <table class="table">
                                            <tr>
                                                <th><?php echo e(__('ID#')); ?></th>
                                                <td><?php echo e($data->id); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__('Name')); ?></th>
                                                <td><?php echo e($data->name); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__('Email')); ?></th>
                                                <td><?php echo e($data->email); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__('Phone')); ?></th>
                                                <td><?php echo e($data->phone); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__('Address')); ?></th>
                                                <td><?php echo e($data->address); ?></td>
                                            </tr>

                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="table-responsive show-table">
                                        <table class="table">

                                            <?php if($data->country != null): ?>
                                                <tr>
                                                    <th><?php echo e(__('Country')); ?></th>
                                                    <td><?php echo e($data->country); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php if($data->city_id != null): ?>
                                                <tr>
                                                    <th><?php echo e(__('City')); ?></th>
                                                    <td><?php echo e($data->city->city_name); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php if($data->state_id != null): ?>
                                                <tr>
                                                    <th><?php echo e(__('State')); ?></th>
                                                    <td><?php echo e($data->state->state); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php if($data->fax != null): ?>
                                                <tr>
                                                    <th><?php echo e(__('Fax')); ?></th>
                                                    <td><?php echo e($data->fax); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php if($data->zip != null): ?>
                                                <tr>
                                                    <th><?php echo e(__('Zip Code')); ?></th>
                                                    <td><?php echo e($data->zip); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                            <tr>
                                                <th><?php echo e(__('Joined')); ?></th>
                                                <td><?php echo e($data->created_at->diffForHumans()); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__('SubMerchant Delivery Image')); ?></th>
                                                <td><a href="<?php echo e(asset('assets/images/submerchantagreementrider/' . $data->submerchant_agreement)); ?>"
                                                        target="_blank">View</a></td>
                                            </tr>

                                            
                                            <?php if(isset($data) && $data->rider_type === 'company'): ?>
                                                <tr>
                                                    <th><?php echo e(__('Live Company Selfie')); ?></th>
                                                    <td>
                                                        <?php if(
                                                            !empty($data->live_selfie_company) &&
                                                                file_exists(public_path('assets/images/liveselfiecompany/' . $data->live_selfie_company))): ?>
                                                            <a href="<?php echo e(asset('assets/images/liveselfiecompany/' . $data->live_selfie_company)); ?>"
                                                                target="_blank">View</a>
                                                        <?php else: ?>
                                                            <span><?php echo e(__('Not available')); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th><?php echo e(__('Company Registration Documents')); ?></th>
                                                    <td>
                                                        <?php if(
                                                            !empty($data->company_registration_document) &&
                                                                file_exists(public_path('assets/images/companyregistrationdocument/' . $data->company_registration_document))): ?>
                                                            <a href="<?php echo e(asset('assets/images/companyregistrationdocument/' . $data->company_registration_document)); ?>"
                                                                target="_blank">View</a>
                                                        <?php else: ?>
                                                            <span><?php echo e(__('Not available')); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th><?php echo e(__('Valid ID/Passport of Company Owner')); ?></th>
                                                    <td>
                                                        <?php if(
                                                            !empty($data->id_company_owner) &&
                                                                file_exists(public_path('assets/images/companyownerid/' . $data->id_company_owner))): ?>
                                                            <a href="<?php echo e(asset('assets/images/companyownerid/' . $data->id_company_owner)); ?>"
                                                                target="_blank">View</a>
                                                        <?php else: ?>
                                                            <span><?php echo e(__('Not available')); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th><?php echo e(__('Transport License / Permis de Transport.')); ?></th>
                                                    <td>
                                                        <?php if(
                                                            !empty($data->transport_license) &&
                                                                file_exists(public_path('assets/images/transportlicense/' . $data->transport_license))): ?>
                                                            <a href="<?php echo e(asset('assets/images/transportlicense/' . $data->transport_license)); ?>"
                                                                target="_blank">View</a>
                                                        <?php else: ?>
                                                            <span><?php echo e(__('Not available')); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th><?php echo e(__('Company Insurance Cerfificate')); ?></th>
                                                    <td>
                                                        <?php if(
                                                            !empty($data->insurance_certificate_company) &&
                                                                file_exists(public_path('assets/images/insurancecertificatecompany/' . $data->insurance_certificate_company))): ?>
                                                            <a href="<?php echo e(asset('assets/images/insurancecertificatecompany/' . $data->insurance_certificate_company)); ?>"
                                                                target="_blank">View</a>
                                                        <?php else: ?>
                                                            <span><?php echo e(__('Not available')); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th><?php echo e(__('Fabilive Company Rider Agreement')); ?></th>
                                                    <td><a href="<?php echo e(asset('assets/pdf/fabiliveridercompany.pdf')); ?>"
                                                            target="_blank">View</a></td>
                                                </tr>

                                                <tr>
                                                    <th><?php echo e(__('Company Taxpayer Registration number (TIN)')); ?></th>
                                                    <td><?php echo e($data->tin_company ?? __('Not provided')); ?></td>
                                                </tr>
                                            <?php elseif(isset($data) && $data->rider_type === 'individual'): ?>
                                                <tr>
                                                    <th><?php echo e(__('Live Individual Selfie')); ?></th>
                                                    <td>
                                                        <?php if(
                                                            !empty($data->live_selfie_individual) &&
                                                                file_exists(public_path('assets/images/liveselfieindividual/' . $data->live_selfie_individual))): ?>
                                                            <a href="<?php echo e(asset('assets/images/liveselfieindividual/' . $data->live_selfie_individual)); ?>"
                                                                target="_blank">View</a>
                                                        <?php else: ?>
                                                            <span><?php echo e(__('Not available')); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th><?php echo e(__('Individual Drivers License')); ?></th>
                                                    <td>
                                                        <?php if(
                                                            !empty($data->driver_license_individual) &&
                                                                file_exists(public_path('assets/images/driverlicenseindividual/' . $data->driver_license_individual))): ?>
                                                            <a href="<?php echo e(asset('assets/images/driverlicenseindividual/' . $data->driver_license_individual)); ?>"
                                                                target="_blank">View</a>
                                                        <?php else: ?>
                                                            <span><?php echo e(__('Not available')); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th><?php echo e(__('Vehicle Registration Certificate (Carte Grise)')); ?></th>
                                                    <td>
                                                        <?php if(
                                                            !empty($data->vehicle_registration_certificate) &&
                                                                file_exists(public_path('assets/images/vehicleregistrationcertificate/' . $data->vehicle_registration_certificate))): ?>
                                                            <a href="<?php echo e(asset('assets/images/vehicleregistrationcertificate/' . $data->vehicle_registration_certificate)); ?>"
                                                                target="_blank">View</a>
                                                        <?php else: ?>
                                                            <span><?php echo e(__('Not available')); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th><?php echo e(__('Insurance Certificate')); ?></th>
                                                    <td>
                                                        <?php if(
                                                            !empty($data->insurance_certificate_individual) &&
                                                                file_exists(public_path('assets/images/insurancecertificateindividual/' . $data->insurance_certificate_individual))): ?>
                                                            <a href="<?php echo e(asset('assets/images/insurancecertificateindividual/' . $data->insurance_certificate_individual)); ?>"
                                                                target="_blank">View</a>
                                                        <?php else: ?>
                                                            <span><?php echo e(__('Not available')); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th><?php echo e(__('Criminal records / Police Report')); ?></th>
                                                    <td>
                                                        <?php if(
                                                            !empty($data->criminal_records) &&
                                                                file_exists(public_path('assets/images/criminalrecords/' . $data->criminal_records))): ?>
                                                            <a href="<?php echo e(asset('assets/images/criminalrecords/' . $data->criminal_records)); ?>"
                                                                target="_blank">View</a>
                                                        <?php else: ?>
                                                            <span><?php echo e(__('Not available')); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th><?php echo e(__('Fabilive Rider Agreement')); ?></th>
                                                    <td>
                                                        <?php if(!empty($data->submerchant_agreement)): ?>
                                                            <a href="<?php echo e(asset('assets/images/submerchantagreementrider/'.$data->submerchant_agreement)); ?>" target="_blank">
                                                                View
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-danger">Not Uploaded</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th><?php echo e(__('Vehicle Type Individual')); ?></th>
                                                    <td><?php echo e($data->vehicle_type_individual ?? __('Not provided')); ?></td>
                                                </tr>

                                                <tr>
                                                    <th><?php echo e(__('Individual Taxpayer Registration number (TIN)')); ?></th>
                                                    <td><?php echo e($data->tin_individual ?? __('Not provided')); ?></td>
                                                </tr>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="2">
                                                        <?php echo e(__('No rider type selected or data not found.')); ?></td>
                                                </tr>
                                            <?php endif; ?>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="order-table-wrap">
                            <div class="order-details-table">
                                <div class="mr-table">
                                    <h4 class="title"><?php echo e(__('Products Ordered')); ?></h4>
                                    <div class="table-responsive">
                                        <table id="example2" class="table table-hover dt-responsive" cellspacing="0"
                                            width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?php echo e(__('Order ID')); ?></th>
                                                    <th><?php echo e(__('Purchase Date')); ?></th>
                                                    <th><?php echo e(__('Order Amount')); ?></th>
                                                    <th><?php echo e(__('Status')); ?></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $data->orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><a
                                                                href="<?php echo e(route('admin-order-invoice', $order->order->id)); ?>"><?php echo e(sprintf("%'.08d", $order->order->id)); ?></a>
                                                        </td>
                                                        <td><?php echo e(Carbon\Carbon::parse($order->created_at)->format('d/m/Y')); ?>

                                                        </td>
                                                        <td><?php echo e(\PriceHelper::showOrderCurrencyPrice(
                                                            $order->order->pay_amount * $order->order->currency_value,
                                                            $order->order->currency_sign,
                                                        )); ?>

                                                        </td>
                                                        <td><?php echo e(ucwords($order->status)); ?></td>
                                                        <td>
                                                            <a href=" <?php echo e(route('admin-order-show', $order->order->id)); ?>"
                                                                class="view-details">
                                                                <i class="fas fa-check"></i><?php echo e(__('Details')); ?>

                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="sub-categori">
        <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vendorformLabel"><?php echo e(__('Send Message')); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid p-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="contact-form">
                                        <form id="emailreply1">
                                            <?php echo e(csrf_field()); ?>

                                            <ul>
                                                <li>
                                                    <input type="email" class="input-field eml-val" id="eml1"
                                                        name="to" placeholder="<?php echo e(__(' Email')); ?> *"
                                                        value="" required="">
                                                </li>
                                                <li>
                                                    <input type="text" class="input-field" id="subj1"
                                                        name="subject" placeholder="<?php echo e(__(' Subject')); ?> *"
                                                        required="">
                                                </li>
                                                <li>
                                                    <textarea class="input-field textarea" name="message" id="msg1" placeholder="<?php echo e(__(' Your Message')); ?> *"
                                                        required=""></textarea>
                                                </li>
                                            </ul>
                                            <button class="submit-btn" id="emlsub1"
                                                type="submit"><?php echo e(__('Send Message')); ?></button>
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

    

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript">
        (function($) {
            "use strict";

            $('#example2').dataTable({
                "ordering": false,
                'paging': false,
                'lengthChange': false,
                'searching': false,
                'ordering': false,
                'info': false,
                'autoWidth': false,
                'responsive': true
            });

        })(jQuery);
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/rider/show.blade.php ENDPATH**/ ?>