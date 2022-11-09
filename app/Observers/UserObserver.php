<?php

namespace App\Observers;

use App\Invirtu\InvirtuClient;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        /**
         * After a user has been created, we want to sync with
         * Invirtu and get back the Auth Token.
         */

        $orgnaizder_id = env('INVIRTU_ORGANIZER_ID', '');

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        if($organizer_token && $organizer_token) {

            $client = new InvirtuClient($organizer_token);

            $data = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'organizer_id' => $orgnaizder_id
            ];

            $result = $client->auth->syncToOrganizer($data);


            if($result->status == 'success') {
                $user->forceFill([
                    'invirtu_user_id' => $result->data->id,
                    'invirtu_user_jwt_token' => $result->data->jwt->token,
                    'invirtu_user_jwt_issued'=> $result->data->jwt->issued_at,
                    'invirtu_user_jwt_expiration'=> $result->data->jwt->expiration,
                ]);

                $user->save();
            } else {
                Log::error('Unable to create Invirtu Account', (array)$result->errors);
            }
        }  else {
            Log::error('Both an invirtu organizer ID and token is required to create a user.');
        }
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
