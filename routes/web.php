<?php

use App\Http\Controllers\FormatController;
use App\Http\Controllers\PdfController;
use App\Http\Livewire\Bonus\Bonuses;
use App\Http\Livewire\CompositProduct\CompositProducts;
use App\Http\Livewire\Customer\Customers;
use App\Http\Livewire\Dashboard\Dashboards;
use App\Http\Livewire\DesignDepartment\HomeDesign;
use App\Http\Livewire\DesignOrder\DesignOrders;
use App\Http\Livewire\Holyday\Holydays;
use App\Http\Livewire\Meeting\Meetings;
use App\Http\Livewire\NewUpdate\NewUpdates;
use App\Http\Livewire\Organization\Organization;
use App\Http\Livewire\PayRoll\PayRolls;
use App\Http\Livewire\Permission\Permissions;
use App\Http\Livewire\ProductionDepartment\HomeProduction;
use App\Http\Livewire\Products\Products;
use App\Http\Livewire\PurchaseOrder\PurchaseOrders;
use App\Http\Livewire\Quote\Quotes;
use App\Http\Livewire\Role\Roles;
use App\Http\Livewire\SellOrder\SellOrders;
use App\Http\Livewire\StockHome\Base;
use App\Http\Livewire\Supplier\Suppliers;
use App\Http\Livewire\User\Users;
use App\Mail\ApproveMailable;
use App\Mail\PurchaseOrderMailable;
use App\Models\PurchaseOrder;
use App\Models\Quote;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cotizacion-pdf/{item}', [PdfController::class, 'quote'])
    ->name('quote-pdf');

Route::get('/nomina-pdf/{item}', [PdfController::class, 'payRoll'])
    ->name('pay-roll-pdf');

Route::get('/recibo-vacaciones/{item}', [FormatController::class, 'vacationsReceipt'])
    ->name('vacations-receipt-format');

Route::get('/certificado-calidad/{item}', [FormatController::class, 'qualityCertificate'])
    ->name('quality-certificate-format');

Route::get('/orden-venta/{item}', [FormatController::class, 'sellOrder'])
    ->name('sell-order-format');

Route::get('/orden-compra/{item}', [FormatController::class, 'purchaseOrder'])
    ->name('purchase-order-format');


// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

Route::get('/productos', Products::class)
    ->middleware('auth')
    ->name('products');

Route::get('/productos-compuestos', CompositProducts::class)
    ->middleware('auth')
    ->name('composit-products');

Route::get('/inventario', Base::class)
    ->middleware('auth')
    ->name('stocks');

Route::get('/cotizaciones', Quotes::class)
    ->middleware('auth')
    ->name('quotes');

Route::get('/clientes', Customers::class)
    ->middleware('auth')
    ->name('customers');

Route::get('/proveedores', Suppliers::class)
    ->middleware('auth')
    ->name('suppliers');

Route::get('/ordenes-venta', SellOrders::class)
    ->middleware('auth')
    ->name('sell-orders');

Route::get('/ordenes-compra', PurchaseOrders::class)
    ->middleware('auth')
    ->name('purchase-orders');

Route::get('/ordenes-diseño', DesignOrders::class)
    ->middleware('auth')
    ->name('design-orders');

Route::get('/usuarios', Users::class)
    ->middleware('auth')
    ->name('users');

Route::get('/permisos', Permissions::class)
    ->middleware('auth')
    ->name('permissions');

Route::get('/roles', Roles::class)
    ->middleware('auth')
    ->name('roles');

Route::get('/produccion', HomeProduction::class)
    ->middleware('auth')
    ->name('production-department');

Route::get('/diseño', HomeDesign::class)
    ->middleware('auth')
    ->name('design-department');

Route::get('/nominas', PayRolls::class)
    ->middleware('auth')
    ->name('pay-rolls');

Route::get('/inicio', Dashboards::class)
    ->middleware('auth')
    ->name('dashboard');

Route::get('/dias-festivos', Holydays::class)
    ->middleware('auth')
    ->name('holydays');

Route::get('/reuniones', Meetings::class)
    ->middleware('auth')
    ->name('meetings');

Route::get('/organizacion', Organization::class)
    ->middleware('auth')
    ->name('organization');

Route::get('/bonos', Bonuses::class)
    ->middleware('auth')
    ->name('bonuses');

Route::get('/novedades', NewUpdates::class)
    ->middleware('auth')
    ->name('news');
        
        // // artisan commands
     Route::get('/clear-cache', function() {
         Artisan::call('cache:clear');
         return "app cache clear!";
      });
     
      Route::get('event-cache', function() {
         Artisan::call('event:clear');
         return "events cache clear!";
      });
     
    //  Route::get('/view-cache', function() {
    //     Artisan::call('view:cache');
    //     return "precompiled all your Blade views so they are not compiled on demand!";
    //  });
     
    //  Route::get('/storage-link', function() {
    //     Artisan::call('storage:link');
    //     return "created virtual storage link successfully!";
    //  });
     
    //  Route::get('/migrate-db', function() {
    //     Artisan::call('migrate --seed');
    //     return "database migrated successfully!";
    //  });
