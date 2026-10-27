<?php
require __DIR__ . '/inc/app.php';

$pagina = 'lavori';
$titolo = 'Lavori';
$lavori = lavori_pubblicati();
$n = count($lavori);

/** Valori distinti (senza badare alle maiuscole), nell'ordine dato. */
function valori(array $lavori, string $campo, array $ordine = []): array
{
    $v = [];
    foreach ($lavori as $w) {
        $x = trim((string) ($w[$campo] ?? ''));
        if ($x !== '' && !isset($v[mb_strtolower($x)])) {
            $v[mb_strtolower($x)] = $x;
        }
    }
    $v = array_values($v);
    if ($ordine) {
        $pos = fn($x) => ($i = array_search(mb_strtolower($x), array_map('mb_strtolower', $ordine), true)) === false ? 99 : $i;
        usort($v, fn($a, $b) => $pos($a) <=> $pos($b));
    }
    return $v;
}

$filtri = array_filter([
    ['evento', 'Evento', valori($lavori, 'category')],
    ['stagione', 'Stagione', valori($lavori, 'seasonLabel', ['Primavera', 'Estate', 'Autunno', 'Inverno'])],
    ['location', 'Location', valori($lavori, 'setting')],
], fn($f) => count($f[2]) > 1);

require __DIR__ . '/inc/testa.php';
?>

<div class="contenitore lavori-testa">
  <div>
    <p class="etichetta entra">I nostri lavori</p>
    <h1 class="titolo-grande entra">Ogni lavoro,<br><em>una storia.</em></h1>
    <p class="sottotitolo entra">Matrimoni ed eventi raccontati dall'inizio alla fine.</p>
  </div>
  <span class="contatore"><?= due($n) ?> <?= $n === 1 ? 'lavoro' : 'lavori' ?></span>
</div>

<?php if ($filtri): ?>
<div class="contenitore filtri" data-filtri>
  <div class="filtri-voci">
    <?php foreach ($filtri as [$id, $nome, $valori]): ?>
      <div class="filtro" data-filtro="<?= e($id) ?>">
        <button type="button" class="filtro-voce" aria-haspopup="listbox" aria-expanded="false">
          <span class="filtro-nome"><?= e($nome) ?></span>
          <!-- Tutte le scelte una sopra l'altra: la voce è larga quanto la più
               lunga, così cambiando scelta niente si sposta. -->
          <span class="filtro-valore">
            <span class="visibile" data-v="">Tutti</span>
            <?php foreach ($valori as $v): ?><span data-v="<?= e(mb_strtolower($v)) ?>"><?= e($v) ?></span><?php endforeach; ?>
          </span>
          <span class="filtro-freccia" aria-hidden="true"></span>
        </button>
        <ul class="filtro-menu" role="listbox" hidden>
          <li role="option" data-v="" aria-selected="true">Tutti</li>
          <?php foreach ($valori as $v): ?><li role="option" data-v="<?= e(mb_strtolower($v)) ?>"><?= e($v) ?></li><?php endforeach; ?>
        </ul>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="filtri-esito" hidden>
    <span class="contatore filtri-conto"></span>
    <button type="button" class="link filtri-azzera">Azzera</button>
  </div>
</div>
<?php endif; ?>

<div class="contenitore lavori-elenco">
  <?php if (!$lavori): ?>
    <p class="vuoto">Presto qui i nostri lavori.</p>
  <?php endif; ?>
  <p class="vuoto filtri-nessuno" hidden>Nessun lavoro con questi filtri.</p>
  <?php foreach ($lavori as $i => $w):
      $meta = unisci([$w['location'] ?? '', $w['date'] ?? '']);
      $tag = array_filter([$w['setting'] ?? '', $w['seasonLabel'] ?? '']);
  ?>
    <a class="lavoro rivela<?= $i % 2 ? ' rovescio' : '' ?>" href="<?= e(url('lavoro', $w['id'])) ?>"
       data-evento="<?= e(mb_strtolower(trim($w['category'] ?? ''))) ?>"
       data-stagione="<?= e(mb_strtolower(trim($w['seasonLabel'] ?? ''))) ?>"
       data-location="<?= e(mb_strtolower(trim($w['setting'] ?? ''))) ?>">
      <div class="lavoro-foto mira">
        <img <?= foto_attr(copertina($w), '(max-width: 1023px) 100vw, 58vw') ?> alt="" loading="lazy">
      </div>
      <div class="lavoro-testo">
        <p class="lavoro-tipo"><span class="num-oro"><?= due($i + 1) ?></span><?= e($w['category'] ?? '') ?></p>
        <h2><?= e($w['title'] ?? '') ?></h2>
        <?php if ($meta !== ''): ?><p class="lavoro-meta"><?= e($meta) ?></p><?php endif; ?>
        <?php if ($tag): ?>
          <p class="lavoro-tag"><?php foreach ($tag as $t): ?><span><?= e($t) ?></span><?php endforeach; ?></p>
        <?php endif; ?>
        <?php if (!empty($w['story'])): ?><p class="lavoro-storia"><?= e($w['story']) ?></p><?php endif; ?>
        <p class="lavoro-guarda"><span class="contatore"><?= due(count($w['photos'] ?? [])) ?> scatti</span><b>Guarda &rarr;</b></p>
      </div>
    </a>
  <?php endforeach; ?>
</div>

<?php require __DIR__ . '/inc/piede.php'; ?>
