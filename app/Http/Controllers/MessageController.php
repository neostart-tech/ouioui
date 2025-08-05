<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
     // recuperation de tous les messages
     public function index(){
        $messages = Message::all();
        return response()->json([
            "error" => false,
            "msg" => "L'ensemble des messages",
            "messages" => $messages
        ]);
    }

    // création des messages
    public function store(Request $request){
    //    dd($request->all());
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return \response()->json([
                "error" => true,
                "message" => $validator->getMessageBag()
            ], 500);
        }
        $message = new Message();
        $message->firstname = $request->firstname;
        $message->lastname = $request->lastname;
        $message->email = $request->email;
        $message->message = $request->message;
        $message->save();

        return response()->json([
            "error" => false,
            "msg" => "Message envoyé avec success"
        ]);
    }

    // afficher un message avec son id
    public function show($id){
        $message = Message::find($id);
        if (is_null($message)) {
            return response()->json([
                "error" => true,
                "msg" => "Element non retrouvé', 404"
            ]);
        }
        return response()->json([
            "error" => false,
            "message" => $message,
            "msg" => "est le message recupéré"
        ]);
    }

    // modifier un message
    public function update(Request $request , $id){
        $message = Message::find($id);
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required',
            'message' => 'required',
        ]);
        if ($validator->fails()) {
            return \response()->json([
                "error" => true,
                "message" => $validator->getMessageBag()
            ], 500);
        }
        $message->firstname = $request->firstname;
        $message->lastname = $request->lastname;
        $message->email = $request->email;
        $message->message = $request->message;
        $message->update();
    }

    // supprimer un message
    public function destroy($id){
        $message = Message::find($id);
        if($message != null){
            $message->delete();
            return response()->json([
                "msg" => "Message supprimé avec succès"
            ]);
        }
           else{
                return response()->json([
                    "error" => true,
                    "msg" => "Message non trouvé"
                ]);
            }
        }
}
