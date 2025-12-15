<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $capdateFrom = $request->query('capdate_from');
        $capdateTo = $request->query('capdate_to');

        $query = DB::table('capturefile');;

        if ($empcde !== null) {
            $query->where('empcde', $empcde);
        }

        if ($capdate !== null) {
            $query->whereDate('capdate', $capdate);
        } elseif ($capdateFrom !== null || $capdateTo !== null) {
            if ($capdateFrom !== null) {
                $query->whereDate('capdate', '>=', $capdateFrom);
            }
            if ($capdateTo !== null) {
                $query->whereDate('capdate', '<=', $capdateTo);
            }
        }

        if(Auth::user()->monitorsetup == 'manual') {
            $empcde_filter = DB::table('tablepar')
                ->where('usrcde', Auth::user()->usrcde)
                ->orderBy('gridorder')  // Before pluck()
                ->pluck('empcde')       // Get empcde values
                ->toArray();
            $query->whereIn('empcde', $empcde_filter ?? []);
        }

        $query->orderBy('capdate', 'desc')
            ->orderBy('captime');

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
