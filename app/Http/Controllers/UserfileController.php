<?php

namespace App\Http\Controllers;

use App\Models\Userfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max($request->integer('per_page', 15), 1), 100);
        $page = max($request->integer('page', 1), 1);
        $search = $request->query('search');

        $query = Userfile::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('usrcde', 'like', "%{$search}%")
                  ->orWhere('monitorsetup', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $users->items(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'last_page' => $users->lastPage(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'usrcde' => ['required', 'string', 'max:15', 'unique:userfile,usrcde'],
            'usrpwd' => ['required', 'string', 'min:1'],
            'monitorsetup' => ['required', 'string', 'in:fifo,manual'],
        ]);

        // Hash password using SHA1
        $data['usrpwd'] = sha1($data['usrpwd']);

        $userfile = Userfile::create($data);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $userfile,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $userfile = Userfile::find($id);

        if (!$userfile) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $userfile,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $userfile = Userfile::find($id);

        if (!$userfile) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $data = $request->validate([
            'usrpwd' => ['sometimes', 'string', 'min:1'],
            'monitorsetup' => ['sometimes', 'string', 'in:fifo,manual'],
        ]);

        // Hash password using SHA1 if provided
        if (isset($data['usrpwd'])) {
            $data['usrpwd'] = sha1($data['usrpwd']);
        }

        $userfile->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $userfile->fresh(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $userfile = Userfile::find($id);

        if (!$userfile) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        // Prevent deleting the currently logged-in user
        if (auth()->check() && auth()->user()->usrcde === $userfile->usrcde) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete your own account',
            ], 403);
        }

        $userfile->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }
}
