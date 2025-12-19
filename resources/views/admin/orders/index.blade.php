@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Orders</h1>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->user ? $order->user->name : 'Guest' }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>{{ $order->item_count }}</td>
                                <td>${{ number_format($order->grand_total, 2) }}</td>
                                <td>
                                    <span class="badge {{ $order->payment_status ? 'bg-success' : 'bg-warning' }}">
                                        {{ $order->payment_status ? 'Paid' : 'Pending' }}
                                    </span>
                                    <small class="d-block text-capitalize">{{ $order->payment_method }}</small>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($order->status == 'completed') bg-success
                                        @elseif($order->status == 'processing') bg-info
                                        @elseif($order->status == 'declined') bg-danger
                                        @else bg-warning @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection