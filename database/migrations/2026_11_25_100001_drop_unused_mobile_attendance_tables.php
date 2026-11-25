<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membersihkan 4 tabel mobile attendance yang tidak lagi digunakan per arahan Pak Aslap & PO:
     * - attendance_photos
     * - attendance_audit_logs
     * - mobile_attendances
     * - attendance_locations
     */
    public function up(): void
    {
        Schema::dropIfExists('attendance_photos');
        Schema::dropIfExists('attendance_audit_logs');
        Schema::dropIfExists('mobile_attendances');
        Schema::dropIfExists('attendance_locations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Fitur mobile attendance dihapus secara permanen per Phase 11
    }
};
