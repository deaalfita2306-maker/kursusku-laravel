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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username', 50)->nullable()->unique()->after('name');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'user'])->default('user')->after('password');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo', 255)->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('profile_photo');
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('users', 'username') ? 'username' : null,
                Schema::hasColumn('users', 'role') ? 'role' : null,
                Schema::hasColumn('users', 'phone') ? 'phone' : null,
                Schema::hasColumn('users', 'profile_photo') ? 'profile_photo' : null,
                Schema::hasColumn('users', 'is_active') ? 'is_active' : null,
                Schema::hasColumn('users', 'last_login_at') ? 'last_login_at' : null,
            ]);

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
