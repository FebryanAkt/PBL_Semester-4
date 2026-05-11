<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Get all messages involving the user
        $messages = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get();
            
        // Group by conversation partner
        $conversations = collect();
        foreach ($messages as $msg) {
            $partnerId = $msg->sender_id == $userId ? $msg->receiver_id : $msg->sender_id;
            if (!$conversations->has($partnerId)) {
                $partner = User::find($partnerId);
                if ($partner) {
                    $conversations->put($partnerId, [
                        'user' => $partner,
                        'last_message' => $msg,
                        'unread' => Message::where('sender_id', $partnerId)
                                        ->where('receiver_id', $userId)
                                        ->where('is_read', false)
                                        ->count()
                    ]);
                }
            }
        }
        
        return view('chat.index', compact('conversations'));
    }

    public function show($id, Request $request)
    {
        $userId = Auth::id();
        $partner = User::findOrFail($id);
        
        // Context Item if passed from "Chat Penjual" button
        $itemContext = null;
        if ($request->has('item_id')) {
            $itemContext = Item::find($request->item_id);
        }
        
        // Mark messages as read
        Message::where('sender_id', $partner->id)
            ->where('receiver_id', $userId)
            ->update(['is_read' => true]);
            
        $messages = Message::where(function($q) use ($userId, $partner) {
                $q->where('sender_id', $userId)->where('receiver_id', $partner->id);
            })->orWhere(function($q) use ($userId, $partner) {
                $q->where('sender_id', $partner->id)->where('receiver_id', $userId);
            })->orderBy('created_at', 'asc')->get();
            
        // AJAX polling response
        if ($request->ajax()) {
            return response()->json([
                'messages' => $messages->map(function($msg) use ($userId) {
                    return [
                        'id' => $msg->id,
                        'text' => $msg->message,
                        'is_mine' => $msg->sender_id == $userId,
                        'time' => $msg->created_at->format('H:i')
                    ];
                })
            ]);
        }
            
        return view('chat.show', compact('partner', 'messages', 'itemContext'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);
        
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $id,
            'message' => $request->message,
            'is_read' => false,
            'item_id' => $request->item_id ?? null
        ]);
        
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success', 
                'message' => [
                    'id' => $message->id,
                    'text' => $message->message,
                    'is_mine' => true,
                    'time' => $message->created_at->format('H:i')
                ]
            ]);
        }
        
        return back();
    }
}
