@component('mail::message')
    # Nouvelle demande d'achat est recu

    De la part de :

    societe : {{$societe}}
    nom de l'utilisateur : {{$username}}
    type : {{$type}}
    email : {{$email}}


    {{$description}}


   Veuillez cliquez sur <a href="http://192.168.43.48/gestion_achat/public/da_manager/{{$idDA}}">ce lien </a> pour valider la demande .

@endcomponent
