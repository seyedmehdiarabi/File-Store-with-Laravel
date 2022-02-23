<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersAdditionalInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users__additional_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('national_identity_number')->nullable();
            $table->string('mobile_phone');
            $table->string('email')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_economic_number')->nullable();
            $table->string('company_registration_number')->nullable();
            $table->string('company_national_identity_number')->nullable();
            $table->string('company_phone')->nullable();
            $table->integer('province_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->string('bank_card_number')->nullable();
            $table->string('date_of_birth',100)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('users__additional_info');
    }
}
