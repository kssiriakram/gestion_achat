@component('mail::message')
    # Votre demande d'achat est refusee

    De la part de :

    societe : {{$societe}}
    nom de l'utilisateur : {{$username}}
    email : {{$email}}
    observation du manager : {{$observation_manager}}

    {{$description}}


   Veuillez cliquez sur <a href="{{env('APP_URL')}}/nouvelledm/{{$idDA}}">ce lien </a> pour modifier la demande .

@endcomponent
