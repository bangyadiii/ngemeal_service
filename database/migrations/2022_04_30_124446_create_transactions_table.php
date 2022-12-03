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
        Schema::create('transactions', function (Blueprint $table) {
            $table->ulid('id')->primary();

            //foreign key
            $table->foreignUlid("user_id")->constrained("users")->onDelete("cascade");
            $table->foreignId("food_id")->constrained("foods")->onDelete("cascade");

            //attributes
            $table->integer("total")->unsigned();
            $table->integer("quantity")->unsigned();
            $table->enum("trx_status", ["failed", "pending", "success"])->default("pending");
            $table->enum("delivery_status", ["cancelled", "waiting_driver", "on_delivery", "finish", "failed"])
                ->default("waiting_driver");
            $table->string("payment_url")->nullable(); //midtrans snap url

            // metadata adalah informasi detail kecil tentang product yang dibeli, contoh nama product, catatan, dll.
            $table->json("metadata")->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
