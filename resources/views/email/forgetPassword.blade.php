<p>Hi {{ $user->first_name }},</p>

<p>You are recieving this email because a password reset request has been made for your account. </p>

<p>If you not request to reset your password, ignore this email. If you need to reset your password, use the link below: </p>

<p><a href="{{$password_reset_url}}">{{$password_reset_url}}</a></p>

<p>Please follow the instrunctions on the reset password for resetting your password.</p>