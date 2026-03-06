<?php

namespace App\Support;

class CardLayoutRenderStyle
{
    public const PT_TO_PX = 96 / 72;
    public const MM_TO_PX = 3.77953;

    public static function chipPreset(string $size = 'medium'): array
    {
        return match (strtolower($size)) {
            'small' => ['fontPt' => 6.5, 'padding' => '2px 6px', 'padX' => 6, 'padY' => 2, 'radius' => 2, 'minHeightPx' => 18],
            'large' => ['fontPt' => 9.0, 'padding' => '4px 10px', 'padX' => 10, 'padY' => 4, 'radius' => 4, 'minHeightPx' => 26],
            default => ['fontPt' => 7.5, 'padding' => '3px 8px', 'padX' => 8, 'padY' => 3, 'radius' => 3, 'minHeightPx' => 22],
        };
    }

    public static function chipFontPt(array $style, array $preset): float
    {
        return (float)($style['fontSizePt'] ?? $preset['fontPt']);
    }

    public static function iconSizePx(float $chipFontPt): int
    {
        return max(10, (int)round(($chipFontPt * self::PT_TO_PX) * 0.9));
    }

    public static function borderRadius(array $style, float $fallback = 4): array
    {
        $radius = is_array($style['borderRadius'] ?? null) ? $style['borderRadius'] : [];

        return [
            'tl' => (float)($radius['tl'] ?? $style['borderRadiusTl'] ?? $fallback),
            'tr' => (float)($radius['tr'] ?? $style['borderRadiusTr'] ?? $fallback),
            'br' => (float)($radius['br'] ?? $style['borderRadiusBr'] ?? $fallback),
            'bl' => (float)($radius['bl'] ?? $style['borderRadiusBl'] ?? $fallback),
        ];
    }

    public static function borderRadiusCss(array $style, float $fallback = 4): string
    {
        $r = self::borderRadius($style, $fallback);
        return "{$r['tl']}px {$r['tr']}px {$r['br']}px {$r['bl']}px";
    }

    public static function editorConfig(): array
    {
        return [
            'ptToPx' => self::PT_TO_PX,
            'mmToPx' => self::MM_TO_PX,
            'chipPresets' => [
                'small' => self::chipPreset('small'),
                'medium' => self::chipPreset('medium'),
                'large' => self::chipPreset('large'),
            ],
        ];
    }

    public static function chipRenderConfig(array $style = []): array
    {
        $chipSize = (string)($style['chipSize'] ?? 'medium');
        $preset = self::chipPreset($chipSize);
        $fontPt = self::chipFontPt($style, $preset);
        $radiusCss = self::borderRadiusCss($style, (float)($preset['radius'] ?? 3));
        $padX = (int)($preset['padX'] ?? 8);
        $padY = (int)($preset['padY'] ?? 3);
        $gap = (int)($style['chipGap'] ?? 4);
        $lineClamp = max(1, (int)($style['lineClamp'] ?? $style['linesMax'] ?? 3));
        $borderWidth = 1;
        $fontPx = $fontPt * self::PT_TO_PX;
        $rowHeightPx = max(1, (int)round($fontPx + ($padY * 2) + ($borderWidth * 2) + $gap));
        $maxHeightPx = $rowHeightPx * $lineClamp;

        return [
            'chipSize' => strtolower($chipSize),
            'fontPt' => $fontPt,
            'fontPx' => $fontPx,
            'padX' => $padX,
            'padY' => $padY,
            'gap' => $gap,
            'lineClamp' => $lineClamp,
            'borderWidth' => $borderWidth,
            'radiusCss' => $radiusCss,
            'rowHeightPx' => $rowHeightPx,
            'maxHeightPx' => $maxHeightPx,
            'iconSizePx' => self::iconSizePx($fontPt),
        ];
    }
}
