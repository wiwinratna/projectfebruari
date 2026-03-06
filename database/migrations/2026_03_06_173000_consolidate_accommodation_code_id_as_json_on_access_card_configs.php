<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('access_card_configs')) {
            return;
        }

        $hasOldScalar = Schema::hasColumn('access_card_configs', 'accommodation_code_id');
        $hasArrayCol = Schema::hasColumn('access_card_configs', 'accommodation_code_ids');

        $rows = collect();
        if ($hasOldScalar || $hasArrayCol) {
            $selects = ['id'];
            if ($hasOldScalar) $selects[] = 'accommodation_code_id';
            if ($hasArrayCol) $selects[] = 'accommodation_code_ids';
            $rows = DB::table('access_card_configs')->select($selects)->get();
        }

        Schema::table('access_card_configs', function (Blueprint $table) use ($hasOldScalar, $hasArrayCol) {
            if ($hasOldScalar) {
                try { $table->dropForeign('accfg_acm_fk'); } catch (\Throwable $e) {}
                try { $table->dropIndex('accfg_acm_idx'); } catch (\Throwable $e) {}
                $table->dropColumn('accommodation_code_id');
            }
            if ($hasArrayCol) {
                $table->dropColumn('accommodation_code_ids');
            }
        });

        Schema::table('access_card_configs', function (Blueprint $table) {
            $table->json('accommodation_code_id')->nullable()->after('transportation_code_id');
        });

        foreach ($rows as $row) {
            $ids = [];
            if ($hasArrayCol && isset($row->accommodation_code_ids) && $row->accommodation_code_ids) {
                $decoded = json_decode((string)$row->accommodation_code_ids, true);
                if (is_array($decoded)) {
                    $ids = collect($decoded)->map(fn($v) => (int)$v)->filter()->unique()->values()->all();
                }
            }
            if (empty($ids) && $hasOldScalar && isset($row->accommodation_code_id) && $row->accommodation_code_id) {
                $ids = [(int)$row->accommodation_code_id];
            }
            DB::table('access_card_configs')
                ->where('id', $row->id)
                ->update(['accommodation_code_id' => json_encode($ids)]);
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('access_card_configs') || !Schema::hasColumn('access_card_configs', 'accommodation_code_id')) {
            return;
        }

        $rows = DB::table('access_card_configs')->select(['id', 'accommodation_code_id'])->get();

        Schema::table('access_card_configs', function (Blueprint $table) {
            $table->dropColumn('accommodation_code_id');
            $table->unsignedBigInteger('accommodation_code_id')->nullable()->after('transportation_code_id');
            $table->index('accommodation_code_id', 'accfg_acm_idx');
            $table->foreign('accommodation_code_id', 'accfg_acm_fk')
                ->references('id')->on('accommodation_codes')
                ->nullOnDelete();
        });

        foreach ($rows as $row) {
            $decoded = json_decode((string)$row->accommodation_code_id, true);
            $firstId = is_array($decoded) && !empty($decoded) ? (int)($decoded[0] ?? 0) : null;
            DB::table('access_card_configs')
                ->where('id', $row->id)
                ->update(['accommodation_code_id' => $firstId ?: null]);
        }
    }
};
