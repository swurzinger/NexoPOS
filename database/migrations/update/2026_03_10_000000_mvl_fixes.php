<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        if ( Schema::hasTable( 'nexopos_products_unit_quantities' ) ) {
            if ( ! Schema::hasColumn( 'nexopos_products_unit_quantities', 'cogs' ) ) {
                Schema::table( 'nexopos_products_unit_quantities', function ( Blueprint $table ) {
                    $table->float( 'cogs' )->default( 0 )->after( 'custom_price_tax' );
                    $table->integer( 'convert_unit_id' )->nullable()->after( 'custom_price_tax' );
                    $table->boolean( 'visible' )->default( true )->after( 'custom_price_tax' );
                } );
            }
        }

        if ( Schema::hasTable( 'nexopos_products' ) ) {
            if ( ! Schema::hasColumn( 'nexopos_products', 'auto_cogs' ) ) {
                Schema::table( 'nexopos_products', function ( Blueprint $table ) {
                    $table->boolean( 'auto_cogs' )->default( true )->after( 'accurate_tracking' );
                } );
            }
        }

        if ( Schema::hasTable( 'nexopos_orders' ) ) {
            if ( ! Schema::hasColumn( 'nexopos_orders', 'total_cogs' ) ) {
                Schema::table( 'nexopos_orders', function ( Blueprint $table ) {
                    $table->float( 'total_cogs', 18, 5 )->default( 0 )->after( 'total_coupons' );
                } );
            }
            if ( Schema::hasColumn( 'nexopos_orders', 'total_tax_value' ) ) {
                Schema::table( 'nexopos_orders', function ( Blueprint $table ) {
                    $table->dropColumn( 'total_tax_value' );
                } );
            }
        }

        if ( Schema::hasTable( 'nexopos_expenses' ) ) {
            Schema::dropIfExists( 'nexopos_expenses' );
            Schema::dropIfExists( 'nexopos_expenses_categories' );

            DB::table( 'migrations' )->whereIn( 'migration', [
                '2020_06_20_000000_create_expenses_table',
                '2022_10_28_093041_update_expense_table28_oct22',
                '2020_06_20_000000_create_expenses_categories_table',
                '2020_10_29_150642_create_nexopos_expenses_history_table',
            ] )->delete();
        }

        $p = \App\Models\Permission::where( [ 'namespace' => 'nexopos.create.expenses-categories' ] )->first();
        if ( $p ) {
            $p->namespace = 'nexopos.create.transactions-account';
            $p->save();
        }

        $p = \App\Models\Permission::where( [ 'namespace' => 'nexopos.delete.expenses-categories' ] )->first();
        if ( $p ) {
            $p->namespace = 'nexopos.delete.transactions-account';
            $p->save();
        }

        $p = \App\Models\Permission::where( [ 'namespace' => 'nexopos.update.expenses-categories' ] )->first();
        if ( $p ) {
            $p->namespace = 'nexopos.update.transactions-account';
            $p->save();
        }

        $p = \App\Models\Permission::where( [ 'namespace' => 'nexopos.read.expenses-categories' ] )->first();
        if ( $p ) {
            $p->namespace = 'nexopos.read.transactions-account';
            $p->save();
        }

        $p = \App\Models\Permission::where( [ 'namespace' => 'nexopos.create.expenses' ] )->first();
        if ( $p ) {
            $p->namespace = 'nexopos.create.transactions';
            $p->save();
        }

        $p = \App\Models\Permission::where( [ 'namespace' => 'nexopos.delete.expenses' ] )->first();
        if ( $p ) {
            $p->namespace = 'nexopos.delete.transactions';
            $p->save();
        }

        $p = \App\Models\Permission::where( [ 'namespace' => 'nexopos.update.expenses' ] )->first();
        if ( $p ) {
            $p->namespace = 'nexopos.update.transactions';
            $p->save();
        }

        $p = \App\Models\Permission::where( [ 'namespace' => 'nexopos.read.expenses' ] )->first();
        if ( $p ) {
            $p->namespace = 'nexopos.read.transactions';
            $p->save();
        }

        $p = \App\Models\Permission::where( [ 'namespace' => 'nexopos.create.cash-flow-history' ] )->first();
        if ( $p ) {
            $p->namespace = 'nexopos.create.transactions-history';
            $p->save();
        }

        $p = \App\Models\Permission::where( [ 'namespace' => 'nexopos.delete.cash-flow-history' ] )->first();
        if ( $p ) {
            $p->namespace = 'nexopos.delete.transactions-history';
            $p->save();
        }

        $p = \App\Models\Permission::where( [ 'namespace' => 'nexopos.update.cash-flow-history' ] )->first();
        if ( $p ) {
            $p->namespace = 'nexopos.update.transactions-history';
            $p->save();
        }

        $p = \App\Models\Permission::where( [ 'namespace' => 'nexopos.read.cash-flow-history' ] )->first();
        if ( $p ) {
            $p->namespace = 'nexopos.read.transactions-history';
            $p->save();
        }

        $p = \App\Models\Permission::where( [ 'namespace' => 'nexopos.reports.cash_flow' ] )->first();
        if ( $p ) {
            $p->namespace = 'nexopos.reports.transactions';
            $p->save();
        }

        $transactionService = App::make( \App\Services\TransactionService::class );
        $transactionService->createDefaultAccounts();

        if ( ! defined( 'NEXO_CREATE_PERMISSIONS' ) ) {
            define( 'NEXO_CREATE_PERMISSIONS', true );
        }

        include_once dirname( __FILE__ ) . '/../../permissions/medias.php';
        include_once dirname( __FILE__ ) . '/../../permissions/categories.php';
        include_once dirname( __FILE__ ) . '/../../permissions/customers.php';
        include_once dirname( __FILE__ ) . '/../../permissions/customers-groups.php';
        include_once dirname( __FILE__ ) . '/../../permissions/coupons.php';
        include_once dirname( __FILE__ ) . '/../../permissions/transactions-accounts.php';
        include_once dirname( __FILE__ ) . '/../../permissions/transactions.php';
        include_once dirname( __FILE__ ) . '/../../permissions/orders.php';
        include_once dirname( __FILE__ ) . '/../../permissions/procurements.php';
        include_once dirname( __FILE__ ) . '/../../permissions/providers.php';
        include_once dirname( __FILE__ ) . '/../../permissions/products.php';
        include_once dirname( __FILE__ ) . '/../../permissions/registers.php';
        include_once dirname( __FILE__ ) . '/../../permissions/rewards.php';
        include_once dirname( __FILE__ ) . '/../../permissions/taxes.php';
        include_once dirname( __FILE__ ) . '/../../permissions/reports.php';
        include_once dirname( __FILE__ ) . '/../../permissions/payments-types.php';
        include_once dirname( __FILE__ ) . '/../../permissions/pos.php';
        include_once dirname( __FILE__ ) . '/../../permissions/widgets.php';

        $admin = Role::firstOrNew( [ 'namespace' => 'admin' ] );
        $admin->addPermissions( Permission::get()->map( fn( $permission ) => $permission->namespace ) );

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        throw new Exception( 'not implemented' );
    }
};
