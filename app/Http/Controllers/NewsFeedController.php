<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsFeedController extends Controller
{
    public function index()
    {
        return view('feed.index');
    }
}
