<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FaqsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('faqs')->delete();
        
        \DB::table('faqs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => 'How do I create an account?',
                'body' => '<p>Creating an account is easy and free!</p><p>Just download the <strong>Molina Market</strong> app from your app store, open it, and tap on <strong>"Sign Up"/"Become A Partner"</strong> button on the login page/screen.</p><p><br></p><p>Fill in the required information, including your email address and a secure password, then follow the instructions to verify your email.</p><p><br></p><p>Once verified, you can start using all the features Molina Market has to offer.</p>',
                'type' => NULL,
                'in_order' => 1,
                'is_active' => 1,
                'created_at' => '2024-04-13 19:33:11',
                'updated_at' => '2024-04-13 19:33:11',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => 'How do I place an order? ',
                'body' => '<p>Ordering first is really easy. Just follow these steps:</p><p><br></p><ol><li>On main screen set your delivery address.</li><li>Select the module of your choice. Food, E-commerce etc</li><li>Select the product of your choice and add it to cart</li><li>If you have a promo code, don\'t forget to validate it in your profile before ordering.</li><li>On the checkout page you can select your preferred payment method</li><li>After order placement you will receive notification to confirm your order placement.</li></ol><p><br></p><p>Follow the steps required for each option and once you’re finished, a courier will accept your order and deliver it in minutes.</p>',
                'type' => 'customer',
                'in_order' => 1,
                'is_active' => 1,
                'created_at' => '2024-04-13 19:33:37',
                'updated_at' => '2024-04-13 19:33:37',
            ),
            2 => 
            array (
                'id' => 3,
                'title' => 'What can I order?',
                'body' => '<p>You can buy and receive products as well as send deliveries or express messages within your city.</p><p>Want us to go to the pharmacy for you?</p><p>Need to buy a pair of shoes?</p><p>Want something to eat?</p><p>Need to ship a package?</p><p><br></p><p>We mean it: Anything you want, delivered in minutes.</p>',
                'type' => NULL,
                'in_order' => 1,
                'is_active' => 1,
                'created_at' => '2024-04-13 19:33:57',
                'updated_at' => '2024-04-13 19:33:57',
            ),
            3 => 
            array (
                'id' => 4,
                'title' => 'How do I find what I want?',
                'body' => '<p>At the bottom of your screen, there\'s a search bar that lets you find any product or establishment in your city.</p>',
                'type' => 'customer',
                'in_order' => 1,
                'is_active' => 1,
                'created_at' => '2024-04-13 19:34:18',
                'updated_at' => '2024-04-13 19:34:18',
            ),
            4 => 
            array (
                'id' => 5,
                'title' => 'How do I withdraw my earnings?',
                'body' => '<p>To withdraw your earnings, simply navigate to the "<strong>Earnings</strong>" section of the app. </p><p>From there, you can view your available balance and choose a withdrawal method, such as bank transfer or payout account info. </p><p>Follow the on-screen instructions to complete the withdrawal process. </p><p><br></p><p>Please note that there may be minimum withdrawal thresholds and processing times associated with each withdrawal method.</p>',
                'type' => 'driver',
                'in_order' => 1,
                'is_active' => 1,
                'created_at' => '2024-04-13 19:37:32',
                'updated_at' => '2024-04-13 19:37:32',
            ),
            5 => 
            array (
                'id' => 6,
                'title' => 'Are there any fees associated with withdrawing my earnings?',
                'body' => '<p>The driver app does not charge any fees for withdrawing your earnings. However, please be aware that third-party payment processors, such as banks, may impose their own fees. </p><p>Make sure to review their terms and conditions for any applicable charges.</p>',
                'type' => 'driver',
                'in_order' => 1,
                'is_active' => 1,
                'created_at' => '2024-04-13 19:38:05',
                'updated_at' => '2024-04-13 19:38:05',
            ),
            6 => 
            array (
                'id' => 7,
                'title' => 'How do I deliver an order to the customer using the driver app?',
                'body' => '<p>When you receive a new order, you will be provided with the customer\'s delivery address and any special instructions. </p><ol><li>Navigate to the "Orders" section of the app to view the details of the order. </li><li>Once you\'ve picked up the items, use the navigation button beside the address to open navigation on any map app of your choice to reach the customer\'s location.</li><li>Upon arrival, confirm the delivery within the app to complete the process.</li></ol>',
                'type' => NULL,
                'in_order' => 1,
                'is_active' => 1,
                'created_at' => '2024-04-13 19:39:32',
                'updated_at' => '2024-04-13 19:39:32',
            ),
            7 => 
            array (
                'id' => 8,
                'title' => 'What should I do if I encounter any issues during the delivery process?',
                'body' => '<p>If you encounter any issues, such as difficulty locating the customer or items missing from the order, please use the in-app messaging or call the customer directly to resolve the issue.</p><p>If you\'re unable to complete the delivery for any reason, contact support for assistance.</p>',
                'type' => 'driver',
                'in_order' => 1,
                'is_active' => 1,
                'created_at' => '2024-04-13 19:39:57',
                'updated_at' => '2024-04-13 19:39:57',
            ),
            8 => 
            array (
                'id' => 9,
                'title' => 'How do I go online to start receiving orders?',
            'body' => '<p>To go online and start receiving orders, simply toggle the <strong style="color: rgb(0, 138, 0);">"Online"</strong> switch within the driver app. </p><p>Once you\'re online, you\'ll begin receiving order requests based on your availability and location. </p><p><br></p><p>Make sure to have your vehicle ready and be prepared to accept orders.</p>',
                'type' => 'driver',
                'in_order' => 1,
                'is_active' => 1,
                'created_at' => '2024-04-13 19:40:44',
                'updated_at' => '2024-04-13 19:40:44',
            ),
            9 => 
            array (
                'id' => 10,
                'title' => 'How do I go offline to stop receiving orders?',
            'body' => '<p>If you need to take a break or stop receiving orders temporarily, toggle the "<strong style="color: rgb(230, 0, 0);">Offline</strong>" switch within the driver app. This will prevent you from receiving new order requests until you go back online. </p><p>You can go offline at any time, but please ensure that you\'re not currently fulfilling an active order when doing so.</p>',
                'type' => 'driver',
                'in_order' => 1,
                'is_active' => 1,
                'created_at' => '2024-04-13 19:41:20',
                'updated_at' => '2024-04-13 19:41:20',
            ),
            10 => 
            array (
                'id' => 11,
                'title' => 'How can I view and manage my earnings through the merchant app?',
                'body' => '<p>Within the merchant app, navigate to the "Earnings" section where you can access detailed information about your earnings. </p><p>This includes your total earnings, breakdowns by day, week, or month, as well as any pending or completed payouts. </p><p>You can also manage your preferred payout methods and initiate withdrawal requests directly from the app.</p>',
                'type' => 'vendor',
                'in_order' => 1,
                'is_active' => 1,
                'created_at' => '2024-04-13 19:45:26',
                'updated_at' => '2024-04-13 19:45:26',
            ),
            11 => 
            array (
                'id' => 12,
                'title' => 'Are there any fees associated with withdrawing earnings from the merchant app?',
            'body' => '<p>The merchant app does not impose any fees for withdrawing earnings. However, depending on your chosen payout method (e.g., bank transfer), there may be third-party processing fees. Make sure to review the terms and conditions of your selected payout method for any applicable charges.</p>',
                'type' => 'vendor',
                'in_order' => 1,
                'is_active' => 1,
                'created_at' => '2024-04-13 19:45:50',
                'updated_at' => '2024-04-13 19:45:50',
            ),
            12 => 
            array (
                'id' => 13,
                'title' => 'How do I add or update products within the merchant app?',
                'body' => '<p>Adding or updating products is simple with the merchant app. </p><p>Navigate to the "Products" section where you can create new listings, update existing ones, adjust prices, and upload images. </p><p>You can also categorize products, set stock quantities, and provide detailed descriptions to attract customers.</p>',
                'type' => 'vendor',
                'in_order' => 1,
                'is_active' => 1,
                'created_at' => '2024-04-13 19:46:17',
                'updated_at' => '2024-04-13 19:46:17',
            ),
            13 => 
            array (
                'id' => 14,
                'title' => 'How do I open or close my vendor status to start or stop receiving orders?',
                'body' => '<p>To manage your vendor status, navigate to the "<strong>Vendor</strong>" tab within the app. Here, you can toggle between "<strong>Open</strong>" and "<strong>Closed</strong>" to control whether your store is available to receive orders. </p><p>When you\'re ready to accept orders, set your status to "Open", and vice versa when you need to temporarily pause order acceptance.</p>',
                'type' => 'vendor',
                'in_order' => 1,
                'is_active' => 1,
                'created_at' => '2024-04-13 19:47:42',
                'updated_at' => '2024-04-13 19:47:42',
            ),
            14 => 
            array (
                'id' => 15,
                'title' => 'How can I communicate with customers within the merchant app if I need more information during the order process?',
                'body' => '<p>The merchant app features built-in communication tools that allow you to chat or initiate phone calls with customers directly. If you require additional information or clarification regarding an order, simply access the order details and use the in-app chat or call function to reach out to the customer.</p>',
                'type' => 'vendor',
                'in_order' => 1,
                'is_active' => 1,
                'created_at' => '2024-04-13 19:48:14',
                'updated_at' => '2024-04-13 19:48:14',
            ),
        ));
        
        
    }
}