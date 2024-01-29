<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->unsignedBigInteger('industry_id'); // Foreign key to the SICs table
            $table->text('owners'); // We'll store owners as a JSON string
            $table->timestamps();
        });
    
        Schema::table('accounts', function (Blueprint $table) {
            $table->foreign('industry_id')->references('id')->on('sics');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
