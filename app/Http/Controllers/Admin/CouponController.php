<?php
// app/Http/Controllers/CouponController.php
namespace App\Http\Controllers\Admin;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class CouponController extends Controller
{
    public function index()
    {
        $datas = Coupon::orderByDesc('id')->paginate(50);
        return view('admin.coupons.index', compact('datas'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Bulk generation inputs
            'number_of_coupons' => 'required|integer|min:1|max:10000',
            'discount'          => 'required|numeric|min:0',
            'expires_at'        => 'nullable|date',
            'prefix'            => 'nullable|string|max:10',
            'code_length'       => 'nullable|integer|min:4|max:32', // random part length
            'package_id'        => 'nullable|exists:packages,id',

        ]);

        $count      = (int) $validated['number_of_coupons'];
        $discount   = (float) $validated['discount'];
        $expiresAt  = $validated['expires_at'] ?? null;
        $prefix     = strtoupper($validated['prefix'] ?? '');
        $codeLength = (int) ($validated['code_length'] ?? 8);
        $packageId = $validated['package_id'] ?? null;

        // Generate unique codes (avoid collisions)
        $codes = $this->generateUniqueCodes($count, $codeLength, $prefix);

        $rows = [];
        $now = now();
        foreach ($codes as $code) {
            $rows[] = [
                'code'       => $code,
                'discount'   => $discount,
                'is_used'    => false,
                'expires_at' => $expiresAt,
                'package_id' => $packageId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('coupons')->insert($rows);

        return redirect()->route('coupons.index')
            ->with('success', "{$count} coupon(s) generated successfully.");
    }

    public function edit(Coupon $coupon)
    {
        $data = $coupon;
        return view('admin.coupons.edit', compact('data'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'discount'   => 'required|numeric|min:0',
            'expires_at' => 'nullable|date',
            'is_used'    => 'nullable|boolean',
        ]);

        $coupon->update([
            'discount'   => $validated['discount'],
            'expires_at' => $validated['expires_at'] ?? null,
            'is_used'    => (bool) ($validated['is_used'] ?? $coupon->is_used),
        ]);

        return redirect()->route('coupons.index')->with('success', 'Coupon updated.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Coupon deleted.');
    }

    /**
     * Generate unique coupon codes with optional prefix.
     */
    private function generateUniqueCodes(int $count, int $length = 8, string $prefix = ''): array
    {
        $generated = [];
        $existing  = Coupon::pluck('code')->toArray();
        $existingSet = array_fill_keys($existing, true);

        while (count($generated) < $count) {
            $code = $prefix . strtoupper(Str::random($length));
            if (!isset($existingSet[$code])) {
                $generated[] = $code;
                $existingSet[$code] = true;
            }
        }
        return $generated;
    }

    public function exportUnusedPdf(Request $request)
{
    $query = Coupon::with('package')
        ->where('is_used', false);

    // Optional: also exclude expired coupons
    if ($request->boolean('skip_expired', true)) {
        $query->where(function ($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
        });
    }

    // Optional filter by package (?package_id=1)
    if ($request->filled('package_id')) {
        $query->where('package_id', $request->query('package_id'));
    }

    $coupons = $query->orderByDesc('id')->get();

    $pdf = Pdf::loadView('admin.coupons.pdf', [
        'coupons'    => $coupons,
        'exportedAt' => now(),
        'package'    => $request->filled('package_id') ? Package::find($request->query('package_id')) : null,
    ])->setPaper('a4', 'portrait');

    $name = 'coupons_unused';
    if ($request->filled('package_id')) $name .= '_pkg_'.$request->query('package_id');
    $name .= '_'.now()->format('Ymd_His').'.pdf';

    return $pdf->download($name.'.pdf');
}

}
