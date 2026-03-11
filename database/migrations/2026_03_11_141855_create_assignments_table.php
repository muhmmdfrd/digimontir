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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users');
            $table->foreignId('technician_id')->constrained('users');
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('status_id')->constrained('statuses');
            $table->text('description_by_admin');
            $table->decimal('lat_check_in', 10, 8)->nullable();
            $table->decimal('lng_check_in', 11, 8)->nullable();
            $table->string('check_in_photo_path')->nullable();
            $table->string('check_out_photo_path')->nullable();
            $table->decimal('lat_check_out', 10, 8)->nullable();
            $table->decimal('lng_check_out', 11, 8)->nullable();
            $table->text('description_by_technician')->nullable();
            $table->integer('rating')->nullable();
            $table->text('review_by_admin')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
