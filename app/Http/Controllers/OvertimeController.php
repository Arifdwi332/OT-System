<?php

namespace App\Http\Controllers;

use App\Models\DepartmentModel;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    public function index() {
        return view('overtime.index');
    }
    public function planning() {
        $departements = DepartmentModel::all(); // Ambil semua departemen
        return view('overtime.planning', compact('departements'));
    }
}
