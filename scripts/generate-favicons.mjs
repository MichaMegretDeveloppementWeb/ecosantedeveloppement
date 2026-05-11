// Génère les fichiers favicon à partir du logo PNG.
// Utilisation (une seule fois, sharp/png-to-ico ne sont pas en devDeps) :
//   npm install --no-save sharp png-to-ico
//   node scripts/generate-favicons.mjs

import { readFile, writeFile } from 'node:fs/promises';
import { fileURLToPath } from 'node:url';
import { dirname, resolve } from 'node:path';
import sharp from 'sharp';
import pngToIco from 'png-to-ico';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const sourcePath = resolve(root, 'public/logo-eco-sante-developpement.png');
const publicDir = resolve(root, 'public');

const source = await readFile(sourcePath);

// Le logo a un canvas légèrement rectangulaire ; on le recadre/aplatit sur
// fond blanc pour des favicons carrés et lisibles aux petites tailles.
const renderPng = (size) =>
    sharp(source)
        .resize(size, size, { fit: 'contain', background: { r: 255, g: 255, b: 255, alpha: 1 } })
        .flatten({ background: '#ffffff' })
        .png({ compressionLevel: 9 })
        .toBuffer();

const [png16, png32, png48, png180, png192, png512] = await Promise.all(
    [16, 32, 48, 180, 192, 512].map(renderPng),
);

await Promise.all([
    writeFile(resolve(publicDir, 'apple-touch-icon.png'), png180),
    writeFile(resolve(publicDir, 'favicon-32x32.png'), png32),
    writeFile(resolve(publicDir, 'favicon-16x16.png'), png16),
    writeFile(resolve(publicDir, 'favicon.ico'), await pngToIco([png16, png32, png48])),
]);

console.log('OK : favicons générés à partir du logo PNG.');
