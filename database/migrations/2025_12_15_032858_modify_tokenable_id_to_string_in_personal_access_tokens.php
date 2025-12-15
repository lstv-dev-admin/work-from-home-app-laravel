<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change tokenable_id from unsigned big integer to string (varchar)
        // This is needed because Userfile uses string primary key (usrcde)
        DB::statement('ALTER TABLE `personal_access_tokens` MODIFY `tokenable_id` VARCHAR(255) NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to unsigned big integer
        DB::statement('ALTER TABLE `personal_access_tokens` MODIFY `tokenable_id` BIGINT UNSIGNED NOT NULL');
    }
};
