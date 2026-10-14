<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // Menampilkan halaman chat beserta daftar pesan sebelumnya
    public function index()
    {
        $messages = Message::with('user')->latest()->take(50)->get()->reverse();
        return view('chat.index', compact('messages'));
    }

   public function store(Request $request)
{
    // 1. Validasi & simpan pesan ke database
    $validated = $request->validate([
        'message' => 'required|string|max:1000',
    ]);

    $message = Message::create([
        'user_id' => auth()->id(),
        'message' => $validated['message'],
    ]);

    // 2. WAJIB: Dispatch event ke Reverb
    broadcast(new MessageSent($message))->toOthers();

    // 3. Kembalikan response JSON untuk render instan di pengirim
    return response()->json([
        'message' => $message->load('user')
    ]);
}
}