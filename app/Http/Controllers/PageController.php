<?php

namespace App\Http\Controllers;

use App\Models\Lyrics;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user && $user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard');
        }
        return view('welcome', [
            'lyrics' => Lyrics::latest()->paginate(9)
        ]);
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }
}
