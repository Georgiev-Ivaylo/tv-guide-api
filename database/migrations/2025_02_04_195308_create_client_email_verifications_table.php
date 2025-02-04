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
        Schema::create('client_email_verifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');
            $table->string('email');
            $table->string('token');
            $table->boolean('is_used')->default(false);
            $table->dateTime('expires_at');

            $table->timestamps();


            $table->index(['client_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_email_verifications');
    }
};
