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
        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('accreditation_id')
                ->nullable()
                ->after('status')
                ->constrained('accreditations')
                ->nullOnDelete();

            $table->foreignId('accreditation_set_by')
                ->nullable()
                ->after('accreditation_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('accreditation_set_at')
                ->nullable()
                ->after('accreditation_set_by');

            $table->index(['status', 'accreditation_id']);
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex(['status', 'accreditation_id']);
            $table->dropConstrainedForeignId('accreditation_id');
            $table->dropConstrainedForeignId('accreditation_set_by');
            $table->dropColumn('accreditation_set_at');
        });
    }
};
