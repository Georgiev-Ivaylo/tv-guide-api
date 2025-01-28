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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id')->unsigned()->index();
            $table->bigInteger('program_id')->unsigned()->index();
            $table->tinyInteger('number_notifications')->nullable(true);
            $table->tinyInteger('time_lapse_notifications')->nullable(true);
            $table->tinyInteger('last_notification')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
