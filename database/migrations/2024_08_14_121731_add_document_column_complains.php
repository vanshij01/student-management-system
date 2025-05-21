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
        Schema::table('complains', function (Blueprint $table) {
            $table->string('document', 255)->nullable()->default(null)->after('type');
            $table->dropForeign(['complain_by']);
            $table->dropIndex('complains_complain_by_index');
            $table->renameColumn('complain_by', 'user_id');
            $table->unsignedBigInteger('complain_by')->nullable()->after('user_id');
            $table->foreign('complain_by')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complains', function (Blueprint $table) {
            $table->dropColumn('document');
            $table->renameColumn('user_id', 'complain_by');
            $table->dropForeign(['complain_by']);
            $table->dropColumn('complain_by');
        });
    }
};
