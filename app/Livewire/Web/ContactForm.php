<?php

namespace App\Livewire\Web;

use App\Actions\Contact\SendContactMessageAction;
use App\Exceptions\BaseAppException;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class ContactForm extends Component
{
    use WithFileUploads;

    public string $firstName = '';

    public string $lastName = '';

    public string $email = '';

    public string $phone = '';

    public string $creche = '';

    public string $entryDate = '';

    public string $message = '';

    public bool $rgpd = false;

    /**
     * Pièce jointe optionnelle. WithFileUploads transforme le fichier
     * uploadé en TemporaryUploadedFile, qu'on transmet à l'Action.
     *
     * @var TemporaryUploadedFile|null
     */
    public $attachment = null;

    /** Affichage de l'écran de succès après envoi. */
    public bool $sent = false;

    public function mount(): void
    {
        // Pré-sélection de la crèche via ?creche=amel-adam, ?creche=bea-benoit...
        $requested = request()->query('creche');
        if (! is_string($requested)) {
            return;
        }

        $allowed = array_keys(config('eco-sante.creches', []));
        $allowed[] = 'indecis';

        if (in_array($requested, $allowed, true)) {
            $this->creche = $requested;
        }
    }

    /** @return array<string, array<string>|string> */
    protected function rules(): array
    {
        $crecheValues = array_keys(config('eco-sante.creches', []));
        $crecheValues[] = 'indecis';

        return [
            'firstName' => ['required', 'string', 'min:2', 'max:80'],
            'lastName' => ['required', 'string', 'min:2', 'max:80'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'creche' => ['required', 'in:'.implode(',', $crecheValues)],
            'entryDate' => ['nullable', 'string', 'regex:/^\d{4}-\d{2}$/'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'rgpd' => ['accepted'],
            'attachment' => ['nullable', 'file', 'mimes:pdf', 'max:5120'], // 5 Mo
        ];
    }

    /** @return array<string, string> */
    protected function messages(): array
    {
        return [
            'firstName.required' => 'Le prénom est obligatoire.',
            'firstName.min' => 'Le prénom doit contenir au moins 2 caractères.',
            'firstName.max' => 'Le prénom ne peut pas dépasser 80 caractères.',
            'lastName.required' => 'Le nom est obligatoire.',
            'lastName.min' => 'Le nom doit contenir au moins 2 caractères.',
            'lastName.max' => 'Le nom ne peut pas dépasser 80 caractères.',
            'email.required' => "L'adresse email est obligatoire.",
            'email.email' => "L'adresse email n'est pas valide.",
            'email.max' => "L'adresse email ne peut pas dépasser 255 caractères.",
            'phone.max' => 'Le numéro de téléphone ne peut pas dépasser 30 caractères.',
            'creche.required' => 'Veuillez sélectionner une crèche.',
            'creche.in' => 'La crèche sélectionnée est invalide.',
            'entryDate.regex' => "La date d'entrée doit être au format AAAA-MM.",
            'message.required' => 'Le message est obligatoire.',
            'message.min' => 'Le message doit contenir au moins 10 caractères.',
            'message.max' => 'Le message ne peut pas dépasser 5000 caractères.',
            'rgpd.accepted' => 'Vous devez accepter la politique de confidentialité.',
            'attachment.file' => 'La pièce jointe est invalide.',
            'attachment.mimes' => 'La pièce jointe doit être un fichier PDF.',
            'attachment.max' => 'La pièce jointe ne doit pas dépasser 5 Mo.',
        ];
    }

    public function removeAttachment(): void
    {
        $this->attachment = null;
        $this->resetErrorBag('attachment');
    }

    public function submit(SendContactMessageAction $action): void
    {
        try {
            $validated = $this->validate();
        } catch (ValidationException $e) {
            // Scroll vers le premier champ en erreur pour aider l'utilisateur
            // à comprendre ce qui ne va pas (le formulaire est long).
            $this->js("setTimeout(() => document.querySelector('.input-error, .field-error')?.scrollIntoView({behavior:'smooth', block:'center'}), 80);");
            throw $e;
        }

        try {
            $action->execute(
                data: [
                    'firstName' => $validated['firstName'],
                    'lastName' => $validated['lastName'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'creche' => $validated['creche'],
                    'entryDate' => $validated['entryDate'] ?? null,
                    'message' => $validated['message'],
                ],
                attachment: $this->attachment,
            );

            $this->sent = true;
            // Scroll vers le bloc de confirmation : sans cela, l'utilisateur
            // peut ne pas voir le succès (formulaire long, page scrollée).
            $this->js("setTimeout(() => document.querySelector('.form-success')?.scrollIntoView({behavior:'smooth', block:'center'}), 100);");
        } catch (BaseAppException $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $this->addError('contact-send-failed', $e->getUserMessage());
            $this->js("setTimeout(() => document.querySelector('[role=alert]')?.scrollIntoView({behavior:'smooth', block:'center'}), 80);");
        }
    }

    /**
     * Liste des 24 prochains mois pour le sélecteur "Date d'entrée souhaitée",
     * indexée par valeur ISO `YYYY-MM` et libellée en français (« Septembre 2026 »).
     *
     * @return array<string, string>
     */
    private function availableMonths(): array
    {
        $months = [];
        $start = Carbon::now()->locale('fr')->startOfMonth();

        for ($i = 0; $i < 24; $i++) {
            $date = $start->copy()->addMonths($i);
            $months[$date->format('Y-m')] = ucfirst($date->isoFormat('MMMM YYYY'));
        }

        return $months;
    }

    public function render(): View
    {
        return view('livewire.web.contact-form', [
            'creches' => config('eco-sante.creches'),
            'availableMonths' => $this->availableMonths(),
        ]);
    }
}
