@php
    $layout = $layout ?: \App\Models\CardLayout::getDefaultLayout();
    $snapshot = $card->snapshot ?? [];
    $pxPerMm = 3.77953;
    $templatePath = $event->card_template_path;
    $templateExists = $templatePath ? Storage::disk('public')->exists($templatePath) : false;
    $templateUrl = $templatePath ? url('/media/' . ltrim($templatePath, '/')) : null;
    $effectiveLayout = $layout ?: \App\Models\CardLayout::getDefaultLayout();
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
            @php
                $style = is_array($element['style'] ?? null) ? $element['style'] : [];
            @endphp
            <div style="position: absolute; left: {{ $xMm }}mm; top: {{ $yMm }}mm; width: {{ $wMm }}mm; height: {{ $hMm }}mm; overflow: hidden; z-index: 10;">
                @if ($element['type'] === 'photo')
                    {{-- Photo Element --}}
                    @if (!empty($snapshot['applicant_photo']))
                        @php
                            // Check if it's a data URI
                            if (str_starts_with($snapshot['applicant_photo'], 'data:')) {
                                $photoUrl = $snapshot['applicant_photo'];
                            } else {
                                // Use /media/ route to bypass Cloudflare WAF blocking /storage/* paths
                                $photoUrl = url('/media/' . ltrim($snapshot['applicant_photo'], '/'));
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
                    @endphp
                    <x-card.accreditation-label :text="$accreditationText" :color="$accreditationColor" :style="$style" />

                @elseif ($element['type'] === 'group-badges')
                    {{-- Transport & Accommodation Group --}}
                    @php
                        $transports = collect($snapshot['transports'] ?? [])->map(function ($item) {
                            if (is_array($item)) {
                                return [
                                    'code' => trim((string)($item['code'] ?? '')),
                                    'show_code' => (bool)($item['show_code'] ?? true),
                                ];
                            }
                            return [
                                'code' => trim((string)$item),
                                'show_code' => true,
                            ];
                        })->filter(fn($item) => $item['code'] !== '')->values()->all();
                        $accommodations = collect($snapshot['accommodations'] ?? [])->map(function ($item) {
                            if (is_array($item)) {
                                return [
                                    'code' => trim((string)($item['code'] ?? '')),
                                    'show_code' => (bool)($item['show_code'] ?? true),
                                ];
                            }
                            return [
                                'code' => trim((string)$item),
                                'show_code' => true,
                            ];
                        })->filter(fn($item) => $item['code'] !== '')->values()->all();
                        $badgeItems = array_merge(
                            array_map(fn($t) => [
                                'code' => $t['code'],
                                'kind' => 'transport',
                                'icon_key' => null,
                                'show_icon' => false,
                                'show_code' => (bool)($t['show_code'] ?? true),
                            ], $transports),
                            array_map(fn($a) => [
                                'code' => $a['code'],
                                'kind' => 'hotel',
                                'icon_key' => null,
                                'show_icon' => false,
                                'show_code' => (bool)($a['show_code'] ?? true),
                            ], $accommodations)
                        );
                        if (empty($badgeItems)) {
                            $badgeItems = collect(['TRANSPORT', 'HOTEL', 'SHUTTLE'])
                                ->map(fn($code) => [
                                    'code' => $code,
                                    'kind' => 'transport',
                                    'icon_key' => null,
                                    'show_icon' => false,
                                    'show_code' => true,
                                ])->all();
                        }
                    @endphp
                    <x-card.chips-badges :items="$badgeItems" :style="$style" />

                @elseif ($element['type'] === 'group-chips')
                    {{-- Venue & Zone Chips --}}
                    @php
                        $venueChips = collect($snapshot['venue_chips'] ?? [])->map(function ($item) {
                            if (is_array($item)) {
                                return ['code' => trim((string)($item['code'] ?? ''))];
                            }
                            return ['code' => trim((string)$item)];
                        })->filter(fn($item) => $item['code'] !== '')->values()->all();
                        $zoneChips = collect($snapshot['zone_chips'] ?? [])->map(function ($item) {
                            if (is_array($item)) {
                                return ['code' => trim((string)($item['code'] ?? ''))];
                            }
                            return ['code' => trim((string)$item)];
                        })->filter(fn($item) => $item['code'] !== '')->values()->all();
                        $maxVenue = $element['style']['maxVenueChips'] ?? 4;
                        $maxZone = $element['style']['maxZoneChips'] ?? 4;
                        $previewVenue = array_slice($venueChips, 0, $maxVenue);
                        $previewZone = array_slice($zoneChips, 0, $maxZone);
                        $zoneItems = array_slice(array_merge($previewVenue, $previewZone), 0, 3);
                        if (empty($zoneItems)) {
                            $zoneItems = collect(['PTN1', 'PTN2', 'ALL'])
                                ->map(fn($code) => ['code' => $code])
                                ->all();
                        }
                    @endphp
                    <x-card.chips-zones :items="$zoneItems" :style="$style" max-items="3" />

                @endif
            </div>
        @endif
    @endforeach
</div>
