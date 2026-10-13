// Assemble a clean upload-ready folder (dist/) for WebSupport hosting.
import { rmSync, mkdirSync, cpSync, existsSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const out = join(root, 'dist');
rmSync(out, { recursive: true, force: true });
mkdirSync(out, { recursive: true });
for (const f of ['index.php', '.htaccess', 'favicon.ico', 'site.webmanifest', 'app', 'assets']) {
  if (existsSync(join(root, f))) cpSync(join(root, f), join(out, f), { recursive: true });
}
console.log('dist ✔  ->', out);
