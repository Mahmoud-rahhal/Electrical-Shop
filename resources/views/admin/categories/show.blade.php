@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Category Details</h1>
        <div>
            <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Categories
            </a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    @if($category->image)
                        <div class="mb-4">
                            <img src="{{ asset('images/categories/' . $category->image) }}" alt="{{ $category->name }}" class="img-fluid rounded">
                        </div>
                    @else
                        <div class="mb-4 text-center py-5 bg-light rounded">
                            <i class="fas fa-image fa-3x text-muted"></i>
                            <p class="mt-2 mb-0">No image available</p>
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <h2>{{ $category->name }}</h2>
                    <div class="mb-4">
                        <h5>Description</h5>
                        <p>{{ $category->description ?? 'No description available' }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Products in this Category</h5>
                        @if($category->products->count())
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($category->products->take(5) as $product)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                                                        {{ $product->name }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @if($product->sale_price)
                                                        <span class="text-danger">${{ number_format($product->sale_price, 2) }}</span>
                                                        <small class="text-decoration-line-through text-muted d-block">${{ number_format($product->price, 2) }}</small>
                                                    @else
                                                        ${{ number_format($product->price, 2) }}
                                                    @endif
                                                </td>
                                                <td>{{ $product->quantity }}</td>
                                                <td>
                                                    @if($product->status)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($category->products->count() > 5)
                                <div class="text-center mt-2">
                                    <a href="{{ route('products.index', ['category' => $category->id]) }}" class="btn btn-sm btn-outline-primary">
                                        View All {{ $category->products->count() }} Products
                                    </a>
                                </div>
                            @endif
                        @else
                            <p class="text-muted">No products in this category yet.</p>
                        @endif
                    </div>
                    
                    <div class="d-flex">
                        <div class="me-4">
                            <h6>Created At</h6>
                            <p class="mb-0">{{ $category->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <h6>Updated At</h6>
                            <p class="mb-0">{{ $category->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection