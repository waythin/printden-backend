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
        if (!Schema::hasTable('admins')) {

            Schema::create('admins', function (Blueprint $table) {
                $table->id();
                $table->string('name', 500);
                $table->string('mobile', 20)->nullable();
                $table->string('email')->unique();
                $table->string('password')->nullable();
                $table->string('image',500)->nullable(); //iMAGE FULL pATH
                $table->integer('role_id');
                $table->integer('member_id')->nullable();
                $table->integer('is_admin')->nullable();
                $table->string('email_verify_token')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->Integer('login_failed')->default(0);
                $table->string('token', 1000)->nullable()->unique();
                $table->string('refresh_token', 1000)->nullable();
                $table->dateTime('token_expired_at')->nullable();
                $table->enum('status', ['active', 'inactive', 'restricted', 'pending']);
                $table->dateTime('deleted_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
