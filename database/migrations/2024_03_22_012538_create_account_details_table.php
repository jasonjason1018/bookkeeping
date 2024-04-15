<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_details', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('type', 50);
            $table->string('category_type', 50);
            $table->date('invoice_date');
            $table->string('invoice_type', 50);
            $table->text('content')->nullable();
            $table->integer('is_tax')->nullable();
            $table->string('price', 50);
            $table->string('tax', 50);
            $table->string('untax', 50);
            $table->date('actual_date')->nullable();
            $table->string('actual_amount', 50);
            $table->text('remark')->nullable();
            $table->text('img')->nullable();
            $table->string('share', 50);
            $table->date('start_share_date')->nullable();
            $table->date('end_share_date')->nullable();
            $table->text('account_type');
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
        Schema::dropIfExists('account_details');
    }
}
