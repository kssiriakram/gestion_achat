@component('mail::message')
    # Votre demande d'achat est refusee

    De la part de :

    societe : {{$societe}}
    nom de l'utilisateur : {{$username}}
    email : {{$email}}
    observation du manager : {{$observation_manager}}
    observation du directeur : {{$observation_directeur}}

    {{$description}}


   Veuillez cliquez sur <a href="{{env('APP_URL')}}/retourne_directeur/{{$idDA}}">ce lien </a> pour modifier la demande .

@endcomponent
