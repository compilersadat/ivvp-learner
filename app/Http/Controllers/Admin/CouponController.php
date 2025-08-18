<?php
// app/Http/Controllers/CouponController.php
namespace App\Http\Controllers\Admin;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{
    public function index()
    {
        $datas = Coupon::orderByDesc('id')->paginate(50);
        return view('coupons.index', compact('datas'));
    }

    public function create()
    {
        return view('coupons.create');
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
        return view('coupons.edit', compact('data'));
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
}
