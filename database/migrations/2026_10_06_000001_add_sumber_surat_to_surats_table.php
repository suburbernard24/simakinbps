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
        Schema::table('surats', function (Blueprint $table) {
            if (!Schema::hasColumn('surats', 'sumber_surat')) {
                $table->string('sumber_surat', 20)->default('sistem')->after('status');
            }
            if (!Schema::hasColumn('surats', 'file_surat')) {
                $table->string('file_surat')->nullable()->after('sumber_surat');
            }
            if (!Schema::hasColumn('surats', 'nama_file_asli')) {
                $table->string('nama_file_asli')->nullable()->after('file_surat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('surats', 'sumber_surat')) {
                $columns[] = 'sumber_surat';
            }
            if (Schema::hasColumn('surats', 'file_surat')) {
                $columns[] = 'file_surat';
            }
            if (Schema::hasColumn('surats', 'nama_file_asli')) {
                $columns[] = 'nama_file_asli';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
