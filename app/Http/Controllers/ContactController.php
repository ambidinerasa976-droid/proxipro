<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Show contact form
     */
    public function index()
    {
        return view('contact.index');
    }

    /**
     * Store contact message
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:5000',
        ]);

        $contactMessage = ContactMessage::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        // Optionally send email notification to admin
        // Mail::to(config('mail.admin_email'))->send(new NewContactMessage($contactMessage));

        return redirect()->route('contact.index')->with('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
    }

    /**
     * Show user's messages (for authenticated users)
     */
    public function myMessages()
    {
        $messages = ContactMessage::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('contact.my-messages', compact('messages'));
    }
}
