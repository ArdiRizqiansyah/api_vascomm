<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ApiService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // ambil semua data user kecuali yang sudah dihapus
        $users = app('db')->select("SELECT * FROM users WHERE deleted_at IS NULL");

        // jika ada request dengan query search
        if (request()->has('search')) {
            // ambil data user berdasarkan query search
            $users = app('db')->select("SELECT * FROM users WHERE name LIKE '%" . request()->search . "%' AND deleted_at IS NULL");
        }

        $data = [
            'users' => $users,
        ];

        $apiService = new ApiService();

        return $apiService->response($data, 'List All Users', 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = app('db')->select("SELECT * FROM roles");

        $data = [
            'roles' => $roles,
        ];

        $apiService = new ApiService();

        return $apiService->response($data, 'List All Roles', 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validasi data
        $this->validate(
            $request,
            [
                'name' => 'required',
                'email' => 'required|email',
                'role_id' => 'required',
            ]
        );

        $apiService = new ApiService();

        // cek apakah role ada di database
        $role = app('db')->select("SELECT * FROM roles WHERE id = '$request->role_id'");

        if (!$role) {
            return $apiService->error('Role tidak ditemukan', 404);
        }

        //  simpan data user
        $user = app('db')->table('users')->insertGetId(
            [
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $role[0]->id,
            ]
        );

        $data = [
            'user' => $user,
        ];

        return $apiService->response($data, 'User berhasil ditambahkan', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // validasi data
        $apiService = new ApiService();

        //  simpan data user
        $user = app('db')->select("SELECT * FROM users WHERE id = $id");

        if (!$user) {
            return $apiService->error('User tidak ditemukan', 404);
        }

        $roles = app('db')->select("SELECT * FROM roles");

        $data = [
            'user' => $user[0],
            'roles' => $roles,
        ];

        return $apiService->response($data, 'User berhasil dihapus', 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validasi data
        $this->validate(
            $request,
            [
                'name' => 'required',
                'email' => 'required|email',
                'role_id' => 'required',
            ],
            [
                'name.required' => 'Nama wajib diisi',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'role_id.required' => 'Role wajib diisi',
            ]
        );

        $apiService = new ApiService();

        // cek apakah role ada di database
        $role = app('db')->select("SELECT * FROM roles WHERE id = '$request->role_id'");

        if (!$role[0]) {
            return $apiService->error('Role tidak ditemukan', 404);
        }

        //  simpan data user
        $user = app('db')->select("SELECT * FROM users WHERE id = $id");

        if (!$user) {
            return $apiService->error('User tidak ditemukan', 404);
        }

        // update data user
        $user = app('db')->table('users')->where('id', $id)->update(
            [
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $role[0]->id,
            ]
        );

        $data = [
            'user' => $user,
        ];

        return $apiService->response($data, 'User berhasil diupdate', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $apiService = new ApiService();

        //  cari user
        $user = app('db')->select("SELECT * FROM users WHERE id = $id");

        if (!$user) {
            return $apiService->error('User tidak ditemukan', 404);
        }

        // hapus data user menggunakan soft delete
        $user = app('db')->table('users')->where('id', $id)->update([
            'deleted_at' => date('Y-m-d H:i:s'),
        ]);

        return $apiService->response(null, 'User berhasil dihapus', 200);
    }
}
