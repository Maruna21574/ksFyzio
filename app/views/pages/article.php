<?php
/** @var array $article */
meta(['title' => $article['title'], 'description' => $article['excerpt']]);
$headTitle = $article['title'];
$headLead = $article['excerpt'];
$crumbs = [['/tipy', 'Tipy']];
include dirname(__DIR__) . '/partials/page-head.php';
?>
<section class="section section--flush">
  <div class="container container--narrow">
    <article class="prose">
      <?php if ($article['image']): ?>
        <figure class="article-figure">
          <img src="<?= e(asset($article['image'])) ?>" alt="<?= e($article['image_alt']) ?>" width="900" height="900" decoding="async">
        </figure>
      <?php endif; ?>
      <?php foreach ($article['body'] as [$type, $content]): ?>
        <?php if ($type === 'h'): ?><h2><?= e($content) ?></h2>
        <?php elseif ($type === 'p'): ?><p><?= e($content) ?></p>
        <?php elseif ($type === 'quote'): ?><blockquote><?= e($content) ?></blockquote>
        <?php elseif ($type === 'ul'): ?>
          <ul class="checks">
            <?php foreach ($content as $li): ?><li><?= icon('check') ?> <?= e($li) ?></li><?php endforeach; ?>
          </ul>
        <?php endif; ?>
      <?php endforeach; ?>
      <p class="notice"><?= icon('shield') ?> <span>Článok má informatívny charakter a nenahrádza vyšetrenie u veterinára.</span></p>
    </article>
    <p class="center"><a class="btn btn--primary" href="<?= e(url('/rezervacia')) ?>">Objednať sa</a> <a class="btn btn--text" href="<?= e(url('/tipy')) ?>">Ďalšie články</a></p>
  </div>
</section>
