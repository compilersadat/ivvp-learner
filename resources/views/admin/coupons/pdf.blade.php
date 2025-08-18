<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Coupons</title>
    <style>
        @page { margin: 24px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        .header { margin-bottom: 8px; }
        .muted { color: #666; font-size: 11px; }
        .grid {
            display: flex; flex-wrap: wrap;
            gap: 10px;
        }
        .card {
            width: calc(33.333% - 7px); /* 3 per row */
            border: 1px dashed #777; border-radius: 8px;
            padding: 12px; box-sizing: border-box; min-height: 120px;
            display: flex; flex-direction: column; justify-content: space-between;
        }
        .code { font-size: 18px; font-weight: 700; letter-spacing: 1px; }
        .row { display: flex; justify-content: space-between; margin-top: 4px; }
        .label { font-weight: 600; }
        .footer { margin-top: 6px; font-size: 10px; color: #555; }
        .badge {
            display:inline-block; padding:2px 6px; border-radius:4px; font-size:10px; font-weight:700;
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
                    <span><span class="label">Discount:</span> {{ number_format($c->discount, 2) }}</span>
                    <span>
                        @if($c->is_used)
                            <span class="badge badge-red">USED</span>
                        @else
                            <span class="badge badge-green">AVAILABLE</span>
                        @endif
                    </span>
                </div>
                <div class="row">
                    <span><span class="label">Expires:</span> {{ $c->expires_at ? $c->expires_at->format('Y-m-d') : '—' }}</span>
                    <span><span class="label">Package:</span> {{ $c->package->name ?? '—' }}</span>
                </div>
                <div class="footer">ID: {{ $c->id }}</div>
            </div>
        @empty
            <p>No coupons found.</p>
        @endforelse
    </div>
</body>
</html>
