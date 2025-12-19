@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Order #{{ $order->order_number }}</h1>
        <div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Orders
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('images/products/' . $item->product->image) }}" alt="{{ $item->product->name }}" width="60" class="me-3">
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                    <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>${{ number_format($order->grand_total / 1.1, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Tax (10%)</span>
                        <span>${{ number_format($order->grand_total - ($order->grand_total / 1.1), 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span>${{ number_format($order->grand_total, 2) }}</span>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Customer Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Customer</h6>
                        <p class="mb-0">
                            @if($order->user)
                                {{ $order->user->name }}
                                <small class="text-muted d-block">Email: {{ $order->user->email }}</small>
                            @else
                                Guest
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <h6>Contact Information</h6>
                        <p class="mb-0">{{ $order->phone_number }}</p>
                    </div>
                    <div>
                        <h6>Shipping Address</h6>
                        <address class="mb-0">
                            {{ $order->first_name }} {{ $order->last_name }}<br>
                            {{ $order->address }}<br>
                            {{ $order->city }}, {{ $order->country }}<br>
                            @if($order->post_code)
                                {{ $order->post_code }}<br>
                            @endif
                        </address>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Update Status</h5>
                </div>
                <div class="card-body">
<form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
    @csrf

                        <div class="mb-3">
                            <select name="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="declined" {{ $order->status == 'declined' ? 'selected' : '' }}>Declined</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection