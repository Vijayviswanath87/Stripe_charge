<?php
/**********************************************************************************************
 * Filename       : ProductController .php
 * Task           : Stipe -Payment
 * Creation Date  : 24-08-2024
 * Author         : Vijay Viswanath
 * Description    : Products listing and payments by using stripe payment
*********************************************************************************************/ 

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Exception\CardException;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Stripe\PaymentIntent;

/**
 * Controller for handling product-related actions
 */

class ProductController extends Controller
{
    /**
     * Display a listing of all products
     *
     * @return \Illuminate\View\View
     */

    public function index()
    {
        // Retrieve all products from the database
            $products = Product::all();

        // Return the view with the products data
            return view('products.index', compact('products'));
    }


    /**
     * Display a single product
     *
     * @param int $id
     * @return \Illuminate\View\View
     */

    public function show($id)
    {
        // Retrieve the product from the database by ID
            $product = Product::find($id);

        // Return the view with the product data
            return view('products.show', compact('product'));
    }

    /**
     * Handle product purchase
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function purchase(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Set Stripe secret key
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        // Create a new payment intent
        $paymentIntent = PaymentIntent::create([
            'amount' => $product->price, // $20.00
            'currency' => 'usd',
            'payment_method' => $request->payment_method_id,
            'description' => 'Payment for product: ' . $product->name,
            'confirmation_method' => 'manual',
            'confirm' => true,
            'return_url' => route('checkout.complete'),
        ]);

        // Check if the payment intent requires action
        if ($paymentIntent->status == 'requires_action') {
            return response()->json([
                'requires_action' => true,
                'payment_intent_client_secret' => $paymentIntent->client_secret
            ]);
        } else {
            return response()->json(['success' => true]);
        }
    }

    /**
     * Show the checkout form
     *
     * @return \Illuminate\View\View
    */

    public function showCheckoutForm()
    {
        return view('cashier.checkout');
    }

    /**
     * Show the checkout success page
     *
     * @return \Illuminate\View\View
    */

     public function checkoutSuccess()
    {
        return view('cashier.success');
    }

    /**
     * Show the checkout cancel page
     *
     * @return \Illuminate\View\View
     */

    public function checkoutCancel()
    {
        return view('cashier.cancel');
    }

    /**
     * Process a payment request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function processPayment(Request $request)
    {
        // Set the Stripe API key
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        // Create a new payment intent
        $paymentIntent = PaymentIntent::create([
            'amount' => $request->amount, // $20.00
            'currency' => 'usd',
            'payment_method' => $request->payment_method_id,
            'confirmation_method' => 'manual',
            'confirm' => true,
            'return_url' => route('checkout.complete'),
        ]);

        // Check the payment intent status
        if ($paymentIntent->status == 'requires_action') {
            // If the payment intent requires action, return a JSON response with the client secret
            return response()->json([
                'requires_action' => true,
                'payment_intent_client_secret' => $paymentIntent->client_secret
            ]);
        } else {
            // If the payment intent is successful, return a JSON response with a success flag
            return response()->json(['success' => true]);
        }
    }

    /**
     * Complete the payment
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function completePayment(Request $request)
    {
        // Here you can handle the result of the payment, check if it succeeded, and display a message to the user
        $paymentIntentId = $request->query('payment_intent');
        $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);

        if ($paymentIntent->status == 'succeeded') {
            return redirect()->route('checkout.success')->with('success', 'Payment succeeded!');
        } else {
            return redirect()->route('checkout.cancel')->with('error', 'Payment failed!');
        }
    }

}