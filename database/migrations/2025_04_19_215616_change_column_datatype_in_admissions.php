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
            $table->text('nationality')->nullable(true)->default(null)->change();
            $table->renameColumn('DOB', 'dob');
            $table->boolean('is_indian_citizen')->default(1)->after('nationality');
            $table->boolean('is_local_guardian_in_ahmedabad')->default(1)->after('father_full_name');
            $table->boolean('is_parent_indian_citizen')->default(1)->after('father_full_name');
            $table->string('education_type')->default(null)->before('course_id');
            $table->string('father_photo_url')->nullable()->default(null)->after('student_photo_url');
            $table->string('mother_photo_url')->nullable()->default(null)->after('father_photo_url');
            $table->string('board_type')->nullable()->default(null)->after('course_id');
            $table->string('board_name')->nullable()->default(null)->after('board_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->dropColumn('is_indian_citizen');
            $table->dropColumn('is_local_guardian_in_ahmedabad');
            $table->dropColumn('is_parent_indian_citizen');
            $table->dropColumn('education_type');
            $table->dropColumn('board_type');
            $table->dropColumn('board_name');
        });
    }
};
