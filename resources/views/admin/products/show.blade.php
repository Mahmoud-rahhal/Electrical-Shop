@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Product Details</h1>
        <div>
            <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Products
            </a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="main-image mb-3">
                        <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded">
                    </div>
                    @if($product->images)
                        <div class="thumbnail-images d-flex flex-wrap">
                            @foreach($product->images as $image)
                                <div class="thumb me-2 mb-2" style="width: 80px; cursor: pointer;">
                                    <img src="{{ asset('images/products/' . $image) }}" alt="{{ $product->name }}" class="img-fluid rounded border">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <h2>{{ $product->name }}</h2>
                    <div class="mb-3">
                        <span class="badge bg-primary">{{ $product->category->name }}</span>
                        @if($product->featured)
                            <span class="badge bg-success ms-1">Featured</span>
                        @endif
                        @if(!$product->status)
                            <span class="badge bg-danger ms-1">Inactive</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        @if($product->sale_price)
                            <h3 class="text-danger">
                                ${{ number_format($product->sale_price, 2) }}
                                <small class="text-decoration-line-through text-muted">${{ number_format($product->price, 2) }}</small>
                                <span class="badge bg-danger ms-2">Save {{ $product->discount_percentage }}%</span>
                            </h3>
                        @else
                            <h3>${{ number_format($product->price, 2) }}</h3>
                        @endif
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <strong class="me-2">SKU:</strong>
                            <span>{{ $product->sku }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <strong class="me-2">Stock:</strong>
                            @if($product->quantity > 0)
                                <span class="text-success">{{ $product->quantity }} available</span>
                            @else
                                <span class="text-danger">Out of stock</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Description</h5>
                        <p>{{ $product->description }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Reviews</h5>
                        @if($product->reviews->count())
                            @foreach($product->reviews as $review)
                                <div class="review mb-3 pb-2 border-bottom">
                                    <div class="d-flex justify-content-between mb-1">
                                        <strong>{{ $review->user->name }}</strong>
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    @if($review->comment)
                                        <p class="mb-0">{{ $review->comment }}</p>
                                    @endif
                                    <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No reviews yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.thumb img').click(function() {
            $('.main-image img').attr('src', $(this).attr('src'));
        });
    });
</script>
@endpush