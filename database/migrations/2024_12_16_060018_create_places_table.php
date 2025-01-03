<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('places', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->foreignId('city_id')->constrained('cities');
        $table->text('description');
        $table->boolean('is_active')->default(true)->after('description');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
