<?php
require __DIR__ . '/inc/app.php';

$pagina = 'lavori';
$lavori = lavori_pubblicati();
$id = param('id');
$i = null;
foreach ($lavori as $k => $w) {
    if ($w['id'] === $id) {
        $i = $k;
    }
}
$w = $i === null ? null : $lavori[$i];
$succ = ($w && count($lavori) > 1) ? $lavori[($i + 1) % count($lavori)] : null;
$titolo = $w['title'] ?? 'Lavoro';
if (!$w && !STATICO) {
    http_response_code(404);
}

require __DIR__ . '/inc/testa.php';
?>

<?php if (!$w): ?>
  <div class="contenitore lavoro-assente">
    <p>Questo lavoro non è più in mostra.</p>
    <a class="indietro" href="<?= e(url('lavori')) ?>">&larr; Tutti i lavori</a>
  </div>
<?php else:
    $info = array_filter(array_map('trim', [$w['category'] ?? '', $w['setting'] ?? '', $w['seasonLabel'] ?? '', $w['location'] ?? '', $w['date'] ?? '']));
?>
  <div class="contenitore dettaglio-testa">
    <a class="indietro" href="<?= e(url('lavori')) ?>">&larr; Tutti i lavori</a>
    <h1 class="entra"><?= e($w['title']) ?></h1>
    <?php if ($info): ?>
      <p class="lavoro-tag entra"><?php foreach ($info as $t): ?><span><?= e($t) ?></span><?php endforeach; ?></p>
    <?php endif; ?>
  </div>

  <?php if (!empty($w['photos'])): ?>
  <!-- Solo foto: la prima orizzontale grande, poi un collage che alterna
       verticali e orizzontali senza tagliarle (composto da sito.js). -->
  <div class="contenitore collage" data-collage data-titolo="<?= e($w['title']) ?>"
       data-meta="<?= e(unisci([$w['category'] ?? '', $w['location'] ?? '', $w['date'] ?? ''])) ?>">
    <?php foreach ($w['photos'] as $p): ?>
      <figure class="tavola" data-foto="<?= e(foto($p['url'], 2200)) ?>" data-didascalia="<?= e($p['caption'] ?? '') ?>"
              tabindex="0" role="button" aria-label="<?= e(($p['caption'] ?? '') ?: $w['title']) ?>">
        <div class="tavola-foto mira">
          <img <?= foto_attr($p['url'], '(max-width: 599px) 100vw, 60vw') ?> alt="<?= e($p['caption'] ?? '') ?>">
        </div>
        <figcaption><span class="num-oro"><span class="n"></span></span><?php if (!empty($p['caption'])): ?><em><?= e($p['caption']) ?></em><?php endif; ?></figcaption>
      </figure>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <div class="contenitore dettaglio-fondo">
    <?php if ($succ): ?>
      <a class="successivo" href="<?= e(url('lavoro', $succ['id'])) ?>">
        <span class="successivo-foto mira"><img <?= foto_attr(copertina($succ), '170px') ?> alt="" loading="lazy"></span>
        <span class="successivo-testo"><small>Lavoro successivo</small><strong><?= e($succ['title']) ?></strong></span>
        <span class="successivo-freccia" aria-hidden="true">&rarr;</span>
      </a>
    <?php endif; ?>
    <a class="indietro" href="<?= e(url('lavori')) ?>">&larr; Tutti i lavori</a>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/inc/piede.php'; ?>
