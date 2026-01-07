<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Menampilkan halaman kontak.
     */
    public function index() 
    {
        return view('kontak');
    }

    /**
     * Menyimpan pesan pengguna ke database dan mengarahkan ke WhatsApp Admin.
     */
    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Simpan pesan ke Database
        Message::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        // Persiapkan Redirect ke WhatsApp
        $adminPhone = env('ADMIN_WA_NUMBER', '6281234567890'); 
        
        $waMessage  = "Halo Admin Smart Home, saya *" . $request->name . "*.\n\n";
        $waMessage .= "Subjek: " . $request->subject . "\n";
        $waMessage .= "Pesan: " . $request->message . "\n\n";
        $waMessage .= "Email saya: " . $request->email;

        $url = "https://wa.me/" . $adminPhone . "?text=" . urlencode($waMessage);

        return redirect()->away($url);
    }
}
