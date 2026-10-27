<?php
require __DIR__ . '/inc/app.php';

$pagina = 'index';
$titolo = '';
$classe_body = 'pagina-home';
$c = contenuti();
$firma = mb_strtolower(str_replace(' ', '', $c['studioName'] ?? 'studio54'));

// Il titolo va a capo dopo la virgola: la seconda parte in corsivo.
$frase = trim(str_replace("\n", ' ', $c['heroTitle'] ?? ''));
$parti = array_map('trim', explode(',', $frase, 2));
$righe = array_values(array_filter(array_map('trim', explode("\n", $c['manifestoTitle'] ?? ''))));

// Le storie: un lavoro per volta, toccando la scheda si apre il lavoro.
$storie = array_slice(array_values(array_filter(lavori_pubblicati(), fn($w) => copertina($w) !== '')), 0, 6);

require __DIR__ . '/inc/testa.php';
?>

<?php if ($storie): ?>
<!-- Apertura: le storie dei lavori a tutto schermo, sotto la barra. -->
<section class="storie" data-storie data-intervallo="5000" aria-roledescription="carosello" aria-label="I nostri lavori">
  <div class="storie-foto">
    <?php foreach ($storie as $i => $w): ?>
      <figure class="storia<?= $i === 0 ? ' attiva' : '' ?>" data-titolo="<?= e($w['title'] ?? '') ?>"
              data-meta="<?= e(unisci([$w['category'] ?? '', $w['location'] ?? ''])) ?>" data-link="<?= e(url('lavoro', $w['id'])) ?>">
        <img <?= foto_attr(copertina($w), '100vw') ?> alt="<?= e($w['title'] ?? '') ?>" <?= $i > 0 ? 'loading="lazy"' : '' ?>>
      </figure>
    <?php endforeach; ?>
  </div>
  <div class="storie-barre" aria-hidden="true"></div>
  <span class="storie-firma" aria-hidden="true"><span><img src="assets/img/mark.png" alt=""></span><?= e($firma) ?></span>
  <button class="storie-prec" type="button" aria-label="Lavoro precedente"></button>
  <button class="storie-succ" type="button" aria-label="Lavoro successivo"></button>
  <a class="storie-scheda" href="<?= e(url('lavoro', $storie[0]['id'])) ?>">
    <span class="storie-testo">
      <b class="storie-titolo"><?= e($storie[0]['title'] ?? '') ?></b>
      <span class="storie-meta"><?= e(unisci([$storie[0]['category'] ?? '', $storie[0]['location'] ?? ''])) ?></span>
    </span>
    <span class="storie-guarda">Guarda</span>
  </a>
</section>
<?php endif; ?>

<section class="home-manifesto contenitore">
  <img class="entra" src="assets/img/mark.png" alt="">
  <?php if (!empty($c['heroKicker'])): ?><p class="etichetta entra"><?= e($c['heroKicker']) ?></p><?php endif; ?>
  <?php if ($frase !== ''): ?>
    <h1 class="home-titolo entra"><?= e($parti[0]) ?><?php if (isset($parti[1])): ?>, <em><?= e($parti[1]) ?></em><?php endif; ?></h1>
  <?php endif; ?>
  <?php if ($righe): ?>
    <p class="home-frase rivela">
      <?php foreach ($righe as $r): ?><span><?= e($r) ?></span><?php endforeach; ?>
    </p>
  <?php endif; ?>
  <a class="sottolineato rivela" href="<?= e(url('lavori')) ?>">Guarda tutti i lavori &rarr;</a>
</section>

<?php require __DIR__ . '/inc/piede.php'; ?>
