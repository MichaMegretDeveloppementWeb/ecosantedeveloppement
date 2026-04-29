<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array{firstName: string, lastName: string, email: string, phone: ?string, creche: string, entryDate: ?string, message: string}  $data
     */
    public function __construct(
        public array $data,
        public ?TemporaryUploadedFile $attachment = null,
    ) {}

    public function envelope(): Envelope
    {
        $crecheLabel = $this->resolveCrecheLabel($this->data['creche']);

        return new Envelope(
            replyTo: [new Address($this->data['email'], "{$this->data['firstName']} {$this->data['lastName']}")],
            subject: "Nouvelle demande — {$crecheLabel}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            with: [
                'data' => $this->data,
                'crecheLabel' => $this->resolveCrecheLabel($this->data['creche']),
            ],
        );
    }

    /** @return array<int, Attachment> */
    public function attachments(): array
    {
        if (! $this->attachment) {
            return [];
        }

        return [
            Attachment::fromPath($this->attachment->getRealPath())
                ->as($this->attachment->getClientOriginalName())
                ->withMime('application/pdf'),
        ];
    }

    private function resolveCrecheLabel(string $slug): string
    {
        if ($slug === 'indecis') {
            return 'À déterminer';
        }

        return config("eco-sante.creches.{$slug}.name", $slug);
    }
}
