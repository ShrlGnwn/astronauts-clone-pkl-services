<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom access: pembeda user biasa (belanja via API) vs Admin (boleh
     * login ke dashboard web).
     *   'customer' -> default, dipakai untuk belanja (API /api/auth/*)
     *   'admin'    -> bisa login dashboard (/login → /dashboard)
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('access')->default('customer');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('access');
        });
    }
};
