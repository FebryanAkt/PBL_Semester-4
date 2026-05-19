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
            ->with(['sender', 'receiver', 'item'])
            ->latest()
            ->get();
            
        // Group by conversation partner and item, so each product chat stays clear.
        $conversations = collect();
        foreach ($messages as $msg) {
            $partnerId = $msg->sender_id == $userId ? $msg->receiver_id : $msg->sender_id;
            $conversationKey = $partnerId . ':' . ($msg->item_id ?: 'general');

            if (!$conversations->has($conversationKey)) {
                $partner = User::find($partnerId);
                if ($partner) {
                    $unreadQuery = Message::where('sender_id', $partnerId)
                        ->where('receiver_id', $userId)
                        ->where('is_read', false);

                    if ($msg->item_id) {
                        $unreadQuery->where('item_id', $msg->item_id);
                    } else {
                        $unreadQuery->whereNull('item_id');
                    }

                    $conversations->put($conversationKey, [
                        'user' => $partner,
                        'item' => $msg->item,
                        'last_message' => $msg,
                        'unread' => $unreadQuery->count(),
                    ]);
                }
            }
        }
        
        return view('chat.index', compact('conversations'));
    }

    public function show($id, Request $request)
    {
        $userId = Auth::id();
        abort_if((int) $id === (int) $userId, 403);

        $partner = User::findOrFail($id);
        
        // Context item if passed from "Chat Penjual" button or conversation list.
        $itemContext = null;
        if ($request->filled('item_id')) {
            $itemContext = Item::findOrFail($request->item_id);
            abort_if(
                (int) $itemContext->user_id !== (int) $userId
                && (int) $itemContext->user_id !== (int) $partner->id,
                403
            );
        }
        
        // Mark messages as read
        $readQuery = Message::where('sender_id', $partner->id)
            ->where('receiver_id', $userId)
            ->when($itemContext, fn ($query) => $query->where('item_id', $itemContext->id));

        $readQuery->update(['is_read' => true]);
            
        $messages = Message::where(function ($query) use ($userId, $partner) {
                $query->where(function($q) use ($userId, $partner) {
                    $q->where('sender_id', $userId)->where('receiver_id', $partner->id);
                })->orWhere(function($q) use ($userId, $partner) {
                    $q->where('sender_id', $partner->id)->where('receiver_id', $userId);
                });
            })
            ->when($itemContext, fn ($query) => $query->where('item_id', $itemContext->id))
            ->orderBy('created_at', 'asc')
            ->get();
            
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
            'message' => 'required|string|max:1000',
            'item_id' => 'nullable|exists:items,id',
        ]);

        abort_if((int) $id === (int) Auth::id(), 403);

        $receiver = User::findOrFail($id);
        $itemId = null;

        if ($request->filled('item_id')) {
            $item = Item::findOrFail($request->item_id);
            abort_if(
                (int) $item->user_id !== (int) Auth::id()
                && (int) $item->user_id !== (int) $receiver->id,
                403
            );

            $itemId = $item->id;
        }
        
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver->id,
            'message' => $request->message,
            'is_read' => false,
            'item_id' => $itemId,
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
