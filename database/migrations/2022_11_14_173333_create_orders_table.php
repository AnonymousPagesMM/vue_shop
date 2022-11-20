<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id',20)->nullable()->unique();
            $table->string('user_id');
            $table->integer('member_id')->nullable();
            $table->string('phone_1');
            $table->string('phone_2');
            $table->string('city');
            $table->text('address');
            $table->text('voucher',10)->nullable();
            $table->text('payment',40);
            $table->integer('delivery_charges');
            $table->string('deliver_date')->nullable();
            $table->string('status',15)->default('pending');
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
