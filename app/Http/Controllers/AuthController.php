<?php

namespace App\Http\Controllers;

use App\Models\KaryawanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function index() {
        return view('auth.login');
    }
    function login(Request $request) {
        $request->validate( 
            [
                'npk' => 'required',
                'password' => 'required'
            ],
            [
                'npk.required' => 'NPK wajib diisi',
                'password.required' => 'Password wajib diisi'
            ]
        );

         // Check if the npk exists in the karyawan table
         $karyawan = KaryawanModel::where('npk', $request->npk)->first();

         if (!$karyawan) {
             // If NPK is not found, return back with an error
             return redirect()->back()->withErrors('NPK tidak terdaftar')->withInput();
         };

        $infologin = [
            'npk' => $request->npk,
            'password' => $request->password,
        ];

        if(Auth::attempt($infologin)){
            if (Auth::user()->role == 'superadmin') {
                return redirect('menu/superadmin');
            } elseif (Auth::user()->role == 'admin') {
                return redirect('menu/admin');
            } elseif (Auth::user()->role == 'section_head') {
                return redirect('menu/sechead');
            } elseif (Auth::user()->role == 'department_head') {
                return redirect('menu/dephead');
            } elseif (Auth::user()->role == 'division_head') {
                return redirect('menu/divhead');
            } elseif (Auth::user()->role == 'hrd') {
                return redirect('menu/hrd');
            }
        }else {
            return redirect('')->withErrors('NPK dan password yang dimasukan tidak sesuai')->withInput();
        }
    }
    function logout(){
        Auth::logout();
        return redirect('/');
    }
}
