<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    function index() {
        return view('dashboard');
    }
    function superadmin() {
        return view('dashboard');
        echo "<h1>". Auth::user()->nama ."</h1>";
    }
    function admin() {
        echo "masuk admin";
        echo "<h1>". Auth::user()->nama ."</h1>";
    }
    function sechead() {
        echo "masuk sechead";
        echo "<h1>". Auth::user()->nama ."</h1>";
    }
    function dephead() {
        echo "masuk dephead";
        echo "<h1>". Auth::user()->nama ."</h1>";
    }
    function divhead() {
        echo "masuk divisihead";
        echo "<h1>". Auth::user()->nama ."</h1>";
    }
    function hrd() {
        echo "masuk hrd";
        echo "<h1>". Auth::user()->nama ."</h1>";
    }
}
