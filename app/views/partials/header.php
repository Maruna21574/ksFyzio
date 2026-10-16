<?php
// Hlavné menu. Položka s 'children' = rozbaľovacie menu.
$serviceItems = [];
foreach ($GLOBALS['SERVICES'] as $s) {
    $serviceItems[] = ['/sluzby/' . $s['slug'], $s['title'], $s['icon'], '', $s['color']];
}
$serviceItems[] = ['/sluzby', 'Všetky služby', 'arrow', 'Prehľad a porovnanie', 'all'];

$nav = [
    ['label' => 'Služby', 'base' => '/sluzby', 'children' => $serviceItems],
    ['label' => 'Pre majiteľov', 'base' => '', 'children' => [
        ['/ako-to-funguje', 'Ako to funguje', 'clock', 'Postup a časté otázky', 'cyan'],
        ['/test-pohybu', 'Test pohybu psa', 'activity', 'Rýchly orientačný test', 'green'],
        ['/vek-psa', 'Vek psa v ľudských rokoch', 'sun', 'Zábavná kalkulačka', 'lime'],
        ['/cennik', 'Cenník', 'tag', 'Ceny a balíčky', 'lime'],
        ['/pribehy', 'Príbehy pacientov', 'heart', 'Pred a po terapii', 'red'],
        ['/tipy', 'Tipy a rady', 'book', 'Články o pohybe zvierat', 'navy'],
    ]],
    ['label' => 'O nás', 'base' => '/o-nas', 'children' => [
        ['/o-nas', 'O nás', 'paw', 'Kto sme a ako pracujeme', 'cyan'],
        ['/rezervacia', 'Objednať sa', 'calendar', 'Online rezervácia', 'red'],
    ]],
    ['label' => 'Kontakt', 'href' => '/kontakt'],
];

$cur = $GLOBALS['CURRENT_PATH'] ?? '/';
$isIn = function (array $item) use ($cur): bool {
    foreach ($item['children'] ?? [] as $c) {
        if ($cur === $c[0] || ($c[0] !== '/sluzby' && str_starts_with($cur, $c[0] . '/'))) {
            return true;
        }
    }
    return false;
};
?>
<header class="site-header" data-header>
  <div class="container site-header__inner">
    <a class="brand" href="<?= e(url('/')) ?>" aria-label="<?= e(cfg('name')) ?> – domov">
      <img src="<?= e(asset('assets/img/logo.webp')) ?>" alt="<?= e(cfg('name')) ?> – <?= e(cfg('tagline')) ?>" width="560" height="360" decoding="async">
    </a>

    <nav class="nav" id="hlavne-menu" aria-label="Hlavné menu" data-nav>
      <ul class="nav__list">
        <?php foreach ($nav as $n): ?>
          <?php if (empty($n['children'])): ?>
            <li><a class="nav__link" href="<?= e(url($n['href'])) ?>"<?= active($n['href']) ?>><?= e($n['label']) ?></a></li>
          <?php else: ?>
            <li class="has-sub<?= $isIn($n) ? ' is-current' : '' ?>" data-sub>
              <button class="nav__link nav__toggle" type="button" aria-expanded="false" aria-haspopup="true">
                <?= e($n['label']) ?><?= icon('chevron', 'nav__caret') ?>
              </button>
              <div class="subnav">
                <ul>
                  <?php foreach ($n['children'] as [$href, $label, $ico, $desc, $color]): ?>
                    <li>
                      <a class="subnav__link subnav__link--<?= e($color) ?>" href="<?= e(url($href)) ?>"<?= $cur === $href ? ' aria-current="page"' : '' ?>>
                        <span class="subnav__icon"><?= icon($ico) ?></span>
                        <span class="subnav__text">
                          <strong><?= e($label) ?></strong>
                          <?php if ($desc !== ''): ?><small><?= e($desc) ?></small><?php endif; ?>
                        </span>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </li>
          <?php endif; ?>
        <?php endforeach; ?>
      </ul>
      <div class="nav__actions">
        <a class="nav__phone" href="tel:<?= e(cfg('phone_href')) ?>"><?= icon('phone') ?><span><?= e(cfg('phone')) ?></span></a>
        <a class="btn btn--primary btn--sm" href="<?= e(url('/rezervacia')) ?>">Objednať sa</a>
      </div>
    </nav>

    <button class="burger" type="button" aria-expanded="false" aria-controls="hlavne-menu" data-burger>
      <span class="sr-only">Menu</span>
      <?= icon('menu', 'burger__open') ?>
      <?= icon('close', 'burger__close') ?>
    </button>
  </div>
</header>
