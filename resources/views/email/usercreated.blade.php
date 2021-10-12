Hello {{$user->first_name}}
Your account is successfully created. Click this link to verify your accout:
{{route('verify', $user->verified_token)}}