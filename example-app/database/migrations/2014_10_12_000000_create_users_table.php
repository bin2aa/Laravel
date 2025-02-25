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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');

            $table->rememberToken();
            //thêm first_name và last_name
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            
            //thêm status và role
            $table->tinyInteger('status')->default(0);
            $table->string('role')->default('user');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void  //dùng để xóa bảng
    {
        Schema::dropIfExists('users'); // xóa bảng users
        
    }
};
