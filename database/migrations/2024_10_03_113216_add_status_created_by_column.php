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
        Schema::table('country', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->comment("1 => Enable, 0 => Disabled")->after('name');
            $table->bigInteger('created_by')->default(1)->after('status');
        });

        Schema::table('villages', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->comment("1 => Enable, 0 => Disabled")->after('name');
            $table->bigInteger('created_by')->default(1)->after('status');
        });

        Schema::table('document_type', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->comment("1 => Enable, 0 => Disabled")->after('type');
            $table->bigInteger('created_by')->default(1)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('country', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('created_by');
        });

        Schema::table('villages', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('created_by');
        });

        Schema::table('document_type', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('created_by');
        });
    }
};
