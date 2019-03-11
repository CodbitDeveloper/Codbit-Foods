<?php

namespace App\Http\Controllers;

use DB;
use Config;
use App\User;
use App\Branch;
use App\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurant = Restaurant::all();

        return view('admin.restaurants', compact('restaurant'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $restaurant = new Restaurant;

        return view('admin.restaurant_create', compact('restaurant'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = true;
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'contact_number' => 'required',
            'logo' => 'required'
        ]);

        $restaurant = new Restaurant;

        $restaurant->name = $request->name;
        $restaurant->email = $request->email;
        $restaurant->website = $request->website;
        $restaurant->contact_number = $request->contact_number;
        if($request->hasFile('image')){
            $fileName = Utils::saveImage($request, 'image', 'image/logo');
            $restaurant->logo = $fileName;
        }else{
            $restaurant->logo = $request->logo;
        }
        $restaurant->domain = $request->domain;

        if ($restaurant->save()){
            $result = false;
        }
        
        return response()->json([
            'error' => $result,
            'data' => $restaurant,
            'message' => !$result ? 'Restaurant created successfully.' : 'Error creating restaurant'
        ]);
    }

    public function create_db(Request $request){
        $request->validate([
            'id' => 'required',
            'DB_name' => 'required',
            'DB_username' => 'required',
            'DB_password' => 'required'
        ]);


        $restaurant = new Restaurant();

        $restaurant = Restaurant::where('id', $request->id)->first();
        $restaurant->DB_name = $request->DB_name;
        $restaurant->DB_username = $request->DB_username;
        $restaurant->DB_password = $request->DB_password;

        $connectionName = config('database.default');
        config(["database.connections.{$connectionName}.database" => null]);

        if(DB::statement("CREATE DATABASE IF NOT EXISTS " . $request->DB_name)){
            config(["database.connections.{$connectionName}.database" => $request->DB_name]);
            DB::purge();


            DB::unprepared("
            CREATE TABLE `branches` (
                `id` int(10) UNSIGNED NOT NULL,
                `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `phone_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `active` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
            );
            CREATE TABLE `categories` (
                `id` int(10) UNSIGNED NOT NULL,
                `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
            );
            CREATE TABLE `checkouts` (
                `id` int(10) UNSIGNED NOT NULL,
                `checkoutUrl` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `checkoutId` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `clientReference` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `order_id` int(10) UNSIGNED NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
            );
            CREATE TABLE `comments` (
                `id` int(10) UNSIGNED NOT NULL,
                `customer_id` int(10) UNSIGNED NOT NULL,
                `item_id` int(10) UNSIGNED NOT NULL,
                `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
                `ratings` int(11) NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
            );
            CREATE TABLE `customers` (
                `id` int(10) UNSIGNED NOT NULL,
                `firstname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `lastname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
            );
            CREATE TABLE `customer_promo` (
                `promo_id` int(10) UNSIGNED NOT NULL,
                `customer_id` int(10) UNSIGNED NOT NULL,
                `is_used` tinyint(1) NOT NULL DEFAULT '0',
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL
            );
            CREATE TABLE `deals` (
                `id` int(10) UNSIGNED NOT NULL,
                `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `description` text COLLATE utf8mb4_unicode_ci,
                `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `starts_at` timestamp NULL DEFAULT NULL,
                `expires_at` timestamp NULL DEFAULT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
            );
            CREATE TABLE `deliveries` (
                `order_id` int(10) UNSIGNED NOT NULL,
                `dispatch_id` int(10) UNSIGNED NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
            );
            CREATE TABLE `dispatches` (
                `id` int(10) UNSIGNED NOT NULL,
                `firstname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `lastname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `active` tinyint(1) NOT NULL DEFAULT '1',
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
            );
            CREATE TABLE `feedback` (
                `id` int(10) UNSIGNED NOT NULL,
                `suggestion` text COLLATE utf8mb4_unicode_ci NOT NULL,
                `customer_id` int(10) UNSIGNED NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `ratings` int(11) NOT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
            );
            CREATE TABLE `images` (
                `id` int(10) UNSIGNED NOT NULL,
                `filename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `item_id` int(10) UNSIGNED NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
            );
            CREATE TABLE `ingredients` (
                `id` int(10) UNSIGNED NOT NULL,
                `item_id` int(10) UNSIGNED NOT NULL,
                `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
            );
            CREATE TABLE `items` (
                `id` int(10) UNSIGNED NOT NULL,
                `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
                `price` double NOT NULL,
                `category_id` int(10) UNSIGNED NOT NULL,
                `active` tinyint(1) NOT NULL DEFAULT '1',
                `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
            );
            CREATE TABLE `item_order` (
                `item_id` int(10) UNSIGNED NOT NULL,
                `order_id` int(10) UNSIGNED NOT NULL,
                `quantity` int(10) UNSIGNED NOT NULL DEFAULT '1',
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
            );
            CREATE TABLE `item_promo` (
                `item_id` int(10) UNSIGNED NOT NULL,
                `promo_id` int(10) UNSIGNED NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL
            );
            CREATE TABLE `orders` (
                `id` int(10) UNSIGNED NOT NULL,
                `customer_id` int(10) UNSIGNED NOT NULL,
                `total_price` double NOT NULL,
                `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
                `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `to_be_delivered` tinyint(1) NOT NULL DEFAULT '0',
                `has_paid` tinyint(1) NOT NULL DEFAULT '0',
                `payment_type_id` int(10) UNSIGNED NOT NULL,
                `extra_note` text COLLATE utf8mb4_unicode_ci,
                `is_delivered` tinyint(1) NOT NULL DEFAULT '0',
                `branch_id` int(10) UNSIGNED NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
            );
            CREATE TABLE `payment_types` (
                `id` int(10) UNSIGNED NOT NULL,
                `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `active` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
            );
            INSERT INTO `payment_types` (`id`, `name`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
            (4, 'Mobile Money', '1', NULL, NULL, NULL),
            (5, 'Card', '1', NULL, NULL, NULL),
            (6, 'Cash', '1', NULL, NULL, NULL); 
            CREATE TABLE `phone_numbers` (
                `id` int(10) UNSIGNED NOT NULL,
                `phone_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
            );
            CREATE TABLE `promos` (
                `id` int(10) UNSIGNED NOT NULL,
                `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `description` text COLLATE utf8mb4_unicode_ci,
                `max_uses` int(10) UNSIGNED DEFAULT NULL,
                `max_uses_customer` int(10) UNSIGNED DEFAULT NULL,
                `promo_amount` int(11) NOT NULL,
                `is_active` tinyint(1) NOT NULL DEFAULT '1',
                `is_fixed` tinyint(1) NOT NULL DEFAULT '1',
                `create_date` timestamp NULL DEFAULT NULL,
                `starts_at` timestamp NULL DEFAULT NULL,
                `expires_at` timestamp NULL DEFAULT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
            );
            CREATE TABLE `settings` (
                `id` int(10) UNSIGNED NOT NULL,
                `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `logo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `website` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `contact_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `currency` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'GH₵',
                `restaurant_id` int(10) UNSIGNED NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
            );
            INSERT INTO `settings` (`id`, `restaurant_id`, `name`, `email`, `logo`, `website`, `contact_number`, `created_at`, `updated_at`, `deleted_at`) VALUES
            ('1', '$restaurant->id', '$restaurant->name', '$restaurant->email', '$restaurant->logo', '$restaurant->website', '$restaurant->contact_number', '$restaurant->created_at', NULL, NULL);
            CREATE TABLE `users` (
                `id` int(10) UNSIGNED NOT NULL,
                `firstname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `lastname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `gender` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                `active` tinyint(1) NOT NULL DEFAULT '1',
                `branch_id` int(10) UNSIGNED NOT NULL,
                `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
            );
            ALTER TABLE `branches`
            ADD PRIMARY KEY (`id`);

            ALTER TABLE `categories`
            ADD PRIMARY KEY (`id`),
            ADD UNIQUE KEY `categories_name_unique` (`name`);

            ALTER TABLE `checkouts`
            ADD PRIMARY KEY (`id`),
            ADD KEY `checkouts_order_id_foreign` (`order_id`);

            ALTER TABLE `comments`
            ADD PRIMARY KEY (`id`),
            ADD KEY `comments_customer_id_foreign` (`customer_id`),
            ADD KEY `comments_item_id_foreign` (`item_id`);

            ALTER TABLE `customers`
            ADD PRIMARY KEY (`id`),
            ADD UNIQUE KEY `customers_email_unique` (`email`);
            
            ALTER TABLE `customer_promo`
            ADD PRIMARY KEY (`promo_id`,`customer_id`),
            ADD KEY `promo_customer_customer_id_foreign` (`customer_id`);

            ALTER TABLE `deals`
            ADD PRIMARY KEY (`id`);

            ALTER TABLE `deliveries`
            ADD PRIMARY KEY (`order_id`,`dispatch_id`),
            ADD KEY `deliveries_dispatch_id_foreign` (`dispatch_id`);

            ALTER TABLE `dispatches`
            ADD PRIMARY KEY (`id`);

            ALTER TABLE `feedback`
            ADD PRIMARY KEY (`id`),
            ADD KEY `feedback_customer_id_foreign` (`customer_id`);

            ALTER TABLE `images`
            ADD PRIMARY KEY (`id`),
            ADD KEY `images_item_id_foreign` (`item_id`);

            ALTER TABLE `ingredients`
            ADD PRIMARY KEY (`id`),
            ADD KEY `ingredients_item_id_foreign` (`item_id`);

            ALTER TABLE `items`
            ADD PRIMARY KEY (`id`),
            ADD UNIQUE KEY `items_name_unique` (`name`),
            ADD KEY `items_category_id_foreign` (`category_id`);

            ALTER TABLE `item_order`
            ADD PRIMARY KEY (`item_id`,`order_id`),
            ADD KEY `item_order_order_id_foreign` (`order_id`);

            ALTER TABLE `item_promo`
            ADD PRIMARY KEY (`item_id`,`promo_id`),
            ADD KEY `item_promo_promo_id_foreign` (`promo_id`);

            ALTER TABLE `orders`
            ADD PRIMARY KEY (`id`),
            ADD KEY `orders_customer_id_foreign` (`customer_id`),
            ADD KEY `orders_payment_type_id_foreign` (`payment_type_id`),
            ADD KEY `orders_branch_id_foreign` (`branch_id`);

            ALTER TABLE `payment_types`
            ADD PRIMARY KEY (`id`);

            ALTER TABLE `phone_numbers`
            ADD PRIMARY KEY (`id`);

            ALTER TABLE `promos`
            ADD PRIMARY KEY (`id`);

            ALTER TABLE `settings`
            ADD PRIMARY KEY (`id`),
            ADD UNIQUE KEY `settings_name_unique` (`name`),
            ADD UNIQUE KEY `settings_email_unique` (`email`);

            ALTER TABLE `users`
            ADD PRIMARY KEY (`id`),
            ADD UNIQUE KEY `users_username_unique` (`username`),
            ADD KEY `users_branch_id_foreign` (`branch_id`);

            ALTER TABLE `branches`
            MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

            ALTER TABLE `categories`
            MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

            ALTER TABLE `checkouts`
            MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

            ALTER TABLE `comments`
            MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

            ALTER TABLE `customers`
            MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

            ALTER TABLE `deals`
            MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

            ALTER TABLE `dispatches`
            MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

            ALTER TABLE `feedback`
            MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

            ALTER TABLE `images`
            MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

            ALTER TABLE `ingredients`
            MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

            ALTER TABLE `items`
            MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

            ALTER TABLE `orders`
            MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

            ALTER TABLE `payment_types`
            MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

            ALTER TABLE `phone_numbers`
            MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

            ALTER TABLE `promos`
            MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

            ALTER TABLE `settings`
            MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

            ALTER TABLE `users`
            MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

            ALTER TABLE `checkouts`
            ADD CONSTRAINT `checkouts_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

            ALTER TABLE `comments`
            ADD CONSTRAINT `comments_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `comments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
            
            ALTER TABLE `customer_promo`
            ADD CONSTRAINT `promo_customer_promo_id_foreign` FOREIGN KEY (`promo_id`) REFERENCES `promos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `promo_customer_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

            ALTER TABLE `deliveries`
            ADD CONSTRAINT `deliveries_dispatch_id_foreign` FOREIGN KEY (`dispatch_id`) REFERENCES `dispatches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `deliveries_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

            ALTER TABLE `feedback`
            ADD CONSTRAINT `feedback_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

            ALTER TABLE `images`
            ADD CONSTRAINT `images_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

            ALTER TABLE `ingredients`
            ADD CONSTRAINT `ingredients_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

            ALTER TABLE `items`
            ADD CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

            ALTER TABLE `item_order`
            ADD CONSTRAINT `item_order_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `item_order_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

            ALTER TABLE `item_promo`
            ADD CONSTRAINT `item_promo_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `item_promo_promo_id_foreign` FOREIGN KEY (`promo_id`) REFERENCES `promos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

            ALTER TABLE `orders`
            ADD CONSTRAINT `orders_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `orders_payment_type_id_foreign` FOREIGN KEY (`payment_type_id`) REFERENCES `payment_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

            ALTER TABLE `users`
            ADD CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
            COMMIT;
            ");
            
            //$branch = Branch::create(request(['name', 'location']));
            config(["database.connections.{$connectionName}.database" => 'codbitfood']);
            DB::purge();

            if($restaurant->save()){
                config(["database.connections.mysql2.database" => $request->DB_name]);
                DB::purge();

                $branch = new Branch();

                $branch->name = $request->branch_name;
                $branch->location = $request->branch_location;
                $branch->phone_number = $request->branch_phone_number;
    
                if($branch->save()){
                    $user = new User();
        
                    $user->firstname = $request->firstname;
                    $user->lastname  = $request->lastname;
                    $user->username  = $request->username.'@'.$restaurant->domain;
                    $user->phone     = $request->phone;
                    $user->gender    = $request->gender;
                    $user->branch_id = $branch->id;
                    $user->password  = bcrypt('Password');
                    $user->role      = 'Admin';
        
                    if($user->save()){
                        $result = false;
                    }

                    //Artisan::call('passport:install');
                    config(["database.default" => 'mysql2']);
                    config(["database.connections.mysql2.database" => $request->DB_name]);
                    DB::purge();
                    
                    shell_exec('php ../artisan passport:install');
                    
                    return response()->json([
                        'message' => 'database created and restaurant updated'
                    ]);
                }else{
                    return response()->json([
                        'message' => 'database created but initiating failed'
                    ]);
                }
            }else{
                return response()->json([
                    'message' => 'database created but could save restaurant'
                ]);
            }
        }else{
            return response()->json([
                'message' => 'could not create database'
            ]);
        }
    }

    public function is_active(Request $request)
    {
        $restaurant = Restaurant::where('id', $request->id)->first();

        $isactive = $request->active;
        $restaurant->active = $isactive;

        if($restaurant->save()){
            return response()->json([
                'data' => $restaurant,
                'message' => 'Restaurant updated'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error updating restaurant'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function show(Restaurant $restaurant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function edit(Restaurant $restaurant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurant $restaurant)
    {
        //
    }
}
