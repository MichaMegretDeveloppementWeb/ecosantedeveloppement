<div>
    @if ($sent)
        <div class="form-success" role="status">
            <x-illu.coeur class="success-icon" />
            <strong>Merci, votre message est envoyé.</strong>
            <p>Nous vous recontactons sous 48h pour organiser une visite ou répondre à vos questions.</p>
        </div>
    @else
        <form class="contact-form" wire:submit="submit" novalidate>
            <h2>Écrivez-nous.</h2>
            <p class="muted mb-6">Tous les champs marqués <span class="required">*</span> sont obligatoires.</p>

            @error('contact-send-failed')
                <div class="form-success" style="background: var(--rose-50); border-color: var(--rose-200); margin-bottom: 24px;" role="alert">
                    <strong style="color: var(--rose-600);">Une erreur est survenue.</strong>
                    <p>{{ $message }}</p>
                </div>
            @enderror

            <div class="form-grid">
                <div class="field">
                    <label for="firstName">Prénom <span class="required">*</span></label>
                    <input id="firstName" type="text" wire:model.blur="firstName" placeholder="Camille"
                        class="@error('firstName') input-error @enderror">
                    @error('firstName') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div class="field">
                    <label for="lastName">Nom <span class="required">*</span></label>
                    <input id="lastName" type="text" wire:model.blur="lastName" placeholder="Martin"
                        class="@error('lastName') input-error @enderror">
                    @error('lastName') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div class="field">
                    <label for="email">Email <span class="required">*</span></label>
                    <input id="email" type="email" wire:model.blur="email" placeholder="vous@exemple.fr"
                        class="@error('email') input-error @enderror">
                    @error('email') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div class="field">
                    <label for="phone">Téléphone</label>
                    <input id="phone" type="tel" wire:model.blur="phone" placeholder="06 12 34 56 78"
                        class="@error('phone') input-error @enderror">
                    @error('phone') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="field mt-5">
                <label>Crèche d'intérêt <span class="required">*</span></label>
                <div class="radio-group" role="radiogroup">
                    @foreach ($creches as $c)
                        <label class="radio-card">
                            <input type="radio" wire:model.live="creche" value="{{ $c['slug'] }}" @checked($creche === $c['slug'])>
                            <span class="radio-content">
                                <span class="dot dot-{{ $c['palette'] }}"></span>
                                <strong>{{ $c['name'] }}</strong>
                                <small>{{ $c['city'] }} · {{ $c['department_code'] }}</small>
                            </span>
                        </label>
                    @endforeach
                    <label class="radio-card">
                        <input type="radio" wire:model.live="creche" value="indecis" @checked($creche === 'indecis')>
                        <span class="radio-content">
                            <span class="dot" style="background:var(--ink-300);"></span>
                            <strong>Je ne sais pas encore</strong>
                            <small>Conseillez-moi</small>
                        </span>
                    </label>
                </div>
                @error('creche') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="field mt-5">
                <label for="entryDate">Date d'entrée souhaitée</label>
                <select id="entryDate" wire:model.blur="entryDate"
                    class="select-month @error('entryDate') input-error @enderror">
                    <option value="">Choisissez un mois</option>
                    @foreach ($availableMonths as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('entryDate') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="field mt-5">
                <label for="message">Message <span class="required">*</span></label>
                <textarea id="message" rows="5" wire:model.blur="message"
                    placeholder="Parlez-nous de votre enfant, de vos besoins de garde, de vos questions..."
                    class="@error('message') input-error @enderror"></textarea>
                @error('message') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            {{-- Pièce jointe avec drag & drop natif via Alpine --}}
            <div class="field mt-5"
                 x-data="{
                     dragOver: false,
                     handleDrop(event) {
                         this.dragOver = false;
                         const file = event.dataTransfer.files[0];
                         if (!file) return;
                         const input = this.$refs.fileInput;
                         const dt = new DataTransfer();
                         dt.items.add(file);
                         input.files = dt.files;
                         input.dispatchEvent(new Event('change', { bubbles: true }));
                     },
                 }">
                <label>Pièce jointe : fiche de préinscription remplie (PDF)</label>

                <div class="upload-zone"
                     :class="{ 'drag': dragOver }"
                     @dragover.prevent="dragOver = true"
                     @dragleave.prevent="dragOver = false"
                     @drop.prevent="handleDrop($event)"
                     @click="if (!$refs.fileInput.files.length) $refs.fileInput.click()">

                    <input type="file" wire:model="attachment" accept="application/pdf" hidden x-ref="fileInput">

                    @if ($attachment)
                        <div class="upload-filled">
                            <span class="file-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            </span>
                            <div class="file-info">
                                <strong>{{ $attachment->getClientOriginalName() }}</strong>
                                <small>{{ number_format($attachment->getSize() / 1024, 0) }} Ko</small>
                            </div>
                            <button type="button" wire:click="removeAttachment" @click.stop class="remove-btn" aria-label="Supprimer le fichier">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                            </button>
                        </div>
                    @else
                        <div class="upload-empty">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 12 15 15"/></svg>
                            <strong>Glissez votre PDF ici, ou <span class="link-btn">parcourez vos fichiers</span></strong>
                            <small>PDF uniquement · 5 Mo max.</small>
                        </div>
                    @endif
                </div>

                <div wire:loading wire:target="attachment" class="muted" style="margin-top: 8px; font-size: 13px;">
                    Téléversement en cours…
                </div>
                @error('attachment') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="field-checkbox mt-5">
                <input type="checkbox" id="rgpd" wire:model="rgpd">
                <label for="rgpd">
                    J'accepte que mes informations soient utilisées pour traiter ma demande,
                    conformément à la <a href="{{ route('legal.index') }}">politique de confidentialité</a>.
                    <span class="required">*</span>
                </label>
            </div>
            @error('rgpd') <p class="field-error">{{ $message }}</p> @enderror

            <button type="submit" class="btn btn-primary mt-6 submit-btn" wire:loading.attr="disabled" wire:target="submit">
                <span wire:loading.remove wire:target="submit" class="flex items-center gap-2">
                    Envoyer ma demande
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </span>
                <span wire:loading wire:target="submit">Envoi en cours…</span>
            </button>
        </form>
    @endif
</div>
