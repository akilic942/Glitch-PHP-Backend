<?php

namespace App\Http\Controllers;

use App\Facades\WebhookInvirtuFacade;
use App\Models\Webhook;
use Exception;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    /**
     * Handle Webhooks From Invirtu/Bingewave
     *
     * @return \Illuminate\Http\Response
     */
    public function invirtuWebhook(Request $request)
    {
       

        $input = $request->all();

        if(isset($input['action']) && $input['action']) {

            $data = (isset($input['data'])) ? $input['data'] : null;


            if($data && is_array($data)) {
                $data = json_encode($data);
            }

            $webhook = Webhook::create([
                'incoming_outgoing' => 1,
                'action' => $input['action'],
                'data' => $data
            ]);

            try {
                WebhookInvirtuFacade::process($input['action'], $input['data']);
            } catch(Exception $e) {

            }

        }
        
        return response()->json('ok'); 
    }
}
