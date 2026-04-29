<?php

namespace App\Actions\Contact;

use App\Exceptions\Contact\ContactSendException;
use App\Mail\ContactMessageMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Throwable;

/**
 * Compose le mail de demande de contact et l'envoie au destinataire
 * configuré dans config/eco-sante.contact.recipient_email.
 */
class SendContactMessageAction
{
    /**
     * @param  array{firstName: string, lastName: string, email: string, phone: ?string, creche: string, entryDate: ?string, message: string}  $data
     */
    public function execute(array $data, ?TemporaryUploadedFile $attachment = null): void
    {
        try {
            Mail::to(config('eco-sante.contact.recipient_email'))
                ->send(new ContactMessageMail($data, $attachment));
        } catch (Throwable $e) {
            throw ContactSendException::sendFailed($e);
        }
    }
}
