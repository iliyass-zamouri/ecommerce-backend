@component('mail::message')
    # Welcome to the Newsletter

    Dear {{ucfirst($user->first_name) .' '. ucfirst($user->last_name)}},

    We look forward to communicating more with you. For more information visit our blog.

    @component('mail::button', ['url' => 'https://localhost:8000/api/v1/unsubscribe'])
        Unsubscribe
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
