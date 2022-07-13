<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PushNotificationsController extends Controller
{
    public function push(Request $request)
    {

        ini_set('max_excecution_time', -1);

        ini_set('memory_limit', -1);

        $request->validate([
            'subject' => 'required|string',
            'message' => 'required'
        ]);

        if(env('ONESIGNAL_APP_ID') =='' && env('ONESIGNAL_REST_API_KEY') == ''){
            
            return back()->with('error','Please update onesignal keys in settings !')->withInput();
        }

        try {

            $users = User::whereIn('id',$request->user_ids)->get();

            $data = [
                'subject' => $request->subject,
                'body' => $request->message,
                'target_url' => $request->target_url ?? null,
                'icon' => $request->icon ?? null,
                'image' => $request->image ?? null,
                'buttonChecked' => $request->show_button ? "yes" : "no",
                'button_text' => $request->btn_text ?? null,
                'button_url' => $request->btn_url ?? null,
            ];



            Notification::send($users, new OfferPushNotifications($data));

            
            return back()->with('success','Notification pushed successfully !');

        } catch (\Exception $e) {

            notify()->error($e->getMessage());
            return back()->withInput();

        }

    }

    public function usermarkreadsingle(Request $request){
        $a = isset($request['id']) ? $request['id'] : 'not yet';
        
        $userUnreadNotification = auth()->user()
            ->unreadNotifications
            ->where('id', $a)->first();

        if ($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
            return Response::json(['status' => 'success']);
        }
    }

    
}
