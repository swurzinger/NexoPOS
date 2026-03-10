<?php

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
                    $table->float( 'cogs' )->default( 0 )->after('custom_price_tax');
                    $table->integer( 'convert_unit_id' )->nullable()->after('custom_price_tax');
                    $table->boolean( 'visible' )->default( true )->after('custom_price_tax');
                } );
            }
        }
        
        if ( Schema::hasTable( 'nexopos_products' ) ) {
            if ( ! Schema::hasColumn( 'nexopos_products', 'auto_cogs' ) ) {
                Schema::table( 'nexopos_products', function ( Blueprint $table ) {
                    $table->boolean( 'auto_cogs' )->default( true )->after('accurate_tracking');
                } );
            }
        }
        
        if ( Schema::hasTable( 'nexopos_orders' ) ) {
            if ( ! Schema::hasColumn( 'nexopos_orders', 'total_cogs' ) ) {
                Schema::table( 'nexopos_orders', function ( Blueprint $table ) {
                    $table->float( 'total_cogs', 18, 5 )->default( 0 )->after('total_coupons');
                } );
            }
            if ( Schema::hasColumn( 'nexopos_orders', 'total_tax_value' ) ) {
                Schema::table( 'nexopos_orders', function ( Blueprint $table ) {
                    $table->dropColumn('total_tax_value');
                } );
            }
        }

        if ( Schema::hasTable( 'nexopos_expenses' ) ) {
            Schema::dropIfExists('nexopos_expenses');
            Schema::dropIfExists('nexopos_expenses_categories');

            DB::table('migrations')->whereIn('migration', [
                '2020_06_20_000000_create_expenses_table',
                '2022_10_28_093041_update_expense_table28_oct22',
                '2020_06_20_000000_create_expenses_categories_table',
                '2020_10_29_150642_create_nexopos_expenses_history_table'
            ])->delete();
        }
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        throw new Exception("not implemented");
    }
};
