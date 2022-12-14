<p>Hi {{ $user->first_name }},</p>

<p>Your event has been scheduled for {{$competition->name}} as '{{$event-> title }}'. The current date/time of your match is {{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y, g:i a') }}.</p>

<p>This will be a live stream of your game that you can setup and broadcast to your friends and followers.</p>
    
<p>To access and setup your live stream, do the following:</p>

<ol>
    <li>Go to your streaming admin page at: <a href="{{ $event_admin_link }}">{{ $event_admin_link  }}</a>.
    <li>If it ask you to login, please login with the email/password associated with this email.</li>
    <li>Read the instructions for setting up your own RTMP stream.</li>
    <li>At the time of the match, share your full screen of your game and start the broadcast!</li>
    <li>Share this watch page with your friends and followers at <a href="{{ $event_watch_link }}">{{ $event_watch_link  }}</a>.</li>
</ol>

<p>Please reach out to the competition organizer if you have any questions.</p>
