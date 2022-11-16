<p>Hi {{ $invite->name }}</p>

<p>You have been invited to be a co-host for '{{$event-> title }}'.</p>

<p>The password for joining the stream is as follows: {{$invite->token}}</p>
    
<p>To accept the invitation, please select one of the two options below: </p>

<ul>
    <li>New User Registration: <a href="{{ $registration_link }}">{{ $registration_link }}</a></li>
    <li>Returning User Login: <a href="{{ $login_link }}">{{ $login_link }}</a></li>
</ul>

<p>Please follow the instructions on the linked pages for joining as a co-host.</p>
