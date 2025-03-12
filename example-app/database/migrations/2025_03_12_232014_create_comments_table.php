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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Thêm ràng buộc khóa ngoại với bảng users và xóa dữ liệu liên quan khi xóa user
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // Thêm ràng buộc khóa ngoại với bảng posts và xóa dữ liệu liên quan khi xóa bài viết
            $table->text('content');
            $table->timestamps();
            $table->softDeletes(); // Thêm cột deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
