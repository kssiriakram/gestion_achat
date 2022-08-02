@component('mail::message')
    # Votre demande d'achat est refusee

    De la part de :

    societe : {{$societe}}
    nom de l'utilisateur : {{$username}}
    email : {{$email}}
    observation du manager : {{$observation_manager}}
    observation du directeur : {{$observation_directeur}}
    observation de l'acheteur : {{$observation_acheteur}}
    {{$description}}


   Veuillez cliquez sur <a href="{{env('APP_URL')}}/retourne_acheteur/{{$idDA}}">ce lien </a> pour modifier la demande .

@endcomponent
