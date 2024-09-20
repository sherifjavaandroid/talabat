<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\FirebaseMessagingTrait;
use Illuminate\Http\Request;
use App\Models\User;

class ChatNotificationController extends Controller
{
    use FirebaseMessagingTrait;
    //
    public function send(Request $request)
    {

        //
        // logger("Chat Request", [$request->all()]);
        $peer = $request->peer;
        if (!\Str::contains($request->peer['id'], 'vendor_')) {
            $peerUser = User::with('roles')->where('id', $request->peer['id'])->first();
            $peer["role"] = $peerUser->roles->first;
        } else {
            // $peer['id'] = str_ireplace("vendor_","v_",$peer['id']);
            $peer["role"] = "manager";
        }
        $peer = json_encode($peer);
        $topic = str_ireplace("vendor_", "v_", $request->topic);


        //
        try {
            $orderData = [
                'is_chat' => "1",
                'path' => $request->path,
                'user' => $peer,
                'peer' => json_encode($request->user),
                'title' => $request->title,
                'body' => $request->body,
            ];
            // logger("Chat Notification Data", $orderData);
            $this->sendOrderFirebaseNotification($topic, $request->title, $request->body, $orderData);

            //
            // logger("Chat Data", $orderData);
            return response()->json([
                "message" => __("Notification sent successfully")
            ], 200);
        } catch (\Exception $ex) {
            logger("Chat Error", [$ex]);
            return response()->json([
                "message" => $ex->getMessage() ?? __("Notification failed")
            ], 400);
        }
    }
}
