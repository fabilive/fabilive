<div class="dropdown-header">
    {{ __('Notifications') }}
    @if(count($datas) > 0)
    <a id="user-notf-clear" data-href="{{ route('user-notf-clear') }}" class="float-right text-primary" href="javascript:;">
        {{ __('Clear All') }}
    </a>
    @endif
</div>
<ul>
    @forelse($datas as $data)
    <li>
        <a href="{{ $data->url ?? route('user-order', $data->order()->first()->id ?? 0) }}">
            <i class="{{ $data->icon ?? 'fas fa-info-circle' }} mr-2"></i>
            {{ $data->message ?? __('You have a new update.') }}
            <small class="text-muted d-block">{{ $data->created_at->diffForHumans() }}</small>
        </a>
    </li>
    @empty
    <li>
        <a href="javascript:;" class="text-center">{{ __('No New Notifications.') }}</a>
    </li>
    @endforelse
</ul>
@if(count($datas) > 0)
<div class="dropdown-footer text-center">
    @if(auth()->guard('rider')->check())
        <a href="{{ route('rider-orders') }}">{{ __('View All Jobs') }}</a>
    @else
        <a href="{{ route('user-orders') }}">{{ __('View All Orders') }}</a>
    @endif
</div>
@endif

