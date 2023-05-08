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
        Schema::create('obligation_retributions', function (Blueprint $table) {
            $table->id();
            $table->string('no_telepon', 20);
            $table->string('ktp');
            $table->string('alamat', 100);
            $table->string('pekerjaan', 25);
            $table->string('jenis_usaha', 25);
            $table->bigInteger('nik');
            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obligation_retributions');
    }
};
