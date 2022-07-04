<?php

namespace Database\Seeders;

use App\Models\BankData;
use App\Models\Bonus;
use App\Models\Company;
use App\Models\CompositProduct;
use App\Models\CompositProductDetails;
use App\Models\Contact;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Department;
use App\Models\DesignOrder;
use App\Models\DesignResult;
use App\Models\DesignType;
use App\Models\Employee;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\ProductFamily;
use App\Models\ProductMaterial;
use App\Models\ProductStatus;
use App\Models\Quote;
use App\Models\QuoteProduct;
use App\Models\SatMethod;
use App\Models\SatType;
use App\Models\SatWay;
use App\Models\SellOrder;
use App\Models\SellOrderedProduct;
use App\Models\StockActionType;
use App\Models\StockMovement;
use App\Models\StockProduct;
use App\Models\Supplier;
use App\Models\User;
use App\Models\UserHasSellOrderedProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Storage::makeDirectory('public/products');
        // Storage::makeDirectory('public/stock-products');
        // Storage::makeDirectory('public/composit-products');
        // Storage::makeDirectory('public/design-results');
        // Storage::makeDirectory('public/design-plans');
        // Storage::makeDirectory('public/design-logos');

        $this->call([
            PermissionsSeeder::class,
            RolesSeeder::class,
            RoleHasPermissionSeeder::class,
            JustificationsEventSeeder::class,
            UserSeeder::class,
            BonusSeeder::class,
            DepartmentSeeder::class,
            MeasurementSeeder::class,
            ProductFamilySeeder::class,
            ProductMaterialSeeder::class,
            SatMethodSeeder::class,
            SatTypeSeeder::class,
            SatWaySeeder::class,
            StockActionTypeSeeder::class,
            DesignTypeSeeder::class,
            ProductStatusSeeder::class,
            CurrencySeeder::class,
        ]);

        // ProductStatus::factory(3)->create();
        // Product::factory(20)->create();
        // StockProduct::factory(10)->create();
        // StockMovement::factory(200)->create();
        // Currency::factory(2)->create();
        // Company::factory(10)->create();
        // Contact::factory(38)->create();
        // Customer::factory(10)->create();
        // Supplier::factory(9)->create();
        // BankData::factory(9)->create();
        // Quote::factory(10)->create();
        // QuoteProduct::factory(25)->create();
        // CompositProduct::factory(22)->create();
        // CompositProductDetails::factory(45)->create();
        // SellOrder::factory(10)->create();
        // SellOrderedProduct::factory(25)->create();
        // UserHasSellOrderedProduct::factory(35)->create();
        // DesignType::factory(5)->create();
        // DesignOrder::factory(10)->create();
        // DesignResult::factory(16)->create();
        // Department::factory(10)->create();
        // Employee::factory(9)->create();
    }

}
