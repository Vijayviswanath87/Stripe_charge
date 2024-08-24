@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Display error or success messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

    <div class="card">
        <div class="card-header text-center">
            <h2>{{ $product->name }}</h2>
        </div>
        <div class="card-body">
            <h4 class="text-center text-muted mb-4">Price: ${{ $product->price }}</h4>
            <p class="text-center mb-4"><strong>Description :</strong> {{ $product->description }}</p>

            <form action="/purchase/{{ $product->id }}" method="POST">
            @csrf
             <p class="text-center mb-4">
            <script
                src="https://checkout.stripe.com/checkout.js"
                class="stripe-button"
                data-key="{{ env('STRIPE_PUBLISHABLE_KEY') }}"
                data-amount="{{ $product->price * 100 }}"  <!-- Amount in cents -->
                data-name="{{ $product->name }}"
                data-description="{{ $product->description }}"
                data-currency="usd"
                data-locale="auto">
            </script>
        </p>
        </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>

    // Initialize Stripe with publishable key
    const stripe = Stripe('{{ env('STRIPE_PUBLISHABLE_KEY') }}');
    // Create Stripe elements
    const elements = stripe.elements();
    const card = elements.create('card'); // Create card element
    card.mount('#card-element'); // Mount card element to the DOM

   // Get the payment form
   const form = document.getElementById('payment-form');
    // Add event listener to the form submission
    form.addEventListener('submit', async (event) => {
        event.preventDefault();     // Prevent default form submission
        // Create payment method with Stripe
        const { paymentMethod, error } = await stripe.createPaymentMethod('card', card);

         // Handle error or success response
        if (error) {
            console.log(error);
        } else {

            // Get payment method ID and product ID
            const paymentMethodId = paymentMethod.id;
            const productId = {{ $product->id }};

            try {

                // Make POST request to the server to process payment
                const response = await fetch('/purchase/' + productId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ paymentMethodId })
                });

                // Handle response from the server
                if (response) {
                       try 
                       {
                            const result = await response.json();
                            // Handle JSON response
                            if (result.message) {
                                document.getElementById('payment-success-message').textContent = result.message;
                                document.getElementById('payment-success').style.display = 'block';
                            } else if (result.error) {
                                document.getElementById('payment-errors-message').textContent = result.error;
                                document.getElementById('payment-errors').style.display = 'block';
                            }
                        } catch (error) {
                            console.error('Error parsing response as JSON:', error);
                            const responseText = await response.text();
                            console.error('Response text:', responseText);
                        }
                } else {
                    console.error('Error occurred');
                    const responseText = await response.text();
                    console.error('Response text:', responseText);
                }
            } catch (error) {
                 console.error('Fetch failed:', error);

            }
        }
    });

</script>
@endsection
