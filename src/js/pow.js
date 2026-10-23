/* Proof-of-work pre formuláre: nájde nonce, pre ktorý SHA-256("soľ:nonce") začína aspoň `bits` nulovými bitmi. */

function leadingZeroBits(bytes) {
  let bits = 0;
  for (const b of bytes) {
    if (b === 0) { bits += 8; continue; }
    return bits + Math.clz32(b) - 24;
  }
  return bits;
}

export async function solvePow(salt, bits) {
  const enc = new TextEncoder();
  for (let n = 0; ; n++) {
    const hash = new Uint8Array(await crypto.subtle.digest('SHA-256', enc.encode(`${salt}:${n}`)));
    if (leadingZeroBits(hash) >= bits) return String(n);
    if (n % 1500 === 1499) await new Promise((r) => setTimeout(r)); // nech stránka zostane plynulá
  }
}
