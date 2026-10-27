<?php
require __DIR__ . '/inc/app.php';

$pagina = 'index';
$titolo = '';
$classe_body = 'pagina-home';
$c = contenuti();

// Il titolo va a capo dopo la virgola: la seconda parte in corsivo.
$frase = trim(str_replace("\n", ' ', $c['heroTitle'] ?? ''));
$parti = array_map('trim', explode(',', $frase, 2));
$righe = array_values(array_filter(array_map('trim', explode("\n", $c['manifestoTitle'] ?? ''))));

// Le storie: un lavoro per volta, toccando la scheda si apre il lavoro.
// Nel carosello vanno solo foto orizzontali: per ogni lavoro si prova la
// copertina e poi le altre foto, e sito.js tiene la prima orizzontale
// (i lavori senza foto orizzontali escono dal carosello).
$storie = [];
foreach (lavori_pubblicati() as $w) {
    $foto = array_values(array_unique(array_filter(array_merge(
        [copertina($w)],
        array_map(fn($p) => $p['url'] ?? '', $w['photos'] ?? [])
    ))));
    if ($foto) {
        $storie[] = ['lavoro' => $w, 'foto' => array_map(fn($u) => foto($u, 2000), $foto)];
    }
}
$storie = array_slice($storie, 0, 6);

require __DIR__ . '/inc/testa.php';
?>

<?php if ($storie): ?>
<!-- Apertura: le storie dei lavori a tutto schermo, sotto la barra. -->
<section class="storie" data-storie data-intervallo="5000" aria-roledescription="carosello" aria-label="I nostri lavori">
  <div class="storie-foto">
    <?php foreach ($storie as $i => ['lavoro' => $w, 'foto' => $foto]): ?>
      <figure class="storia<?= $i === 0 ? ' attiva' : '' ?>" data-titolo="<?= e($w['title'] ?? '') ?>"
              data-meta="<?= e(unisci([$w['category'] ?? '', $w['location'] ?? ''])) ?>" data-link="<?= e(url('lavoro', $w['id'])) ?>"
              data-foto="<?= e(json_encode($foto)) ?>">
        <img src="<?= e($foto[0]) ?>" alt="<?= e($w['title'] ?? '') ?>">
      </figure>
    <?php endforeach; ?>
  </div>
  <div class="storie-velo" aria-hidden="true"></div>
  <div class="storie-barre" aria-hidden="true"></div>
  <button class="storie-prec" type="button" aria-label="Lavoro precedente"></button>
  <button class="storie-succ" type="button" aria-label="Lavoro successivo"></button>
  <?php $primo = $storie[0]['lavoro']; ?>
  <!-- In basso, direttamente sulla foto: i festeggiati, il tipo di festa e
       il luogo, con "Guarda" per aprire il lavoro; a destra le frecce. -->
  <a class="storie-scheda" href="<?= e(url('lavoro', $primo['id'])) ?>">
    <span class="storie-testo">
      <b class="storie-titolo"><?= e($primo['title'] ?? '') ?></b>
      <span class="storie-meta"><?= e(unisci([$primo['category'] ?? '', $primo['location'] ?? ''])) ?></span>
    </span>
    <span class="storie-guarda">Guarda <i aria-hidden="true">&rarr;</i></span>
  </a>
  <div class="storie-frecce">
    <button class="storie-freccia storie-indietro" type="button" aria-label="Foto precedente">&#8249;</button>
    <button class="storie-freccia storie-avanti" type="button" aria-label="Foto successiva">&#8250;</button>
  </div>
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
