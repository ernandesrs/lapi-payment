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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignId('card_id')->nullable()->default(null)->constrained('cards', 'id')->noActionOnDelete();

            $table->string('gateway', 16);
            $table->string('method', 16)->comment('Cartão de crédito, Boleto, Pix, etc.');
            $table->string('transaction_id')->comment('ID da transação gerado pela gateway.');
            $table->float('amount');
            $table->integer('installments');
            $table->string('status')->comment('Pago, Pendente, Negado, Cancelado, etc.');

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
        Schema::dropIfExists('payments');
    }
};