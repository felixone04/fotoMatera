<?php
require __DIR__ . '/inc/app.php';

$pagina = 'index';
$titolo = '';
$classe_body = 'pagina-home';
$c = contenuti();
$k = $c['contacts'] ?? [];
$wa = preg_replace('/[^0-9]/', '', $k['whatsapp'] ?? '');
$firma = mb_strtolower(str_replace(' ', '', $c['studioName'] ?? 'studio54'));

// Il titolo va a capo dopo la virgola: la seconda parte in corsivo.
$frase = trim(str_replace("\n", ' ', $c['heroTitle'] ?? ''));
$parti = array_map('trim', explode(',', $frase, 2));
$intro = trim(str_replace("\n", ' ', $c['bioIntro'] ?? ''));
$righe = array_values(array_filter(array_map('trim', explode("\n", $c['manifestoTitle'] ?? ''))));

// Le storie: un lavoro per volta, toccando la scheda si apre il lavoro.
$storie = array_slice(array_values(array_filter(lavori_pubblicati(), fn($w) => copertina($w) !== '')), 0, 6);

require __DIR__ . '/inc/testa.php';
?>

<section class="home contenitore">
  <div class="home-testo">
    <?php if (!empty($c['heroKicker'])): ?><p class="etichetta entra"><?= e($c['heroKicker']) ?></p><?php endif; ?>
    <?php if ($frase !== ''): ?>
      <h1 class="home-titolo entra"><?= e($parti[0]) ?><?php if (isset($parti[1])): ?>,<br><em><?= e($parti[1]) ?></em><?php endif; ?></h1>
    <?php endif; ?>
    <?php if ($intro !== ''): ?><p class="home-sotto entra"><?= e($intro) ?></p><?php endif; ?>
    <div class="home-azioni entra">
      <a class="bottone" href="<?= e(url('preventivo')) ?>">Richiedi un preventivo</a>
      <?php if ($wa !== ''): ?>
        <a class="bottone chiaro" href="https://wa.me/<?= e($wa) ?>" target="_blank" rel="noopener">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 20l1.3-4A8 8 0 1 1 8 18.7z"/></svg>WhatsApp
        </a>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($storie): ?>
  <div class="storie" data-storie data-intervallo="5000" aria-roledescription="carosello" aria-label="I nostri lavori">
    <div class="storie-foto">
      <?php foreach ($storie as $i => $w): ?>
        <figure class="storia<?= $i === 0 ? ' attiva' : '' ?>" data-titolo="<?= e($w['title'] ?? '') ?>"
                data-meta="<?= e(unisci([$w['category'] ?? '', $w['location'] ?? ''])) ?>" data-link="<?= e(url('lavoro', $w['id'])) ?>">
          <img <?= foto_attr(copertina($w), '(max-width: 899px) 100vw, 50vw') ?> alt="<?= e($w['title'] ?? '') ?>" <?= $i > 0 ? 'loading="lazy"' : '' ?>>
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
  </div>
  <?php endif; ?>
</section>

<?php if ($righe): ?>
<section class="home-manifesto contenitore rivela">
  <img src="assets/img/mark.png" alt="">
  <p>
    <?php foreach ($righe as $i => $r): ?>
      <span class="<?= $i % 2 ? 'corsivo' : '' ?>"><?= e($r) ?></span>
    <?php endforeach; ?>
  </p>
  <a class="sottolineato" href="<?= e(url('lavori')) ?>">Guarda tutti i lavori &rarr;</a>
</section>
<?php endif; ?>

<?php require __DIR__ . '/inc/piede.php'; ?>
