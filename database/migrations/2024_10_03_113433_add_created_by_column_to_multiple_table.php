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
        Schema::table('admissions', function (Blueprint $table) {
            $table->bigInteger('created_by')->default(1)->before('created_at');
        });

        Schema::table('apology_letters', function (Blueprint $table) {
            $table->bigInteger('created_by')->default(1)->before('created_at');
        });

        Schema::table('beds', function (Blueprint $table) {
            $table->bigInteger('created_by')->default(1)->before('created_at');
        });

        Schema::table('complains', function (Blueprint $table) {
            $table->bigInteger('created_by')->default(1)->before('created_at');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->bigInteger('created_by')->default(1)->before('created_at');
        });

        Schema::table('fees', function (Blueprint $table) {
            $table->bigInteger('created_by')->default(1)->before('created_at');
        });

        Schema::table('hostels', function (Blueprint $table) {
            $table->bigInteger('created_by')->default(1)->before('created_at');
        });

        Schema::table('leaves', function (Blueprint $table) {
            $table->bigInteger('created_by')->default(1)->before('created_at');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->bigInteger('created_by')->default(1)->before('created_at');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->bigInteger('created_by')->default(1)->before('created_at');
        });

        Schema::table('wardens', function (Blueprint $table) {
            $table->bigInteger('created_by')->default(1)->before('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });

        Schema::table('apology_letters', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });

        Schema::table('beds', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });

        Schema::table('complains', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });

        Schema::table('fees', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });

        Schema::table('hostels', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });

        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });

        Schema::table('wardens', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });
    }
};
