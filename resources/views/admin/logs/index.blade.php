@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Server Error Logs') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="javascript:;">{{ __('System Settings') }} </a>
                    </li>
                    <li>
                        <a href="{{ route('admin-logs-index') }}">{{ __('Server Error Logs') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="add-product-content1">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                        
                        <div class="row mb-3">
                            <div class="col-lg-12 text-right">
                                <a href="{{ route('admin-logs-clear') }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to clear all logs?')">
                                    <i class="fas fa-trash"></i> {{ __('Clear Logs') }}
                                </a>
                                <a href="{{ route('admin-logs-index') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-sync"></i> {{ __('Refresh') }}
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <textarea class="form-control" rows="30" style="background: #1e1e1e; color: #d4d4d4; font-family: 'Courier New', Courier, monospace; font-size: 14px; line-height: 1.5;" readonly>{{ $logs }}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var textarea = document.querySelector('textarea');
        textarea.scrollTop = textarea.scrollHeight;
    });
</script>
@endsection
