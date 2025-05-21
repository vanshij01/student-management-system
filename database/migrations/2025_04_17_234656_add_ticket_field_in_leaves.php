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
        Schema::table('leaves', function (Blueprint $table) {
            $table->string('subject', 255)->nullable()->default(null)->after('leave_apply_by');
            $table->string('ticket', 255)->nullable()->default(null)->after('leave_to');
            $table->text('note')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn('subject');
            $table->dropColumn('ticket');
        });
    }
};
