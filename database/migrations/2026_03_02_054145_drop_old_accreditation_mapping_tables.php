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
        Schema::dropIfExists('accreditation_mapping_job_category');
        Schema::dropIfExists('accreditation_job_category');
        Schema::dropIfExists('accreditation_mappings');
    }

    public function down(): void
    {
        // kosong aja, karena ini drop
    }
};

