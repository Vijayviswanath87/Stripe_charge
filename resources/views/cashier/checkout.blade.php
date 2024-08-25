<!-- resources/views/checkout.blade.php -->
@extends('layouts.app')

@section('content')
    <form id="payment-form">
        <!-- <div id="card-element"></div>
        <button id="submit">Pay {{ $product->price * 100 }}</button>
        <div id="card-errors" role="alert"></div> -->


        <div class="card">

        <div class="card-header text-center">
            <h2>{{ $product->name }}</h2>
        </div>
        <div class="card-body">
            <div id="card-element"></div>
            <div id="card-errors" role="alert"></div>
            <h4 class="text-center text-muted mb-4">Price: ${{ $product->price }}</h4>
            <p class="text-center mb-4"><strong>Description :</strong> {{ $product->description }}</p>
            <button id="submit">Pay {{ $product->price * 100 }}</button>

           
        </div>
    </div>
    </form>




        
</div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('{{ env('STRIPE_PUBLISHABLE_KEY') }}');
        var elements = stripe.elements();
        var card = elements.create('card');
        card.mount('#card-element');

        document.getElementById('payment-form').addEventListener('submit', async (event) => {
            event.preventDefault();

            const { paymentMethod, error } = await stripe.createPaymentMethod(
                'card', card
            );

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
            } else {
                fetch('/checkout', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        payment_method_id: paymentMethod.id
                    })
                })
                .then((response) => response.json())
                .then((result) => {
                    if (result.requires_action) {
                        stripe.handleCardAction(result.payment_intent_client_secret)
                            .then(function(result) {
                                if (result.error) {
                                    document.getElementById('card-errors').textContent = result.error.message;
                                } else {
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
                                            window.location.href = "{{ route('checkout.success') }}";
                                        }
                                    });
                                }
                            });
                    } else {
                        window.location.href = "{{ route('checkout.success') }}";
                    }
                });
            }
        });
    </script>
@endsection
