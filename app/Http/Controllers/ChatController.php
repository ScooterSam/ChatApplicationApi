<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Participant;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->user = \Auth::guard('api')->user();
    }

    /**
     * This will get all the users chats, maybe we should load
     * the messages later, otherwise we will be loading a lot of data.
     *
     * @param \Response $response
     * @return \Illuminate\Http\JsonResponse
     */
    public function chats()
    {

        $chatsParticipated = Chat::with(['participants', 'messages'])->whereHas('participants', function($value) {
            $value->where(['user_id' => $this->user->id]);
        })
            ->has('messages')
            ->get();


        return response()->json($chatsParticipated);

    }

    /**
     * Gets the messages for a specific chat
     *
     * @param Chat $chat
     * @return \Illuminate\Http\JsonResponse
     * @internal param $ * @param Chat $chat
     */
    public function messages(Chat $chat)
    {
        $messages = $chat->messages->map(function($value) {
            return [
                'id' => $value->id,
                'user_id' => $value->user_id,
                'message' => $value->message,
                'time' => $value->created_at->diffForHumans()
            ];
        });

        return response()->json($messages);
    }

    public function createChat(Request $request)
    {

        if(!$request->has('other_user'))
        {
            return response()->json(['message' => 'You have not selected a user to create a new chat with!'], 401);
        }

        $chat = new Chat();
        $chat->save();

        $participant1 = new Participant();
        $participant1->user_id = $this->user->id;

        $participant2 = new Participant();
        $participant2->user_id = $request->get('other_user');

        $chat->save([$participant1, $participant2]);

        return response()->json([
            'message' => 'Successfully created chat!',
            'chat' => $chat->with('participants')->get()
        ]);

    }

    /**
     * This will send a new message in a chat
     *
     * @param Request $request
     * @param Chat $chat
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request, Chat $chat)
    {
        if(!$chat)
        {
            return response()->json(['message' => 'This chat does not exist!', 401]);
        }

        if(!$chat->participants->where('user_id', $this->user->id)->count())
        {
            return response()->json(['message' => 'You cannot send a message in a chat you are not a part of.'], 401);
        }


        $message = new Message();
        $message->user_id = $this->user->id;
        $message->message = $request->get('message');
        $message->chat_id = $chat->id;
        $message->save();

        //@TODO: Send pusher message to the client about the new message

        return response()->json($message);

    }
}
