{{--
    Définitions SVG des silhouettes blob (clipPath) pour les photos des
    crèches. Une silhouette par palette pour donner à chaque crèche un
    caractère unique. Les coordonnées sont en `objectBoundingBox` (0..1)
    donc les paths s'adaptent à la taille de l'élément clippé.

    Inclu une seule fois dans web.creches.index pour éviter la duplication.
--}}
<svg aria-hidden="true" focusable="false" width="0" height="0" style="position:absolute;width:0;height:0;overflow:hidden">
    <defs>
        {{-- Tous les paths sont remappés pour remplir 0..1 (même forme,
             mais leur bbox colle à l'image → image peu débordante). --}}
        <clipPath id="blob-rose" clipPathUnits="objectBoundingBox">
            <path d="M 0.4769 0 C 0.6731 0, 0.8048 0.0817, 0.9030 0.2714 C 1.0000 0.4587, 0.9865 0.6246, 0.8893 0.8119 C 0.8003 0.9842, 0.6552 1.0000, 0.4769 1.0000 C 0.3043 1.0000, 0.1712 0.9654, 0.0850 0.7987 C 0 0.6345, 0.0545 0.4928, 0.1395 0.3286 C 0.2291 0.1554, 0.2978 0, 0.4769 0 Z"/>
        </clipPath>
        <clipPath id="blob-jaune" clipPathUnits="objectBoundingBox">
            <path d="M 0.9033 0.1372 C 0.9888 0.2384, 1.0000 0.4040, 0.9707 0.5727 C 0.9415 0.7407, 0.8725 0.9116, 0.7563 0.9558 C 0.6402 1.0000, 0.4783 0.9176, 0.3389 0.8328 C 0.2002 0.7489, 0.0832 0.6619, 0.0420 0.5405 C 0 0.4190, 0.0330 0.2631, 0.1222 0.1612 C 0.2107 0.0592, 0.3554 0.0120, 0.5076 0.0060 C 0.6589 0, 0.8186 0.0367, 0.9033 0.1372 Z"/>
        </clipPath>
        <clipPath id="blob-bleu" clipPathUnits="objectBoundingBox">
            <path d="M 0.9329 0.1986 C 1.0000 0.3233, 0.9717 0.5026, 0.9231 0.6517 C 0.8751 0.8008, 0.8060 0.9195, 0.7146 0.9600 C 0.6226 1.0000, 0.5076 0.9631, 0.3814 0.9106 C 0.2551 0.8583, 0.1170 0.7903, 0.0585 0.6583 C 0 0.5263, 0.0204 0.3293, 0.1144 0.1979 C 0.2078 0.0657, 0.3748 0, 0.5393 0.0022 C 0.7035 0.0037, 0.8659 0.0738, 0.9329 0.1986 Z"/>
        </clipPath>
    </defs>
</svg>
