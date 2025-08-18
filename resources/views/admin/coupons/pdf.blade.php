<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Coupons</title>
    <style>
        @page { margin: 16px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
        .header { margin-bottom: 6px; }
        .muted { color: #666; font-size: 10px; }
    
        .grid {
            display: flex;
            flex-wrap: wrap;
            margin: -4px; /* negative margin to balance child margins */
        }
        .card {
            flex: 0 0 33.33%;  /* exactly 3 per row */
            max-width: 33.33%;
            border: 1px dashed #777; border-radius: 6px;
            padding: 6px;
            margin: 4px; /* spacing between cards */
            box-sizing: border-box;
            min-height: 60px;
            display: flex; flex-direction: column; justify-content: space-between;
        }
    
        .code { font-size: 14px; font-weight: 700; letter-spacing: 0.5px; text-align: center; }
        .row { display: flex; justify-content: space-between; margin-top: 2px; font-size: 10px; }
        .label { font-weight: 600; }
        .footer { margin-top: 4px; font-size: 9px; color: #555; text-align: center; }
    
        .badge {
            display:inline-block; padding:1px 4px; border-radius:3px; font-size:9px; font-weight:700;
        }
        .badge-green { background:#e6f6ea; color:#137333; border:1px solid #b6e3c2; }
        .badge-red   { background:#fde8e8; color:#b10d0d; border:1px solid #f7c6c6; }
    </style>
    
    
</head>
<body>
    <div class="header">
        <div><strong>Coupons</strong>
            @if($package) for Package: <em>{{ $package->name }}</em>@endif
        </div>
        <div class="muted">Exported: {{ $exportedAt->format('Y-m-d H:i') }}</div>
    </div>

    <div class="grid">
        @forelse($coupons as $c)
            <div class="card">
                <div class="code">{{ $c->code }}</div>
                <div class="row">
                    <span><span class="label">Package:</span> {{ $c->package->name ?? '—' }}</span>
                </div>
            </div>
        @empty
            <p>No coupons found.</p>
        @endforelse
    </div>
</body>
</html>
