@component('mail::message')
    # Welcome to the Newsletter

    Dear {{$user->first_name == $user->email ? $user->email : $user->first_name .' '. $user->last_name}},

    We look forward to communicating more with you. For more information visit our blog.

    @component('mail::button', ['url' => 'https://localhost:8000/api/v1/unsubscribe'])
        Blog
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
