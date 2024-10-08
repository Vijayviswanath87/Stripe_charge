@extends('layouts.app')

@section('content')
<div class="container">
 <form id="payment-form">
    <div id="card-errors" role="alert"></div>
    <div class="card">

        <div class="card-header text-center">
            <h2></h2>
        </div>
        <div class="card-body">
            <div class="container">
              <h2>Product : {{ $product->name }}</h2>
             
            
            <div class="shadow-lg p-3 mb-5 bg-white rounded">
                <h4 class="text-center text-muted mb-4">Price: $ {{ $product->price }}</h4>
                <p class="text-center mb-4"><strong>Description :</strong> {{ $product->description }}</p>
            </div>
                <nav class="navbar navbar-light bg-light">
                  Pay with Stripe
                </nav>
            
            <div class="shadow-lg p-5 mb-5 bg-white">
                <div class="shadow-lg p-3 mb-1 bg-white rounded">
                    <div id="card-element"  class="p-3 mb-2 bg-light text-dark" ></div>
                </div>
                <p class="text-center mt-4 mb-4"> <button id="submit" class="btn btn-success">Pay $ {{ $product->price }}</button> </p>
            </div>
        </div>
            
        </div>
    </div>
</form>
</div>
@endsection

@section('scripts')
 <!-- Include the Stripe JavaScript library -->
<script src="https://js.stripe.com/v3/"></script>
<script>
        // Set up Stripe
        var stripe = Stripe('{{ env('STRIPE_PUBLISHABLE_KEY') }}');
        var elements = stripe.elements();
        var card = elements.create('card');
        card.mount('#card-element');

        // Handle the payment form submission
        document.getElementById('payment-form').addEventListener('submit', async (event) => {
            event.preventDefault();

            // Create a new payment method
            const { paymentMethod, error } = await stripe.createPaymentMethod(
                'card', card
            );

            if (error) {
                // Display any error messages
                document.getElementById('card-errors').textContent = error.message;
            } else {
                // Send the payment method ID to the server
                fetch('/checkout', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        payment_method_id: paymentMethod.id,
                        amount:{{ $product->price }},
                    })
                })
                .then((response) => response.json())
                .then((result) => {
                    if (result.requires_action) {
                        // Handle the payment intent
                        stripe.handleCardAction(result.payment_intent_client_secret)
                            .then(function(result) {
                                if (result.error) {
                                    // Display any error messages
                                    document.getElementById('card-errors').textContent = result.error.message;
                                } else {
                                    // Send the payment intent ID to the server
                                    fetch('/checkout', {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            payment_intent_id: result.paymentIntent.id
                                        })
                                    })
                                    .then((response) => response.json())
                                    .then((result) => {
                                        if (result.success) {
                                            // Redirect to the success page
                                            window.location.href = "{{ route('checkout.success') }}";
                                        }
                                    });
                                }
                            });
                    } else {
                        // Redirect to the success page
                        window.location.href = "{{ route('checkout.success') }}";
                    }
                });
            }
        });
    </script>

@endsection
