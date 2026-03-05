@php
    $layout = $layout ?: \App\Models\CardLayout::getDefaultLayout();
    $snapshot = $card->snapshot ?? [];
    $pxPerMm = 3.77953;
    $templatePath = $event->card_template_path;
    $templateExists = $templatePath ? Storage::disk('public')->exists($templatePath) : false;
    $templateUrl = $templatePath ? asset('storage/' . $templatePath) : null;
    $effectiveLayout = $templateExists ? $layout : \App\Models\CardLayout::getDefaultLayout();
@endphp

<div style="width: 148mm; height: 210mm; position: relative; background: white; overflow: hidden;">
    <!-- Background Template -->
    @if ($templateExists && $templateUrl)
        <img
            src="{{ $templateUrl }}"
            alt="Template"
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;"
        />
    @endif

    <!-- Rendered Elements -->
    @foreach ($effectiveLayout['elements'] ?? [] as $element)
        @php
            $rect = $element['rect'] ?? [];
            $xMm = $rect['xMm'] ?? (isset($rect['x']) ? round($rect['x'] / $pxPerMm, 3) : 0);
            $yMm = $rect['yMm'] ?? (isset($rect['y']) ? round($rect['y'] / $pxPerMm, 3) : 0);
            $wMm = $rect['wMm'] ?? (isset($rect['w']) ? round($rect['w'] / $pxPerMm, 3) : 0);
            $hMm = $rect['hMm'] ?? (isset($rect['h']) ? round($rect['h'] / $pxPerMm, 3) : 0);
        @endphp
        @if (!empty($element['visible']))
            <div style="position: absolute; left: {{ $xMm }}mm; top: {{ $yMm }}mm; width: {{ $wMm }}mm; height: {{ $hMm }}mm; overflow: hidden; z-index: 10;">
                @if ($element['type'] === 'photo')
                    {{-- Photo Element --}}
                    @if (!empty($snapshot['applicant_photo']))
                        @php
                            // Check if it's a data URI
                            if (str_starts_with($snapshot['applicant_photo'], 'data:')) {
                                $photoUrl = $snapshot['applicant_photo'];
                            } else {
                                $photoUrl = Storage::disk('public')->url($snapshot['applicant_photo']);
                            }
                        @endphp
                        <img
                            src="{{ $photoUrl }}"
                            alt="Photo"
                            style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;"
                        />
                    @else
                        <div style="width: 100%; height: 100%; background: #e5e7eb; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #999;">
                            No Photo
                        </div>
                    @endif

                @elseif ($element['type'] === 'qr')
                    {{-- QR Code Element --}}
                    @if (!empty($snapshot['qr_code']))
                        <img
                            src="{{ $snapshot['qr_code'] }}"
                            alt="QR Code"
                            style="width: 100%; height: 100%; object-fit: contain;"
                        />
                    @else
                        <div style="width: 100%; height: 100%; background: #e5e7eb; display: flex; align-items: center; justify-content: center; font-size: 8px; color: #999;">
                            QR
                        </div>
                    @endif

                @elseif ($element['type'] === 'text-name')
                    {{-- Name Text --}}
                    @php
                        $nameAlign = $element['style']['align'] ?? 'left';
                        $nameFontSize = $element['style']['fontSizePt'] ?? $element['style']['fontSize'] ?? 14;
                        $nameMaxLines = $element['style']['maxLines'] ?? 2;
                    @endphp
                    <div style="display: block; width: 100%; height: 100%; padding: 4px; font-weight: bold; font-size: {{ $nameFontSize }}pt; line-height: 1.2; text-align: {{ $nameAlign }}; overflow: hidden; text-overflow: ellipsis; -webkit-line-clamp: {{ $nameMaxLines }}; -webkit-box-orient: vertical; display: -webkit-box;">
                        {{ $snapshot['applicant_name'] ?? 'SAMPLE PARTICIPANT NAME' }}
                    </div>

                @elseif ($element['type'] === 'text-job')
                    {{-- Job Category Text --}}
                    @php
                        $jobAlign = $element['style']['align'] ?? 'left';
                        $jobFontSize = $element['style']['fontSizePt'] ?? $element['style']['fontSize'] ?? 10;
                    @endphp
                    <div style="display: block; width: 100%; height: 100%; padding: 4px; font-size: {{ $jobFontSize }}pt; color: #666; text-align: {{ $jobAlign }}; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ $snapshot['job_category_name'] ?? 'ROLE / POSITION' }}
                    </div>

                @elseif ($element['type'] === 'text-accreditation')
                    {{-- Accreditation Badge --}}
                    @php
                        $accreditationText = $snapshot['mapping_name'] ?? 'VIP';
                        $accreditationColor = $snapshot['mapping_color'] ?? '#6b7280';
                        $accreditationFontSize = max(10, min(28, $hMm * 2.2));
                        $accreditationRadius = min(8, $hMm * 0.8);
                    @endphp
                    <div style="display: block; width: 100%; height: 100%; overflow: hidden;">
                        <span style="display: flex; width: 100%; height: 100%; align-items: center; justify-content: center; background-color: {{ $accreditationColor }}; color: white; padding: 0 6px; border-radius: {{ $accreditationRadius }}px; font-size: {{ $accreditationFontSize }}px; font-weight: bold; line-height: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $accreditationText }}
                        </span>
                    </div>

                @elseif ($element['type'] === 'group-badges')
                    {{-- Transport & Accommodation Group --}}
                    <div style="width: 100%; height: 100%; padding: 4px; display: flex; flex-wrap: wrap; gap: 4px; align-content: flex-start; align-items: flex-start; overflow: hidden; font-size: 8pt;">
                        @php
                            $transports = $snapshot['transports'] ?? [];
                            $accommodations = $snapshot['accommodations'] ?? [];
                            $badges = array_merge(
                                array_map(fn($t) => ['label' => $t['code'] ?? '', 'color' => $t['color_hex'] ?? '#6366f1'], $transports),
                                array_map(fn($a) => ['label' => $a['code'] ?? '', 'color' => $a['color_hex'] ?? '#8b5cf6'], $accommodations)
                            );
                            if (empty($badges)) {
                                $badges = [
                                    ['label' => 'Transport', 'color' => '#9ca3af'],
                                    ['label' => 'Hotel', 'color' => '#9ca3af'],
                                    ['label' => 'Shuttle', 'color' => '#9ca3af'],
                                ];
                            }
                        @endphp
                        @foreach ($badges as $badge)
                            <span style="background-color: {{ $badge['color'] }}; color: white; padding: 3px 6px; border-radius: 3px; font-weight: bold; font-size: 7pt; white-space: nowrap; max-width: 100%; overflow: hidden; text-overflow: ellipsis;">
                                {{ Str::upper($badge['label']) }}
                            </span>
                        @endforeach
                    </div>

                @elseif ($element['type'] === 'group-chips')
                    {{-- Venue & Zone Chips --}}
                    <div style="width: 100%; height: 100%; padding: 4px; display: flex; flex-wrap: wrap; gap: 4px; align-content: flex-start; align-items: flex-start; font-size: 8pt; overflow: hidden;">
                        @php
                            $venueChips = $snapshot['venue_chips'] ?? [];
                            $zoneChips = $snapshot['zone_chips'] ?? [];
                            $maxVenue = $element['style']['maxVenueChips'] ?? 4;
                            $maxZone = $element['style']['maxZoneChips'] ?? 4;
                            $previewVenue = array_slice($venueChips, 0, $maxVenue);
                            $previewZone = array_slice($zoneChips, 0, $maxZone);
                            if (empty($previewVenue) && empty($previewZone)) {
                                $previewZone = [
                                    ['code' => 'PTN1'],
                                    ['code' => 'PTN2'],
                                    ['code' => 'ALL'],
                                ];
                            }
                        @endphp
                        @foreach ($previewVenue as $venue)
                            <span style="background-color: #059669; color: white; padding: 3px 6px; border-radius: 12px; font-size: 7pt; white-space: nowrap; max-width: 100%; overflow: hidden; text-overflow: ellipsis;">
                                {{ Str::limit($venue['code'] ?? '', 12) }}
                            </span>
                        @endforeach
                        @foreach ($previewZone as $zone)
                            <span style="background-color: #2563eb; color: white; padding: 3px 6px; border-radius: 12px; font-size: 7pt; white-space: nowrap; max-width: 100%; overflow: hidden; text-overflow: ellipsis;">
                                {{ Str::limit($zone['code'] ?? '', 12) }}
                            </span>
                        @endforeach
                    </div>

                @endif
            </div>
        @endif
    @endforeach
</div>
