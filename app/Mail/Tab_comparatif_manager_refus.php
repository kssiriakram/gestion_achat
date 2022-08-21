<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Tab_comparatif_manager_refus extends  Mailable
{
    use Queueable, SerializesModels;
    public $societe;
    public $type;
    public $email;
    public $description;
    public $sujet;
    public $username;
    public $idDA;
    public $destinataire;
    public $observation_manager;

    /**
     * @param $societe
     * @param $type
     * @param $email
     * @param $description
     * @param $idDA
     */
    public function __construct($username, $societe, $type, $email, $description, $sujet , $idDA,$observation_manager)
    {
        $this->sujet = $sujet;

        $this->societe = $societe;
        $this->username = $username;
        $this->type = $type;
        $this->email = $email;

        $this->description=$description;
        $this->observation_manager=$observation_manager;
        $this->idDA=$idDA;

    }

    /**
     * Create a new message instance.
     *
     * @return void
     */

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->email, $name = "Gestion des achats COFMA")->
        subject($this->sujet)->markdown('tab_mailManager_refus');
    }
}
