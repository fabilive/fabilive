@extends('layouts.vendor')

@section('content')
<div class="content-area">
    <h3>Customer Messages</h3>
    <ul class="list-group">
        @foreach ($customers as $customer)
            @php
                $unreadCount = \App\Models\LiveMessage::where('sender_id', $customer->id)
                                ->where('receiver_id', auth()->id())
                                ->where('is_read', false)
                                ->count();
            @endphp

            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('vendor.chat', $customer->id) }}" class="text-decoration-none">
                    <img src="{{ asset('assets/images/noimage.png') }}" alt="Profile" class="rounded-circle" width="40">
                    {{ $customer->name }}
                </a>
                @if ($unreadCount > 0)
                    <span class="badge bg-danger text-white">{{ $unreadCount }}</span>
                @endif
                
            </li>
        @endforeach
    </ul>
</div>
@endsection
