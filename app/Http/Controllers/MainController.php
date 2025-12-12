<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class MainController extends Controller
{
    public function index(Request $request)
    {
        $perPage = min(max($request->integer('per_page', 10), 1), 100);
        $page = max($request->integer('page', 1), 1);
        $empcde = $request->query('empcde');
        $capdate = $request->query('capdate');

        if (Schema::hasTable('capturefile')) {
            $query = DB::table('capturefile');

            if ($empcde !== null) {
                $query->where('empcde', $empcde);
            }

            if ($capdate !== null) {
                $query->whereDate('capdate', $capdate);
            }

            $rows = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $rows->items(),
                'meta' => [
                    'current_page' => $rows->currentPage(),
                    'per_page' => $rows->perPage(),
                    'total' => $rows->total(),
                    'last_page' => $rows->lastPage(),
                ],
            ]);
        } else {
            $sample = collect([
                ['message' => 'capturefile table not found, returning sample data'],
            ]);

            return response()->json([
                'success' => true,
                'data' => $sample,
                'meta' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $sample->count(),
                    'last_page' => 1,
                ],
            ]);
        }
    }

    public function showImage(Request $request)
    {
        $imageName = $request->input('scimagename');
        $directoryName = $request->input('scdirectoryname');

        $disk = Storage::disk('public');
        $defaultImage = 'default.png'; // place your fallback image at storage/app/public/default.png

        $imagePath = null;

        if ($imageName !== null && $directoryName !== null) {
            $safeName = basename($imageName);
            $safeDirectory = trim(str_replace(['\\', '..'], '/', $directoryName), '/');
            $candidate = $safeDirectory === '' ? $safeName : $safeDirectory . '/' . $safeName;

            if ($disk->exists($candidate)) {
                $imagePath = $disk->path($candidate);
            }
        }

        if ($imagePath === null) {
            if (!$disk->exists($defaultImage)) {
                return response()->json(['success' => false, 'message' => 'Default image not found'], 404);
            }

            $imagePath = $disk->path($defaultImage);
        }

        $mime = mime_content_type($imagePath) ?: 'application/octet-stream';

        return response()->file($imagePath, [
            'Content-Type' => $mime,
        ]);
    }
}
