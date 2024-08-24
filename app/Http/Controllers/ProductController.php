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

        // Token is created using Checkout.js, this token is sent from the client-side
        $token = $request->stripeToken;

        // Create a charge: this will charge the user's card
        try {
            $charge = Charge::create([
                'amount' => $product->price * 100, // Amount in cents
                'currency' => 'usd',
                'description' => 'Payment for product: ' . $product->name,
                'source' => $token,
            ]);

            // Redirect with a success message
            return redirect()->route('product.show', $id)->with('success', 'Payment successful!');

        } catch (\Exception $e) {
            return redirect()->route('product.show', $id)->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }


}