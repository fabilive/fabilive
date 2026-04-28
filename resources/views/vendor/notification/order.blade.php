	<a class="clear">{{ __('Notifications') }}</a>
	@if(count($datas) > 0)
	<a id="order-notf-clear" data-href="{{ route('vendor-order-notf-clear',Auth::user()->id) }}" class="clear" href="javascript:;">
		{{ __('Clear All') }}
	</a>
	<ul>
	@foreach($datas as $data)
		<li>
			<a href="{{ $data->url ?? route('vendor-order-show', $data->order_number) }}">
				<i class="{{ $data->icon ?? 'fas fa-newspaper' }}"></i>
				{{ $data->message ?? __('You have a new order.') }}
				@if($data->order_number)
					<small class="text-muted d-block">{{ __('Order') }} #{{ $data->order_number }}</small>
				@endif
			</a>
		</li>
	@endforeach
	</ul>

	@else 

	<a class="clear" href="javascript:;">
		{{ __('No New Notifications.')}}
	</a>

	@endif