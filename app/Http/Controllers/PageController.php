<?php

namespace App\Http\Controllers;

use App\Models\Lyrics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function home()
    {
        $user = auth()->user();
        if ($user) {
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

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Send email to site owner
        \Mail::raw(
            "Name: {$validated['name']}\nEmail: {$validated['email']}\nSubject: {$validated['subject']}\nMessage: {$validated['message']}",
            function ($message) use ($validated) {
                $message->to('jan842070@gmail.com')
                        ->subject('Contact Form: ' . $validated['subject'])
                        ->replyTo($validated['email'], $validated['name']);
            }
        );

        return redirect()->route('contact')->with('success', 'Thank you for your message. We will get back to you soon!');
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
