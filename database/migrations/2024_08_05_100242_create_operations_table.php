<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->date('operation_date');
            $table->unsignedBigInteger('user_id');
            $table->string('user_type');
            $table->string('operation_type');
            $table->decimal('amount', 10, 2);
            $table->string('currency');
            $table->decimal('commission_fee', 10, 2)->default(0.00);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
