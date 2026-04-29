<?php

namespace App\Exceptions;

use RuntimeException;
use Throwable;

/**
 * Exception métier de base. Porte un message technique (pour les logs)
 * ET un message utilisateur (pour l'affichage). Toutes les exceptions
 * domaine du projet doivent en hériter pour bénéficier de getUserMessage().
 */
abstract class BaseAppException extends RuntimeException
{
    protected string $userMessage;

    public function __construct(
        string $technicalMessage,
        string $userMessage,
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        $this->userMessage = $userMessage;
        parent::__construct($technicalMessage, $code, $previous);
    }

    /** Message destiné à l'utilisateur (sans données techniques). */
    public function getUserMessage(): string
    {
        return $this->userMessage;
    }
}
