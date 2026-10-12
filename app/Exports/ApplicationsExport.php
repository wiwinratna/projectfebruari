<?php

namespace App\Exports;

use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ApplicationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    protected $eventId;
    protected $search;

    public function __construct($eventId = null, $search = null)
    {
        $this->eventId = $eventId;
        $this->search = $search;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Application::with(['user.profile', 'opening.jobCategory', 'opening.event.city'])
            ->orderBy('created_at', 'desc');

        // Filter by event if specified
        if ($this->eventId) {
            $query->whereHas('opening', function($q) {
                $q->where('event_id', $this->eventId);
            });
        }

        // Apply search if present
        if ($this->search) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                    $uq->where('username', 'like', '%'.$search.'%')
                       ->orWhere('email', 'like', '%'.$search.'%');
                })
                ->orWhereHas('opening', function($oq) use ($search) {
                    $oq->where('title', 'like', '%'.$search.'%')
                       ->orWhereHas('event', function($eq) use ($search) {
                            $eq->where('title', 'like', '%'.$search.'%');
                       })
                       ->orWhereHas('jobCategory', function($jcq) use ($search) {
                            $jcq->where('name', 'like', '%'.$search.'%');
                       });
                });
            });
        }

        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [\
            'ID',
            'Applicant Name',
            'NIK',
            'Kewarganegaraan',
            'Phone',
            'Email',
            'Date of Birth',
            'Position',
            'Category',
            'Event',
            'City',
            'Address',
            'Status',
            'Applied Date',
            'Applied Time',
            'Review Notes',
            'Reviewed At'
        ];
    }

    /**
     * @param Application $application
     * @return array
     */
    public function map($application): array
    {
        $profile = $application->user->profile ?? null;
        $isWna = ($profile && $profile->nationality_type === 'wna');
        $kewarganegaraan = $profile ? ($isWna ? 'WNA' : 'WNI') : '-';
        $nik = (!$isWna && $profile && $profile->nik) ? (string) $profile->nik : '-';
        $phone = $profile && $profile->phone ? (string) $profile->phone : '-';
        $dob = $profile && $profile->date_of_birth ? $profile->date_of_birth->format('d M Y') : '-';
        $address = $profile && $profile->address ? $profile->address : '-';

        return [
            $application->id,
            $application->user->name ?? $application->user->username ?? 'N/A',
            $nik,
            $kewarganegaraan,
            $phone,
            $application->user->email ?? 'N/A',
            $dob,
            $application->opening->title ?? 'N/A',
            $application->opening->jobCategory->name ?? 'N/A',
            $application->opening->event->title ?? 'N/A',
            $application->opening->event->city->name ?? 'N/A',
            $address,
            ucfirst($application->status),
            $application->created_at->format('d M Y'),
            $application->created_at->format('H:i:s'),
            $application->review_notes ?? '-',
            $application->reviewed_at ? $application->reviewed_at->format('d M Y H:i') : '-'
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as header
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'EF4444']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true]
            ],
        ];
    }
}
