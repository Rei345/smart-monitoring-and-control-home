<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index() 
    {
        return view('kontak');
    }

    // Menyimpan pesan ke database
    public function send(Request $request)
    {
        // 1. Validasi Input (Wajib diisi)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // 2. Simpan ke Database
        Message::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        // 3. Redirect ke WhatsApp Admin
        // Nomor WA kamu/admin (Format: 628xxxx)
        $adminPhone = '6281376544633'; 
        
        // Susun pesan WA
        $waMessage = "Halo Admin Smart Home, saya *" . $request->name . "*.\n\n";
        $waMessage .= "Subjek: " . $request->subject . "\n";
        $waMessage .= "Pesan: " . $request->message . "\n\n";
        $waMessage .= "Email saya: " . $request->email;

        // Encode URL agar karakter spasi/enter terbaca
        $url = "https://wa.me/" . $adminPhone . "?text=" . urlencode($waMessage);

        // Redirect user ke WA (bukan kembali ke halaman kontak)
        return redirect()->away($url);
    }
}
