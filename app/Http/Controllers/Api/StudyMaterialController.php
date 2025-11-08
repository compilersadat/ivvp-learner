<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ResponseController;
use App\Http\Resources\StudyMaterialFolderResource;
use App\Models\StudyMaterialFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudyMaterialController extends ResponseController
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => ['required', 'string'],
            'year' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $error = '';
            foreach ($errors as $messages) {
                foreach ($messages as $message) {
                    $error .= ' ' . $message;
                }
            }

            return $this->sendError(trim($error), 422);
        }

        $folders = StudyMaterialFolder::with(['documents' => function ($query) {
                $query->active();
            }])
            ->active()
            ->where('branch_id', $request->branch_id)
            ->where('year', $request->year)
            ->orderBy('name')
            ->get();

        $success['message'] = 'Study materials fetched.';
        $success['data'] = StudyMaterialFolderResource::collection($folders);

        return $this->sendResponse($success);
    }
}
