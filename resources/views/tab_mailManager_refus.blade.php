@component('mail::message')
    # Votre table comparatif est refusee

    De la part de :

    societe : {{$societe}}
    nom de l'utilisateur : {{$username}}
    email : {{$email}}
    observation du manager : {{$observation_manager}}

    {{$description}}


   Veuillez cliquez sur <a href="{{env('APP_URL')}}/retourne_managers_tab/{{$idDA}}">ce lien </a> pour modifier la demande .

@endcomponent
