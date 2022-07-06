<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cake_awaiting_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cake_id');
            $table->unsignedBigInteger('client_id');
            $table->timestamps();

            $table->foreign('cake_id')
                ->references('id')
                ->on('cakes')
                ->cascadeOnDelete();

            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cake_awaiting_lists');
    }
};
