<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /* public function up(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->renameColumn('notes', 'student_note');
            $table->text('admin_comment')->after('student_note')->nullable();
        });
    } */

    /**
     * Reverse the migrations.
     */
    /* public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->dropColumn('admin_comment');
        });
    } */
};
