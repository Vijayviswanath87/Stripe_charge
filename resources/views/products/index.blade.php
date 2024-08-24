@extends('layouts.app')

@section('content')

<div class="container">
    <h2 class="my-4 text-center bg-primary text-white p-2">Electronic Products</h2>
    <div class="row">
        @foreach($products as $product)
        <div class="col-md-3">
            <div class="card mb-4 shadow-sm">
                <h5 class="card-title p-2"> <b>{{ $product->name }}</b></h5>
                <div class="card-body p-2">
                    <p class="card-text mb-1"><strong>Price:</strong> ${{ $product->price }}</p>
                    <p class="card-text"><strong>Description:</strong>{{ $product->description }}</p>
                </div>
                <div class="card-footer p-2">
                    <a href="/product/{{ $product->id }}" class="btn btn-primary btn-block buy-now-btn">Buy Now</a>
                    <div class="spinner-border text-primary d-none" role="status" id="loading-spinner-{{ $product->id }}">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script>
        // Get all the buy now buttons
        document.querySelectorAll('.buy-now-btn').forEach(button => {
                // Add an event listener to each button
                 button.addEventListener('click', function() {
                    // Hide the button and show the loading spinner
                     this.classList.add('d-none');
                     document.getElementById('loading-spinner-' + this.href.split('/').pop()).classList.remove('d-none');
                });
        });

</script>
@endsection
