<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StockTargets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_targets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20)->nullable()->default(null)->comment('自訂名稱，若未填則使用股市網站名稱');
            $table->string('code', 10)->comment('股票代碼');
            $table->string('description', 200)->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_targets');
    }
}
