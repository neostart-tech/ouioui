<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\stripeMail;
use App\Models\Programme;
use Exception;

class StripeController extends Controller
{
    public string $code;
    public function stripe(Request $request){
        $stripe = new \Stripe\StripeClient(env('STRIPE_API_KEY') );

       $image_url = 'https://images.unsplash.com/photo-1613243555988-441166d4d6fd?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80';
        $checkout = $stripe->checkout->sessions->create([
            'success_url' => 'https://ouiouiacademy.ca/api/save/'.$request->id.'/'.$request->email.'/'.'1',
            'cancel_url' => 'https://ouiouiacademy.ca/api/save/'.$request->id.'/'.$request->email.'/'.'0',
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'cad',
                        'unit_amount' => $request->prix*100,
                        'product_data' => [
                            'name' => "Vous êtes sur le point de faire un paiement de:",
                            'images' => [$image_url],
                        ]
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
          ]);
          
        
        return $checkout;
    }
    

    public function save($id,$email,$status){
        
        $trans = new Transaction;
        $trans->prog_id = $id;
        $trans->id_charge_stripe = $status;
        $trans->date = Carbon::now();
        $trans->save();

        if($status ==1){
            $programme = Programme::find($id);
            $programme->status = 2;
            $programme->save();
        }
        
        $data = [
            'subject' => 'oui-oui Academy',
            'body' => $status == 1 ? 'paiement effectué avec succes' : 'echec'
        ];

        try {
            Mail::to($email)->send(new stripeMail($data));
        } catch (Exception $th) {
        }

        return redirect('https://ouiouiacademy.ca/succes');
    }
     
     // fonction mobile
     public function enregistrer($id,$email,$status){
        
        $trans = new Transaction;
        $trans->prog_id = $id;
        $trans->id_charge_stripe = $status;
        $trans->date = Carbon::now();
        $trans->save();

        if($status ==1){
            $programme = Programme::find($id);
            $programme->status = 2;
            $programme->save();
        }
        $data = [
            'subject' => 'oui-oui Academy',
            'body' => $status == 1 ? 'paiement effectué avec succes' : 'echec'
        ];

        try {
            Mail::to($email)->send(new stripeMail($data));
        } catch (Exception $th) {
        }
        return response()->json([
            "error" => false,
            "message" => "La liste des programmes",
            "transactions" =>$trans
        ]);
    }

}
