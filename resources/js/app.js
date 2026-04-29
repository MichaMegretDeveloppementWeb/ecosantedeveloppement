/* Eco Santé Développement — bootstrap JS minimal.
   Pour l'instant, un seul comportement global : toggle du menu mobile.
   Le drag-and-drop du formulaire de contact est géré dans
   resources/js/web/contact/index.js. */

document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('nav-toggle');
  const links = document.getElementById('nav-links');
  if (!toggle || !links) return;

  toggle.addEventListener('click', () => {
    const isOpen = links.classList.toggle('mobile-open');
    toggle.setAttribute('aria-expanded', String(isOpen));
  });
});
