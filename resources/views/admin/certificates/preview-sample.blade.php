<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Preview - {{ $event->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        :root {
            --sheet-w: 297mm;
            --sheet-h: 210mm;
        }

        @page {
            size: A4 landscape;
            margin: 0;
        }

        html, body {
            margin: 0; padding: 0;
        }

        body {
            min-height: 100vh;
            padding: 24px;
            background: #f1f5f9;
            font-family: "Inter", ui-sans-serif, system-ui, -apple-system, sans-serif;
        }

        .preview-shell {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header-bar {
            background: white;
            padding: 20px 24px;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .title-group h1 {
            font-size: 18px;
            font-weight: 900;
            color: #1e293b;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .title-group p {
            font-size: 13px;
            color: #64748b;
            margin: 4px 0 0 0;
            font-weight: 500;
        }

        .action-group {
            display: flex;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            border: none;
        }

        .btn-blue { background: #2563eb; color: white; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); }
        .btn-blue:hover { background: #1d4ed8; transform: translateY(-1px); }

        .btn-white { background: white; color: #475569; border: 1px solid #e2e8f0; }
        .btn-white:hover { border-color: #cbd5e1; background: #f8fafc; }

        .btn-gray { background: #1e293b; color: white; }
        .btn-gray:hover { background: #0f172a; }

        .stage {
            display: flex;
            justify-content: center;
            padding-bottom: 50px;
        }

        .certificate-container {
            width: var(--sheet-w);
            height: var(--sheet-h);
            background: white;
            box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
            border-radius: 4px;
            overflow: hidden;
            transform-origin: top center;
        }

        .print-tip {
            background: #fef3c7;
            border: 1px solid #fde68a;
            color: #92400e;
            padding: 12px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        @media print {
            body { padding: 0; background: white; }
            .no-print { display: none !important; }
            .header-bar, .print-tip { display: none !important; }
            .stage { padding: 0; }
            .certificate-container { box-shadow: none; border-radius: 0; transform: none !important; }
        }
    </style>
</head>
<body>
    <div class="preview-shell">
        <div class="header-bar no-print">
            <div class="title-group">
                <h1>
                    @if(!empty($isReal))
                        Certificate Preview
                    @else
                        Sample Preview
                    @endif
                </h1>
                <p>{{ $samplePayload['volunteer_name'] ?? '—' }} • {{ $event->title }}</p>
            </div>
            
            <div class="action-group">
                @if(!empty($isReal) && !empty($certificate))
                    <a href="{{ route('admin.certificates.download', $certificate) }}" class="btn btn-blue">
                        <i class="fas fa-download"></i> Download PDF
                    </a>
                @endif
                <button onclick="window.print()" class="btn btn-white">
                    <i class="fas fa-print"></i> Print
                </button>

            </div>
        </div>

        <div class="print-tip no-print">
            <i class="fas fa-info-circle"></i>
            <span>Tip: For best results, enable <strong>"Background graphics"</strong> in the print settings and set the paper size to <strong>A4 Landscape</strong>.</span>
        </div>

        <div class="stage">
            <div class="certificate-container" id="certFrame">
                @include('admin.certificates.preview-content', [
                    'layout'      => $layout,
                    'payload'     => $samplePayload,
                    'event'       => $event,
                    'layoutModel' => $layoutModel,
                ])
            </div>
        </div>
    </div>

    <script>
        function autoScale() {
            const frame = document.getElementById('certFrame');
            const shell = document.querySelector('.preview-shell');
            if(!frame || !shell) return;
            
            const shellWidth = shell.clientWidth - 48; // padding
            const certWidth  = 1122.52; // 297mm in pixels 
            
            if (shellWidth < certWidth) {
                const scale = shellWidth / certWidth;
                frame.style.transform = `scale(${scale})`;
                // Adjust height of stage to compensate for scale reduction
                document.querySelector('.stage').style.height = (210 * scale) + 'mm';
            } else {
                frame.style.transform = 'none';
                document.querySelector('.stage').style.height = 'auto';
            }
        }

        window.addEventListener('resize', autoScale);
        autoScale();
    </script>
</body>
</html>
