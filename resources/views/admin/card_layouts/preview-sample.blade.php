<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Preview - {{ $event->title }}</title>
    <style>
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        :root {
            --sheet-w: 148mm;
            --sheet-h: 210mm;
        }

        @page {
            size: A5 portrait;
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            padding: 16px;
            background: #e5e7eb;
            font-family: Arial, sans-serif;
        }

        .preview-shell {
            max-width: 860px;
            margin: 0 auto;
            background: #f8fafc;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 16px;
        }

        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .toolbar-title {
            font-size: 20px;
            font-weight: 700;
            line-height: 1.2;
        }

        .toolbar-subtitle {
            color: #6b7280;
            font-size: 13px;
            margin-top: 2px;
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn {
            background: #2563eb;
            color: white;
            border: 0;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-secondary {
            background: #0f766e;
        }

        .print-note {
            margin-bottom: 12px;
            font-size: 12px;
            color: #4b5563;
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            border-radius: 6px;
            padding: 8px 10px;
        }

        .sheet-stage {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 8px 0 4px;
        }

        .sheet-wrap {
            width: var(--sheet-w);
            height: var(--sheet-h);
            position: relative;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.16);
            page-break-inside: avoid;
            break-inside: avoid;
            overflow: hidden;
        }

        #preview-container {
            width: 100%;
            height: 100%;
            margin: 0 auto;
            position: relative;
            background: white;
        }

        .guide-layer {
            position: absolute;
            inset: 0;
            z-index: 30;
            pointer-events: none;
        }

        .guide-border {
            position: absolute;
            inset: 0;
            border: 1px dashed rgba(37, 99, 235, 0.35);
        }

        .guide-grid {
            position: absolute;
            inset: 0;
            background-image:
                repeating-linear-gradient(to right, rgba(15, 23, 42, 0.08) 0, rgba(15, 23, 42, 0.08) 0.2mm, transparent 0.2mm, transparent 5mm),
                repeating-linear-gradient(to bottom, rgba(15, 23, 42, 0.08) 0, rgba(15, 23, 42, 0.08) 0.2mm, transparent 0.2mm, transparent 5mm);
            opacity: 0.35;
        }

        body.guides-off .guide-layer {
            display: none;
        }

        @media print {
            html, body {
                width: var(--sheet-w);
                height: var(--sheet-h);
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
                overflow: hidden !important;
            }

            .preview-shell {
                width: var(--sheet-w);
                height: var(--sheet-h);
                margin: 0 !important;
                padding: 0 !important;
                border: 0 !important;
                border-radius: 0 !important;
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            .sheet-stage {
                padding: 0;
                margin: 0;
                width: var(--sheet-w);
                height: var(--sheet-h);
            }

            .sheet-wrap {
                width: var(--sheet-w);
                height: var(--sheet-h);
                box-shadow: none !important;
                margin: 0 !important;
                page-break-after: always;
                page-break-inside: avoid;
                break-after: page;
                break-inside: avoid;
            }

            .guide-layer {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="preview-shell">
        <div class="toolbar no-print">
            <div>
                <div class="toolbar-title">Preview Sample Card</div>
                <div class="toolbar-subtitle">{{ $event->title }}</div>
            </div>
            <div class="toolbar-actions">
                <button id="btnGuides" class="btn btn-secondary" type="button" onclick="toggleGuides()">Hide Guides</button>
                <button class="btn" type="button" onclick="window.print()">Print / Save PDF</button>
            </div>
        </div>

        <div class="print-note no-print">
            Enable "Background graphics" in the print dialog for best results.
        </div>

        <div class="sheet-stage">
            <div class="sheet-wrap">
                <div id="preview-container">
                    @include('admin.card_layouts.preview-card-content')
                </div>
                <div class="guide-layer" id="guideLayer">
                    <div class="guide-grid"></div>
                    <div class="guide-border"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleGuides() {
            document.body.classList.toggle('guides-off');
            const btn = document.getElementById('btnGuides');
            if (!btn) return;
            btn.textContent = document.body.classList.contains('guides-off') ? 'Show Guides' : 'Hide Guides';
        }
    </script>
</body>
</html>
