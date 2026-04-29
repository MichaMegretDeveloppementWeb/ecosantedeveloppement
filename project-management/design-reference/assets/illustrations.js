/* Bibliothèque d'illustrations SVG inline réutilisables.
   Toutes les illustrations utilisent currentColor et les couleurs CSS
   pour pouvoir être teintées via les variables de couleur du design system. */

window.IconLibrary = {

  soleil: `<svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <circle cx="50" cy="50" r="20" fill="#efce5e"/>
    <g stroke="#efce5e" stroke-width="3" stroke-linecap="round">
      <line x1="50" y1="12" x2="50" y2="22"/>
      <line x1="50" y1="78" x2="50" y2="88"/>
      <line x1="12" y1="50" x2="22" y2="50"/>
      <line x1="78" y1="50" x2="88" y2="50"/>
      <line x1="23" y1="23" x2="30" y2="30"/>
      <line x1="70" y1="70" x2="77" y2="77"/>
      <line x1="77" y1="23" x2="70" y2="30"/>
      <line x1="30" y1="70" x2="23" y2="77"/>
    </g>
    <circle cx="44" cy="48" r="2" fill="#2b2521"/>
    <circle cx="56" cy="48" r="2" fill="#2b2521"/>
    <path d="M44 56 Q50 60 56 56" stroke="#2b2521" stroke-width="2" stroke-linecap="round" fill="none"/>
  </svg>`,

  nuage: `<svg viewBox="0 0 120 80" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M30 60 Q15 60 15 45 Q15 30 30 32 Q32 18 50 22 Q60 10 75 22 Q95 18 95 38 Q108 40 105 55 Q105 65 90 65 L35 65 Q30 65 30 60 Z"
      fill="#dcecf5" stroke="#94c2dc" stroke-width="2"/>
  </svg>`,

  fleur: `<svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <ellipse cx="50" cy="22" rx="14" ry="20" fill="#f6d0c7"/>
    <ellipse cx="78" cy="50" rx="20" ry="14" fill="#f6d0c7"/>
    <ellipse cx="50" cy="78" rx="14" ry="20" fill="#f6d0c7"/>
    <ellipse cx="22" cy="50" rx="20" ry="14" fill="#f6d0c7"/>
    <circle cx="50" cy="50" r="12" fill="#efce5e"/>
    <circle cx="46" cy="48" r="2" fill="#2b2521"/>
    <circle cx="54" cy="48" r="2" fill="#2b2521"/>
    <path d="M45 54 Q50 57 55 54" stroke="#2b2521" stroke-width="1.5" stroke-linecap="round" fill="none"/>
  </svg>`,

  feuille: `<svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M20 80 Q20 30 50 15 Q80 30 80 60 Q70 85 45 85 Q28 85 20 80 Z"
      fill="#c3d8c5" stroke="#74a079" stroke-width="2"/>
    <path d="M20 80 Q40 60 65 35" stroke="#74a079" stroke-width="2" stroke-linecap="round" fill="none"/>
    <path d="M30 70 Q40 65 48 58" stroke="#74a079" stroke-width="1.5" stroke-linecap="round" fill="none"/>
    <path d="M40 78 Q50 70 55 60" stroke="#74a079" stroke-width="1.5" stroke-linecap="round" fill="none"/>
  </svg>`,

  coeur: `<svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M50 82 C20 62 18 38 30 28 C42 18 50 30 50 38 C50 30 58 18 70 28 C82 38 80 62 50 82 Z"
      fill="#efb1a3" stroke="#d97058" stroke-width="2"/>
  </svg>`,

  etoile: `<svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M50 12 L60 40 L88 42 L66 60 L74 88 L50 72 L26 88 L34 60 L12 42 L40 40 Z"
      fill="#faf0cc" stroke="#e8b938" stroke-width="2" stroke-linejoin="round"/>
  </svg>`,

  maison: `<svg viewBox="0 0 120 100" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M20 50 L60 18 L100 50 L100 88 Q100 92 96 92 L24 92 Q20 92 20 88 Z"
      fill="#fbe8e3" stroke="#d97058" stroke-width="2.5"/>
    <path d="M14 52 L60 14 L106 52" stroke="#b85540" stroke-width="2.5" stroke-linecap="round" fill="none"/>
    <rect x="50" y="60" width="20" height="32" rx="2" fill="#efce5e" stroke="#9c771b" stroke-width="2"/>
    <rect x="30" y="58" width="14" height="14" rx="2" fill="#dcecf5" stroke="#4a8bb4" stroke-width="2"/>
    <rect x="76" y="58" width="14" height="14" rx="2" fill="#dcecf5" stroke="#4a8bb4" stroke-width="2"/>
    <rect x="78" y="22" width="10" height="14" fill="#d97058"/>
    <ellipse cx="83" cy="22" rx="5" ry="3" fill="#d97058"/>
  </svg>`,

  enfant: `<svg viewBox="0 0 100 120" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <ellipse cx="50" cy="110" rx="32" ry="6" fill="#000" opacity="0.05"/>
    <circle cx="50" cy="32" r="22" fill="#fbe8e3" stroke="#2b2521" stroke-width="2"/>
    <path d="M30 28 Q35 14 50 12 Q65 14 70 28" fill="#4a423d"/>
    <circle cx="42" cy="34" r="2" fill="#2b2521"/>
    <circle cx="58" cy="34" r="2" fill="#2b2521"/>
    <path d="M44 42 Q50 46 56 42" stroke="#2b2521" stroke-width="1.5" stroke-linecap="round" fill="none"/>
    <circle cx="36" cy="38" r="2.5" fill="#efb1a3" opacity="0.6"/>
    <circle cx="64" cy="38" r="2.5" fill="#efb1a3" opacity="0.6"/>
    <path d="M28 60 Q50 52 72 60 L70 100 Q70 104 66 104 L34 104 Q30 104 30 100 Z"
      fill="#efb1a3" stroke="#2b2521" stroke-width="2"/>
    <path d="M30 70 L20 88" stroke="#2b2521" stroke-width="2" stroke-linecap="round"/>
    <path d="M70 70 L80 88" stroke="#2b2521" stroke-width="2" stroke-linecap="round"/>
  </svg>`,

  blocs: `<svg viewBox="0 0 120 100" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <rect x="10" y="60" width="32" height="32" rx="3" fill="#94c2dc" stroke="#3a6f93" stroke-width="2"/>
    <text x="26" y="82" font-family="Nunito,sans-serif" font-weight="700" font-size="20" fill="#2f5874" text-anchor="middle">A</text>
    <rect x="46" y="60" width="32" height="32" rx="3" fill="#efb1a3" stroke="#b85540" stroke-width="2"/>
    <text x="62" y="82" font-family="Nunito,sans-serif" font-weight="700" font-size="20" fill="#934234" text-anchor="middle">B</text>
    <rect x="82" y="60" width="32" height="32" rx="3" fill="#efce5e" stroke="#9c771b" stroke-width="2"/>
    <text x="98" y="82" font-family="Nunito,sans-serif" font-weight="700" font-size="20" fill="#9c771b" text-anchor="middle">C</text>
    <rect x="28" y="28" width="32" height="32" rx="3" fill="#c3d8c5" stroke="#57845d" stroke-width="2"/>
    <rect x="64" y="28" width="32" height="32" rx="3" fill="#f6d0c7" stroke="#d97058" stroke-width="2"/>
    <rect x="46" y="-4" width="32" height="32" rx="3" fill="#dcecf5" stroke="#4a8bb4" stroke-width="2" transform="translate(0,4)"/>
  </svg>`,

  livre: `<svg viewBox="0 0 120 100" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M10 22 Q40 16 60 24 Q80 16 110 22 L110 86 Q80 80 60 88 Q40 80 10 86 Z"
      fill="#fefcf7" stroke="#2b2521" stroke-width="2.5"/>
    <line x1="60" y1="24" x2="60" y2="88" stroke="#2b2521" stroke-width="2"/>
    <path d="M18 32 L52 28 M18 42 L52 38 M18 52 L52 48 M18 62 L48 58"
      stroke="#94c2dc" stroke-width="2" stroke-linecap="round"/>
    <path d="M68 28 L102 32 M68 38 L102 42 M68 48 L102 52 M68 58 L98 62"
      stroke="#efb1a3" stroke-width="2" stroke-linecap="round"/>
  </svg>`,

  ballon: `<svg viewBox="0 0 100 120" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <ellipse cx="50" cy="42" rx="32" ry="36" fill="#efb1a3" stroke="#b85540" stroke-width="2.5"/>
    <ellipse cx="38" cy="32" rx="8" ry="12" fill="#fbe8e3" opacity="0.6"/>
    <path d="M50 78 L48 86 L52 86 Z" fill="#b85540"/>
    <path d="M50 86 Q42 96 50 110 Q58 96 50 86" stroke="#7a6f68" stroke-width="1.5" stroke-linecap="round" fill="none"/>
  </svg>`,

  papillon: `<svg viewBox="0 0 120 100" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <path d="M60 50 Q40 20 18 28 Q8 38 18 52 Q24 62 40 60 Q56 58 60 50 Z"
      fill="#f6d0c7" stroke="#d97058" stroke-width="2"/>
    <path d="M60 50 Q40 80 22 76 Q14 70 18 60 Q26 56 40 60 Q56 62 60 50 Z"
      fill="#fbe8e3" stroke="#d97058" stroke-width="2"/>
    <path d="M60 50 Q80 20 102 28 Q112 38 102 52 Q96 62 80 60 Q64 58 60 50 Z"
      fill="#f6d0c7" stroke="#d97058" stroke-width="2"/>
    <path d="M60 50 Q80 80 98 76 Q106 70 102 60 Q94 56 80 60 Q64 62 60 50 Z"
      fill="#fbe8e3" stroke="#d97058" stroke-width="2"/>
    <ellipse cx="60" cy="50" rx="3" ry="14" fill="#2b2521"/>
    <circle cx="58" cy="38" r="2" fill="#2b2521"/>
    <circle cx="62" cy="38" r="2" fill="#2b2521"/>
    <path d="M58 36 Q54 28 52 24" stroke="#2b2521" stroke-width="1.5" stroke-linecap="round" fill="none"/>
    <path d="M62 36 Q66 28 68 24" stroke="#2b2521" stroke-width="1.5" stroke-linecap="round" fill="none"/>
  </svg>`,

  vague: `<svg viewBox="0 0 1200 60" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="none">
    <path d="M0 30 Q150 0 300 30 T600 30 T900 30 T1200 30 L1200 60 L0 60 Z" fill="currentColor"/>
  </svg>`,

  // Logo Eco Santé Développement (mark + wordmark)
  logo: `<svg viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <circle cx="28" cy="28" r="26" fill="#fbe8e3"/>
    <path d="M28 14 Q22 22 22 30 Q22 38 28 42 Q34 38 34 30 Q34 22 28 14 Z" fill="#74a079"/>
    <path d="M28 22 Q26 26 26 30" stroke="#34543b" stroke-width="1.5" stroke-linecap="round" fill="none"/>
    <circle cx="20" cy="22" r="3" fill="#efce5e"/>
    <circle cx="38" cy="20" r="2" fill="#d97058"/>
    <circle cx="40" cy="36" r="2.5" fill="#94c2dc"/>
  </svg>`,
};

// Helper: insère une illustration dans tous les éléments [data-illu="<name>"]
window.renderIllustrations = function() {
  document.querySelectorAll('[data-illu]').forEach(el => {
    const name = el.getAttribute('data-illu');
    if (window.IconLibrary[name]) {
      el.innerHTML = window.IconLibrary[name];
    }
  });
};

if (document.readyState !== 'loading') window.renderIllustrations();
else document.addEventListener('DOMContentLoaded', window.renderIllustrations);
