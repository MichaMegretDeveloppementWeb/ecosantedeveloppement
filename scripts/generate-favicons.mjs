// Génère les fichiers favicon à partir de public/favicon.svg.
// Utilisation (une seule fois, sharp/png-to-ico ne sont pas en devDeps) :
//   npm install --no-save sharp png-to-ico
//   node scripts/generate-favicons.mjs

import { readFile, writeFile } from 'node:fs/promises';
import { fileURLToPath } from 'node:url';
import { dirname, resolve } from 'node:path';
import sharp from 'sharp';
import pngToIco from 'png-to-ico';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const svgPath = resolve(root, 'public/favicon.svg');
const publicDir = resolve(root, 'public');

const svg = await readFile(svgPath);

const renderPng = (size) =>
    sharp(svg, { density: 384 }).resize(size, size).png({ compressionLevel: 9 }).toBuffer();

const [png16, png32, png48, png180] = await Promise.all([16, 32, 48, 180].map(renderPng));

await Promise.all([
    writeFile(resolve(publicDir, 'apple-touch-icon.png'), png180),
    writeFile(resolve(publicDir, 'favicon.ico'), await pngToIco([png16, png32, png48])),
]);

console.log('OK : public/favicon.ico + public/apple-touch-icon.png générés.');
