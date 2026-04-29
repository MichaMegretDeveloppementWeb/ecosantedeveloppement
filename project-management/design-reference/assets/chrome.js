/* Header & Footer partagés - injection automatique
   Marque la page active selon data-page sur <body>. */

window.renderChrome = function() {
  const page = document.body.getAttribute('data-page') || '';
  const isActive = (p) => p === page ? ' class="active"' : '';

  const headerHTML = `
    <header class="site-header">
      <div class="container">
        <nav class="nav">
          <a href="index.html" class="nav-logo">
            <span style="width:44px;height:44px;display:inline-flex;" data-illu="logo"></span>
            <span>Eco Santé <em style="font-style:italic;font-weight:400;color:var(--rose-500);">Développement</em></span>
          </a>
          <div class="nav-links" id="navLinks">
            <a href="index.html"${isActive('home')}>Accueil</a>
            <a href="structures.html"${isActive('structures')}>Nos crèches</a>
            <a href="projet.html"${isActive('projet')}>Projet pédagogique</a>
            <a href="contact.html"${isActive('contact')}>Contact</a>
            <a href="contact.html" class="btn btn-primary" style="padding:12px 22px;">Inscription</a>
          </div>
          <button class="nav-toggle" id="navToggle" aria-label="Menu">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/></svg>
          </button>
        </nav>
      </div>
    </header>
  `;

  const footerHTML = `
    <footer class="site-footer">
      <div class="container">
        <div class="footer-grid">
          <div>
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:18px;">
              <span style="width:44px;height:44px;display:inline-flex;background:#fefcf7;border-radius:50%;padding:2px;" data-illu="logo"></span>
              <strong style="font-family:var(--font-display);font-size:20px;font-weight:500;color:var(--creme-50);">Eco Santé Développement</strong>
            </div>
            <p style="color:var(--ink-300);font-size:14px;line-height:1.6;max-width:32ch;">
              Trois micro-crèches privées qui accueillent vos tout-petits dans un cadre chaleureux, bienveillant et stimulant.
            </p>
          </div>
          <div>
            <h4>Nos crèches</h4>
            <a href="structures.html#amel-adam">Amel &amp; Adam · Deuil-la-Barre</a>
            <a href="structures.html#bea-benoit">Béa &amp; Benoit · Deuil-la-Barre</a>
            <a href="structures.html#chiara-hugo">Chiara &amp; Hugo · Bassens</a>
          </div>
          <div>
            <h4>Le projet</h4>
            <a href="projet.html">Projet pédagogique</a>
            <a href="projet.html#valeurs">Nos valeurs</a>
            <a href="projet.html#journee">Une journée type</a>
            <a href="projet.html#equipe">L'équipe</a>
          </div>
          <div>
            <h4>Contact</h4>
            <a href="tel:0666841669">06 66 84 16 69</a>
            <a href="mailto:ecosantedeveloppement@orange.fr">ecosantedeveloppement<br>@orange.fr</a>
            <a href="contact.html">Demande d'inscription</a>
            <a href="legal.html">Mentions légales</a>
          </div>
        </div>
        <div class="footer-bottom">
          <span>© 2026 Eco Santé Développement · Tous droits réservés</span>
          <span>Structures agréées PMI · Val d'Oise &amp; Savoie</span>
        </div>
      </div>
    </footer>
  `;

  // Insère header au début du body et footer à la fin
  const headerSlot = document.getElementById('site-header-slot');
  const footerSlot = document.getElementById('site-footer-slot');
  if (headerSlot) headerSlot.outerHTML = headerHTML;
  else document.body.insertAdjacentHTML('afterbegin', headerHTML);
  if (footerSlot) footerSlot.outerHTML = footerHTML;
  else document.body.insertAdjacentHTML('beforeend', footerHTML);

  // Mobile nav
  const toggle = document.getElementById('navToggle');
  if (toggle) {
    toggle.addEventListener('click', () => {
      document.getElementById('navLinks').classList.toggle('mobile-open');
    });
  }

  // Re-render illustrations after header/footer injection
  if (window.renderIllustrations) window.renderIllustrations();
};

if (document.readyState !== 'loading') window.renderChrome();
else document.addEventListener('DOMContentLoaded', window.renderChrome);
