<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Faculty;
use App\Models\StudyMaterialDocument;
use App\Models\StudyMaterialFolder;
use Illuminate\Http\Request;

class StudyMaterialController extends Controller
{
    public function index()
    {
        $folders = StudyMaterialFolder::withCount('documents')
            ->latest()
            ->get();

        return view('admin.study_materials.index', compact('folders'));
    }

    public function create()
    {
        $faculties = Faculty::all();

        return view('admin.study_materials.create', compact('faculties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'faculty_id' => ['required', 'string'],
            'branch_id' => ['required', 'string'],
            'year' => ['required', 'integer', 'min:1', 'max:10'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        StudyMaterialFolder::create($validated);

        return redirect()
            ->route('study-materials.index')
            ->with('success', 'Study material folder created successfully.');
    }

    public function edit(StudyMaterialFolder $studyMaterial)
    {
        $faculties = Faculty::all();
        $branches = Branch::where('wrtf', $studyMaterial->faculty_id)->get();
        $duration = Faculty::where('faculty_id', $studyMaterial->faculty_id)->value('duration') ?? 4;
        $years = range(1, (int) $duration);

        return view('admin.study_materials.edit', [
            'folder' => $studyMaterial,
            'faculties' => $faculties,
            'branches' => $branches,
            'years' => $years,
        ]);
    }

    public function update(Request $request, StudyMaterialFolder $studyMaterial)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'faculty_id' => ['required', 'string'],
            'branch_id' => ['required', 'string'],
            'year' => ['required', 'integer', 'min:1', 'max:10'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $studyMaterial->update($validated);

        return redirect()
            ->route('study-materials.index')
            ->with('success', 'Study material folder updated successfully.');
    }

    public function delete(StudyMaterialFolder $studyMaterial)
    {
        $studyMaterial->delete();

        return redirect()
            ->route('study-materials.index')
            ->with('success', 'Study material folder deleted successfully.');
    }

    public function documents(StudyMaterialFolder $studyMaterial)
    {
        $documents = $studyMaterial->documents()->latest()->get();

        return view('admin.study_materials.documents', [
            'folder' => $studyMaterial,
            'documents' => $documents,
        ]);
    }

    public function storeDocument(Request $request, StudyMaterialFolder $studyMaterial)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'file_url' => ['required', 'url', 'max:2048'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $studyMaterial->documents()->create($validated);

        return redirect()
            ->route('study-materials.documents', $studyMaterial)
            ->with('success', 'Document added successfully.');
    }

    public function deleteDocument(StudyMaterialDocument $document)
    {
        $folder = $document->folder;
        $document->delete();

        return redirect()
            ->route('study-materials.documents', $folder)
            ->with('success', 'Document removed successfully.');
    }
}
