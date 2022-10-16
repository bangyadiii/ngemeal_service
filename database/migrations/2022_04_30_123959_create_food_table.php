<?php

use App\Models\Store;
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
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Store::class);
            $table->string("name")->nullable();
            $table->longText("description")->nullable();
            $table->string("ingredients", 300)->nullable();
            $table->unsignedBigInteger("price")->nullable();
            $table->double("rate")->nullable();
            $table->string("types")->nullable();

            $table->timestamp("archived");
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
        Schema::dropIfExists('food');
    }
};
