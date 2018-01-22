<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Status message.
     */
    const MESSAGE_SENT = 'Message Sent!';

    /**
     * ChatConrtoller constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show chat.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('chat');
    }

    /**
     * Fetch all messages.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function fetchMessages()
    {
        return Message::with('user')->get();
    }

    /**
     * Persist message to db.
     *
     * @param Request $request
     *
     * @return array
     */
    public function sendMessage(Request $request)
    {
        $user = auth()->user();
        $message = $user->messages()->create([
            'message' => $request->input('message'),
        ]);

        broadcast(new MessageSent($user, $message))->toOthers();

        return ['status' => self::MESSAGE_SENT];
    }
}
