// Build: SCSS -> CSS, JS -> minified bundle, fonts copy, image pipeline.
import { mkdirSync, copyFileSync, writeFileSync, existsSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';
import * as sass from 'sass';
import { build as esbuild } from 'esbuild';
import sharp from 'sharp';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const p = (...a) => join(root, ...a);
const only = process.argv[2]; // css | js | fonts | img | undefined = all
const want = (n) => !only || only === n;

for (const d of ['assets/css', 'assets/js', 'assets/fonts', 'assets/img']) mkdirSync(p(d), { recursive: true });

if (want('css')) {
  const out = sass.compile(p('src/scss/main.scss'), { style: 'compressed', loadPaths: [p('node_modules')] });
  writeFileSync(p('assets/css/main.css'), out.css);
  console.log('css  ✔');
}

if (want('js')) {
  await esbuild({
    entryPoints: [p('src/js/main.js')],
    outfile: p('assets/js/main.js'),
    bundle: true, minify: true, target: ['es2019'], legalComments: 'none',
  });
  console.log('js   ✔');
}

if (want('fonts')) {
  const dir = 'node_modules/@fontsource-variable/nunito/files';
  for (const f of ['nunito-latin-wght-normal.woff2', 'nunito-latin-ext-wght-normal.woff2'])
    copyFileSync(p(dir, f), p('assets/fonts', f));
  console.log('font ✔');
}

if (want('img')) {
  const src = (n) => p('src/img-source', n);
  const out = (n) => p('assets/img', n);

  // Logo: trim transparent padding
  const logo = await sharp(src('logo.png')).trim().toBuffer();
  await sharp(logo).resize({ width: 560 }).webp({ quality: 90, alphaQuality: 100 }).toFile(out('logo.webp'));
  await sharp(logo).resize({ width: 560 }).png({ compressionLevel: 9 }).toFile(out('logo.png'));

  // Paw mark -> square icon set
  const paw = await sharp(src('favicon.png')).trim().toBuffer();
  const square = (size, bg = { r: 0, g: 0, b: 0, alpha: 0 }, pad = 0.1) =>
    sharp(paw).resize({ width: Math.round(size * (1 - pad * 2)), height: Math.round(size * (1 - pad * 2)), fit: 'contain', background: { r: 0, g: 0, b: 0, alpha: 0 } })
      .extend({ top: Math.round(size * pad), bottom: Math.round(size * pad), left: Math.round(size * pad), right: Math.round(size * pad), background: bg })
      .resize(size, size, { fit: 'contain', background: bg }).png();
  const white = { r: 255, g: 255, b: 255, alpha: 1 };
  await square(512).toFile(out('icon-512.png'));
  await square(192).toFile(out('icon-192.png'));
  await square(180, white, 0.14).toFile(out('apple-touch-icon.png'));
  await square(96).toFile(out('favicon-96.png'));
  await sharp(paw).resize({ width: 160 }).webp({ quality: 90, alphaQuality: 100 }).toFile(out('paw.webp'));

  // favicon.ico (PNG-in-ICO: 16/32/48)
  const sizes = [16, 32, 48];
  const pngs = await Promise.all(sizes.map((s) => square(s, undefined, 0.04).toBuffer()));
  const head = Buffer.alloc(6); head.writeUInt16LE(1, 2); head.writeUInt16LE(sizes.length, 4);
  let offset = 6 + 16 * sizes.length;
  const dirs = pngs.map((buf, i) => {
    const e = Buffer.alloc(16);
    e[0] = sizes[i]; e[1] = sizes[i]; e.writeUInt16LE(1, 4); e.writeUInt16LE(32, 6);
    e.writeUInt32LE(buf.length, 8); e.writeUInt32LE(offset, 12); offset += buf.length;
    return e;
  });
  writeFileSync(p('favicon.ico'), Buffer.concat([head, ...dirs, ...pngs]));

  // Hero photo (dog)
  await sharp(src('hero-dog.jpg')).rotate().resize({ width: 900, withoutEnlargement: true }).webp({ quality: 82 }).toFile(out('hero-dog.webp'));
  await sharp(src('hero-dog.jpg')).rotate().resize({ width: 520, withoutEnlargement: true }).webp({ quality: 80 }).toFile(out('hero-dog-640.webp'));

  // Maskot: orezanie, vyčistenie okrajov (artefakty po vystrihnutí) a export
  {
    const raw = await sharp(src('mascot.png')).ensureAlpha().trim().toBuffer();
    const alpha = await sharp(raw).extractChannel('alpha').blur(1.6).threshold(170).blur(0.6).toBuffer();
    const rgb = await sharp(raw).removeAlpha().toBuffer();
    const clean = await sharp(rgb).joinChannel(alpha).png().toBuffer();
    await sharp(clean).resize({ width: 760 }).webp({ quality: 88, alphaQuality: 100 }).toFile(out('mascot.webp'));
    await sharp(clean).resize({ width: 380 }).webp({ quality: 88, alphaQuality: 100 }).toFile(out('mascot-sm.webp'));
  }

  // Fotky z praxe
  for (const n of ['praxe-podlozky', 'praxe-odmena']) {
    await sharp(src(n + '.jpg')).rotate().resize({ width: 800, withoutEnlargement: true }).webp({ quality: 82 }).toFile(out(n + '.webp'));
    await sharp(src(n + '.jpg')).rotate().resize({ width: 480, withoutEnlargement: true }).webp({ quality: 80 }).toFile(out(n + '-480.webp'));
  }

  // Open Graph 1200x630
  await sharp(src('og.png')).resize(1200, 630, { fit: 'cover' }).jpeg({ quality: 86, mozjpeg: true }).toFile(out('og-image.jpg'));

  // Social post used in article
  await sharp(src('post-keby-psy.png')).resize({ width: 900 }).webp({ quality: 84 }).toFile(out('post-keby-psy.webp'));
  console.log('img  ✔');
}
