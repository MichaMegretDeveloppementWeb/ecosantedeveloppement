<?php

namespace App\Exceptions\Contact;

use App\Exceptions\BaseAppException;
use Throwable;

class ContactSendException extends BaseAppException
{
    public static function sendFailed(Throwable $e): self
    {
        return new self(
            technicalMessage: "Failed to send contact message. Error: {$e->getMessage()}",
            userMessage: "Impossible d'envoyer votre message pour le moment. Veuillez réessayer dans quelques instants ou nous contacter par téléphone.",
            previous: $e,
        );
    }
}
