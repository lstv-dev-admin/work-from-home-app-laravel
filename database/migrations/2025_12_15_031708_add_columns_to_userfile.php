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
        Schema::table('userfile', function (Blueprint $table) {
            // $table->string('email')->nullable()->after('usrcde');
            // $table->string('name')->nullable()->after('email');
            $table->timestamp('email_verified_at')->nullable()->after('usrcde');
            $table->rememberToken()->after('usrpwd');
            $table->timestamps()->after('monitorsetup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('userfile', function (Blueprint $table) {
            $table->dropColumn(['email_verified_at', 'remember_token', 'created_at', 'updated_at']);
        });
    }
};
