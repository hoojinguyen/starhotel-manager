<?php

namespace Romi\Controller\Admin;
use Anddye\Mailer\Mailable;


class SendMailController extends Mailable
{
    protected $user;

    public function __construct($user)
    {
        $user->dayCheckin = date_format(date_create($user->dayCheckin), 'd-m-Y');;
        $user->name  = strtoupper($user->name );
        $this->user = $user;
    }
    
    public function build()
    {
        $this->setSubject('Welcome to the Hotel!');
        $this->setView('/admin/mail.html.twig', [
            'user' => $this->user,
        ]);
        
        return $this;
    }

}

