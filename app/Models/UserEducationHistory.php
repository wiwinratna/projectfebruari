<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEducationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'education_level',
        'institution_name',
        'field_of_study',
        'graduation_year',
        'is_still_studying',
        'proof_document',
        'proof_document_original_name',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_still_studying' => 'boolean',
        ];
    }

    /**
     * Education level display labels
     */
    public static function educationLevels(): array
    {
        return [
            'SD'      => 'SD (Sekolah Dasar)',
            'SMP'     => 'SMP (Sekolah Menengah Pertama)',
            'SMA'     => 'SMA (Sekolah Menengah Atas)',
            'SMK'     => 'SMK (Sekolah Menengah Kejuruan)',
            'D1'      => 'D1 (Diploma 1)',
            'D2'      => 'D2 (Diploma 2)',
            'D3'      => 'D3 (Diploma 3)',
            'D4'      => 'D4 (Diploma 4)',
            'S1'      => 'S1 (Sarjana)',
            'S2'      => 'S2 (Magister)',
            'S3'      => 'S3 (Doktor)',
            'Lainnya' => 'Lainnya',
        ];
    }

    /**
     * Get the user that owns this education record.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the proof document URL.
     */
    public function getProofDocumentUrlAttribute(): ?string
    {
        if (!$this->proof_document) return null;
        return url('media/' . ltrim($this->proof_document, '/'));
    }
}
