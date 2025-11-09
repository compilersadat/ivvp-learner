<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use App\Services\InstituteUsbKeyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InstituteController extends Controller
{
    public function __construct(
        private InstituteUsbKeyService $usbKeyService,
    ) {
    }

    public function index()
    {
        $institutes = Institute::latest()->get();

        return view('admin.institutes.index', compact('institutes'));
    }

    public function create()
    {
        return view('admin.institutes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:institutes,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'usb_identifier' => ['nullable', 'string', 'max:255', 'unique:institutes,usb_identifier'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $institute = Institute::create($validated);

        $message = 'Institute created successfully.';

        if ($institute->usb_identifier) {
            $result = $this->usbKeyService->writeKeyFile($institute);

            if ($result['success'] && $result['path']) {
                $message .= ' USB key file saved to ' . $result['path'] . '.';
            } elseif (! $result['success'] && $result['message']) {
                $message .= ' USB key file could not be saved: ' . $result['message'];
            }
        }

        return redirect()
            ->route('institutes.index')
            ->with('success', $message);
    }

    public function edit(Institute $institute)
    {
        return view('admin.institutes.edit', compact('institute'));
    }

    public function update(Request $request, Institute $institute)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:institutes,email,' . $institute->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'usb_identifier' => ['nullable', 'string', 'max:255', 'unique:institutes,usb_identifier,' . $institute->id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if (empty($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        $institute->update($validated);

        return redirect()
            ->route('institutes.index')
            ->with('success', 'Institute updated successfully.');
    }

    public function delete(Institute $institute)
    {
        $institute->delete();

        return redirect()
            ->route('institutes.index')
            ->with('success', 'Institute deleted successfully.');
    }

    public function toggleStatus(Institute $institute)
    {
        $institute->is_active = ! $institute->is_active;
        $institute->save();

        $message = $institute->is_active ? 'Institute activated.' : 'Institute deactivated.';

        return redirect()
            ->route('institutes.index')
            ->with('success', $message);
    }

    public function generateUsbKey(): JsonResponse
    {
        do {
            $key = Str::upper(Str::uuid()->toString());
        } while (Institute::where('usb_identifier', $key)->exists());

        return response()->json([
            'key' => $key,
        ]);
    }
}
