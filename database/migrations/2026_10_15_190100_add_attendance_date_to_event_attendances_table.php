<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah kolom attendance_date jika belum ada
        Schema::table('event_attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('event_attendances', 'attendance_date')) {
                $table->date('attendance_date')->nullable()->after('user_id');
            }
        });

        // 2. Isi data absensi lama dengan tanggal check_in_at atau created_at agar tidak null
        try {
            DB::statement("UPDATE event_attendances SET attendance_date = DATE(COALESCE(check_in_at, created_at)) WHERE attendance_date IS NULL");
        } catch (\Throwable $e) {
            // Abaikan jika tabel kosong
        }

        // 3. Tambahkan index biasa pada event_id agar foreign key tidak memblokir penghapusan unique index lama
        Schema::table('event_attendances', function (Blueprint $table) {
            try {
                $table->index('event_id');
            } catch (\Throwable $e) {
                // Abaikan jika index sudah ada
            }
        });

        // 4. Hapus unique index lama dan buat unique index baru yang mencakup attendance_date
        Schema::table('event_attendances', function (Blueprint $table) {
            try {
                $table->dropUnique(['event_id', 'user_id']);
            } catch (\Throwable $e) {
                // Abaikan jika index sudah terhapus
            }

            try {
                $table->unique(['event_id', 'user_id', 'attendance_date']);
            } catch (\Throwable $e) {
                // Abaikan jika sudah ada
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_attendances', function (Blueprint $table) {
            try {
                $table->dropUnique(['event_id', 'user_id', 'attendance_date']);
            } catch (\Throwable $e) {}

            try {
                $table->unique(['event_id', 'user_id']);
            } catch (\Throwable $e) {}

            if (Schema::hasColumn('event_attendances', 'attendance_date')) {
                $table->dropColumn('attendance_date');
            }
        });
    }
};
