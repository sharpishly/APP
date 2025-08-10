<?php

namespace App\Http\Controllers;

use App\Models\CartModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ProductModel;
use Exception;
use Illuminate\Support\Facades\DB; //  For transactions (Laravel, adapt if needed)
use Illuminate\Support\Facades\Mail; // For sending emails (Laravel, adapt if needed)
use Illuminate\Support\Str; // Import the Str class - Add this line

class CheckoutController extends BaseController // Extend your base controller
{
    private $cartModel;
    private $orderModel;
    private $orderItemModel;
    private $productModel;

    public function __construct()
    {
        parent::__construct(); // Call the parent constructor
        $this->cartModel = new CartModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->productModel = new ProductModel();
    }

    /**
     * Display the checkout page (gather shipping info, etc.).
     *
     * @return void
     */
    public function index()
    {
        //  Authentication check (ensure user is logged in)
        if (!auth()->check()) { // Adapt to your authentication system
            $this->redirect('/login'); // Redirect to login page
            return;
        }

        $userId = auth()->user()->id; // Get the logged-in user's ID
        $cartItems = $this->cartModel->getCartByUserId($userId);

        if (empty($cartItems)) {
             $this->redirect('/cart'); // Redirect to cart page if it is empty
             return;
        }

        // Get user data, shipping options, etc.
        $userData = $this->getUserData($userId);  //Adapt this method
        $shippingOptions = $this->getShippingOptions(); // Adapt this method

        $this->view->cartItems = $cartItems;
        $this->view->userData = $userData;
        $this->view->shippingOptions = $shippingOptions;
        $this->view->render('checkout/index'); // Render your checkout form
    }

    /**
     * Handle the order processing logic.
     *
     * @return void
     */
    public function processOrder()
    {
        // 1. Authentication Check
        if (!auth()->check()) { // Adapt to your authentication system
            $this->redirect('/login');
            return;
        }

        $userId = auth()->user()->id;
        // 2. Get Cart Contents
        $cartItems = $this->cartModel->getCartByUserId($userId);

        // 3. Validate Cart and Check Stock
        if (empty($cartItems)) {
            $this->flash('error', 'Your cart is empty.'); // Use your flash message system
            $this->redirect('/cart');
            return;
        }

        $totalAmount = 0;
        $outOfStockProducts = [];

        foreach ($cartItems as $item) {
            $product = $this->productModel->getProductById($item->product_id);
            if ($product->stock < $item->quantity) {
                $outOfStockProducts[] = [
                    'name' => $product->name,
                    'available_stock' => $product->stock,
                    'requested_quantity' => $item->quantity,
                ];
            }
            $totalAmount += ($product->price * $item->quantity); // calculate total amount
        }

        if (!empty($outOfStockProducts)) {
            $errorMessage = 'The following products are out of stock: ';
            foreach ($outOfStockProducts as $product) {
                $errorMessage .= "{$product['name']} (Available: {$product['available_stock']}, Requested: {$product['requested_quantity']}), ";
            }
            $errorMessage = rtrim($errorMessage, ', '); // Remove the trailing comma
            $this->flash('error', $errorMessage);
            $this->redirect('/cart');
            return;
        }

        // 4.  Get Order Data
        $orderData = $this->getOrderData(); // Validate and retrieve order data (shipping, billing, etc.)
        if ($orderData['error']) {
            $this->flash('error', $orderData['message']);
            $this->redirect('/checkout'); //redirect to checkout
            return;
        }

        $orderNumber = Str::random(10); // Generate a unique order number.
        $status = 'pending';  // Initial order status

        // 5. Begin Database Transaction
        DB::beginTransaction(); // Adapt to your framework's transaction handling

        try {
            // 6. Create Order
            $orderId = $this->orderModel->createOrder($userId, $totalAmount, $orderNumber, $status);

            // 7. Create Order Items
            foreach ($cartItems as $item) {
                $product = $this->productModel->getProductById($item->product_id); //get current price.
                $subtotal = $product->price * $item->quantity;
                $this->orderItemModel->createOrderItem($orderId, $item->product_id, $item->quantity, $product->price, $subtotal);

                // 8. Update Product Stock
                $this->productModel->updateStock($item->product_id, $item->quantity);
            }

            // 9. Commit Transaction
            DB::commit();

            // 10. Clear Cart
            $this->cartModel->clearCart($userId);

            // 11. Send Order Confirmation Email
            $this->sendOrderConfirmationEmail($orderId); //  Adapt this method

            // 12. Display Order Confirmation Page
            $this->view->orderNumber = $orderNumber;
            $this->view->orderId = $orderId;
            $this->view->totalAmount = $totalAmount;
            $this->view->render('checkout/confirmation'); // Create this view
        } catch (Exception $e) {
            // 13. Rollback Transaction and Handle Error
            DB::rollBack();
            $this->handleError("Error processing order: " . $e->getMessage()); // Adapt error handling
            $this->flash('error', 'There was a problem processing your order. Please try again.');
            $this->redirect('/checkout'); //redirect
            return;
        }
    }

    /**
     * Validates and retrieves order data from the request.
     *
     * @return array An array containing the order data or an error message.
     */
    private function getOrderData(): array
    {
        //  Adapt this method to your framework's request handling.
        //  This is a simplified example.  Include thorough validation.
        $data = [
            'shipping_address' => request('shipping_address'),  //  Get data from request
            'billing_address' => request('billing_address'),
            'payment_method' => request('payment_method'),
            // Add other order-related fields as necessary
        ];

        //  Example Validation (Adapt to your validation library/method)
        if (empty($data['shipping_address']) || empty($data['payment_method'])) {
            return ['error' => true, 'message' => 'Please provide shipping address and payment method.'];
        }
        //  Add more validation rules

        return ['error' => false, 'data' => $data];
    }

    /**
     * Sends an order confirmation email to the user.
     *
     * @param int $orderId The ID of the order.
     * @return void
     */
    private function sendOrderConfirmationEmail(int $orderId)
    {
        //  Adapt this method to your framework's email sending functionality.
        //  This is a simplified example using Laravel's Mail facade.
        $order = $this->orderModel->getOrderById($orderId);
        $user = auth()->user(); // Get user
        $orderItems = $this->orderItemModel->getOrderItemsByOrderId($orderId);

        $emailData = [
            'user' => $user,
            'order' => $order,
            'orderItems' => $orderItems,
            // Include any other data needed for the email template
        ];

        Mail::to($user->email)->send(new OrderConfirmationEmail($emailData)); //adapt
    }

     /**
     * Handles errors during the order process.
     *
     * @param string $message The error message.
     * @return void
     */
    private function handleError(string $message)
    {
        //  Adapt this method to your framework's error logging and handling.
        //  This is a basic example.
        error_log($message); // Log the error
        //  You might also want to display a user-friendly error page or redirect.
        $this->view->errorMessage = $message; //set error message.
        $this->view->render('error/order_error'); //render error view
    }

    private function getUserData($userId) {
        //Get user data
    }

    private function getShippingOptions() {
        // get shipping
    }
}

