<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->date('submission_date');
            $table->unsignedBigInteger('account_id'); // Foreign key to the Accounts table
            $table->string('deal_name')->unique();
            $table->unsignedBigInteger('iso_id'); // Foreign key to the ISOs table
            $table->enum('sales_stage', ['new deal', 'missing info', 'deal won', 'deal lost']);
            $table->timestamps();
        });
    
        Schema::table('deals', function (Blueprint $table) {
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('iso_id')->references('id')->on('isos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deals');
    }
}
