<?php
namespace App\Facades;

use App\Models\Event;
use App\Models\User;

/**
 * @todo this entire classes is psuedo coded needs testing
 */
class StreamingFacade {


    public static function createFacebookUserStream(Event $event, User $user) {

        
        $fb = new \Facebook\Facebook([
            'app_id' => env('FACEBOOK_CLIENT_ID'),
            'app_secret' => env('FACEBOOK_CLIENT_SECRET'),
            'default_graph_version' => 'v2.10',
            //'default_access_token' => '{access-token}', // optional
          ]);
          

        try {
            // Returns a `FacebookFacebookResponse` object
            $response = $fb->post(
              "/{$user->facebook_id}/live_videos",
              array (
                'status' => 'LIVE_NOW',
                'title' => $event->title,
                'description' => $event->description
              ),
              $user->facebook_auth_token
            );
          } catch(FacebookExceptionsFacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(FacebookExceptionsFacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }

          $graphNode = $response->getGraphNode();

          EventsFacade::addRestream($event, $graphNode->stream_url);
    }

    public static function createTwitchStream(Event $event, User $user) {
        
    }

    public static function createYoutubeStream(Event $event, User $user) {
        
        $OAUTH2_CLIENT_ID = 'REPLACE_ME';
        $OAUTH2_CLIENT_SECRET = 'REPLACE_ME';

        $client = new Google_Client();
        $client->setClientId(env('YOUTUBE_CLIENT_ID'));
        $client->setClientSecret(env('YOUTUBE_CLIENT_SECRET'));
        $client->setScopes('https://www.googleapis.com/auth/youtube');
        $redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
            FILTER_SANITIZE_URL);
        $client->setRedirectUri($redirect);

        // Define an object that will be used to make all API requests.
        $youtube = new Google_Service_YouTube($client);

        $client->setAccessToken($user->youtube_auth_token);

        // Check to ensure that the access token was successfully acquired.
      
        try {
            // Create an object for the liveBroadcast resource's snippet. Specify values
            // for the snippet's title, scheduled start time, and scheduled end time.
            $broadcastSnippet = new Google_Service_YouTube_LiveBroadcastSnippet();
            $broadcastSnippet->setTitle('New Broadcast');
            $broadcastSnippet->setScheduledStartTime('2034-01-30T00:00:00.000Z');
            $broadcastSnippet->setScheduledEndTime('2034-01-31T00:00:00.000Z');

            // Create an object for the liveBroadcast resource's status, and set the
            // broadcast's status to "private".
            $status = new Google_Service_YouTube_LiveBroadcastStatus();
            $status->setPrivacyStatus('public');

            // Create the API request that inserts the liveBroadcast resource.
            $broadcastInsert = new Google_Service_YouTube_LiveBroadcast();
            $broadcastInsert->setSnippet($broadcastSnippet);
            $broadcastInsert->setStatus($status);
            $broadcastInsert->setKind('youtube#liveBroadcast');

            // Execute the request and return an object that contains information
            // about the new broadcast.
            $broadcastsResponse = $youtube->liveBroadcasts->insert('snippet,status',
            $broadcastInsert, array());

            // Create an object for the liveStream resource's snippet. Specify a value
            // for the snippet's title.
            $streamSnippet = new Google_Service_YouTube_LiveStreamSnippet();
            $streamSnippet->setTitle('New Stream');

            // Create an object for content distribution network details for the live
            // stream and specify the stream's format and ingestion type.
            $cdn = new Google_Service_YouTube_CdnSettings();
            $cdn->setFormat("1080p");
            $cdn->setIngestionType('rtmp');

            // Create the API request that inserts the liveStream resource.
            $streamInsert = new Google_Service_YouTube_LiveStream();
            $streamInsert->setSnippet($streamSnippet);
            $streamInsert->setCdn($cdn);
            $streamInsert->setKind('youtube#liveStream');

            // Execute the request and return an object that contains information
            // about the new stream.
            $streamsResponse = $youtube->liveStreams->insert('snippet,cdn',
                $streamInsert, array());

            // Bind the broadcast to the live stream.
            $bindBroadcastResponse = $youtube->liveBroadcasts->bind(
            $broadcastsResponse['id'],'id,contentDetails',
                array(
                    'streamId' => $streamsResponse['id'],
                ));

        } catch (Google_Service_Exception $e) {
            $htmlBody = sprintf('<p>A service error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
        } catch (Google_Exception $e) {
            $htmlBody = sprintf('<p>An client error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
        }
        

    }
}