{{--
    Définitions SVG des silhouettes blob (clipPath) pour les photos des
    crèches. Une silhouette par palette pour donner à chaque crèche un
    caractère unique. Les coordonnées sont en `objectBoundingBox` (0..1)
    donc les paths s'adaptent à la taille de l'élément clippé.

    Inclu une seule fois dans web.creches.index pour éviter la duplication.
--}}
<svg aria-hidden="true" focusable="false" width="0" height="0" style="position:absolute;width:0;height:0;overflow:hidden">
    <defs>
        <clipPath id="blob-rose" clipPathUnits="objectBoundingBox">
            <path d="M 0.5000 0.0468 C 0.6818 0.0468, 0.8038 0.1146, 0.8948 0.2721 C 0.9845 0.4275, 0.9720 0.5652, 0.8822 0.7207 C 0.7996 0.8637, 0.6651 0.8768, 0.5000 0.8768 C 0.3401 0.8768, 0.2168 0.8481, 0.1369 0.7097 C 0.0582 0.5734, 0.1087 0.4558, 0.1874 0.3195 C 0.2704 0.1758, 0.3341 0.0468, 0.5000 0.0468 Z"/>
        </clipPath>
        <clipPath id="blob-jaune" clipPathUnits="objectBoundingBox">
            <path d="M 0.5000 0.0068 C 0.6756 0.0068, 0.7949 0.0403, 0.9043 0.1776 C 1.0131 0.3139, 1.0122 0.4380, 0.9734 0.6080 C 0.9365 0.7696, 0.8525 0.8502, 0.7032 0.9220 C 0.5706 0.9859, 0.4904 0.8591, 0.3578 0.7952 C 0.2485 0.7426, 0.1695 0.6999, 0.1425 0.5816 C 0.1154 0.4629, 0.1655 0.3890, 0.2414 0.2938 C 0.3378 0.1730, 0.3455 0.0068, 0.5000 0.0068 Z"/>
        </clipPath>
        <clipPath id="blob-bleu" clipPathUnits="objectBoundingBox">
            <path d="M 0.798 0.242 C 0.849 0.3265, 0.8275 0.448, 0.7905 0.549 C 0.754 0.65, 0.7015 0.7305, 0.632 0.758 C 0.562 0.785, 0.4745 0.76, 0.3785 0.7245 C 0.2825 0.689, 0.1775 0.643, 0.133 0.5535 C 0.0885 0.464, 0.104 0.3305, 0.1755 0.2415 C 0.2465 0.152, 0.3735 0.1075, 0.4985 0.109 C 0.6235 0.11, 0.747 0.1575, 0.798 0.242 Z"/>
        </clipPath>
    </defs>
</svg>
