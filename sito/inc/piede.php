<?php
// Fine di ogni pagina pubblica: le informazioni dello studio e gli script.
$c = contenuti();
$k = $c['contacts'] ?? [];
$tel = preg_replace('/[^0-9+]/', '', $k['phone'] ?? '');
?>
  </main>

  <footer class="piede">
    <div class="piede-dentro">
      <div class="piede-testa">
        <img class="piede-logo" src="assets/img/logo.png" alt="<?= e($c['studioName'] ?? 'Studio 54') ?>">
      </div>
      <!-- Preventivo e WhatsApp stanno solo nella pagina del preventivo. -->
      <div class="piede-colonne">
        <div class="piede-scheda">
          <h3>Contatti</h3>
          <?php if (!empty($k['phone'])): ?><a href="tel:<?= e($tel) ?>"><?= e($k['phone']) ?></a><?php endif; ?>
          <?php if (!empty($k['email'])): ?><a href="mailto:<?= e($k['email']) ?>"><?= e($k['email']) ?></a><?php endif; ?>
        </div>
        <div class="piede-scheda">
          <h3>Dove siamo</h3>
          <?php if (!empty($k['address'])): ?>
            <p><?= e($k['address']) ?></p>
            <a class="sottolineato" href="https://www.google.com/maps/search/?api=1&amp;query=<?= e(rawurlencode($k['address'])) ?>" target="_blank" rel="noopener">Apri in Maps</a>
          <?php endif; ?>
        </div>
        <div class="piede-scheda">
          <h3>Orari</h3>
          <?php foreach (array_filter(array_map('trim', explode('·', $k['hours'] ?? ''))) as $h): ?><p><?= e($h) ?></p><?php endforeach; ?>
        </div>
        <div class="piede-scheda">
          <h3>Seguici</h3>
          <?php if (!empty($k['instagram'])): ?><a href="<?= e($k['instagram']) ?>" target="_blank" rel="noopener">Instagram</a><?php endif; ?>
          <?php if (!empty($k['facebook'])): ?><a href="<?= e($k['facebook']) ?>" target="_blank" rel="noopener">Facebook</a><?php endif; ?>
        </div>
      </div>
      <div class="piede-fondo">
        <span>&copy; <?= date('Y') ?> <?= e($c['studioName'] ?? '') ?><?= !empty($k['vat']) ? '  ·  ' . e($k['vat']) : '' ?></span>
        <span class="piede-link">
          <a href="<?= e(url('lavori')) ?>">Lavori</a>
          <a href="<?= e(url('biografia')) ?>">Biografia</a>
          <a href="<?= e(url('area-clienti')) ?>">Area clienti</a>
          <a href="<?= e(url('riservata')) ?>">Area riservata</a>
        </span>
      </div>
    </div>
  </footer>

  <div class="lightbox" id="lightbox" hidden aria-modal="true" role="dialog" aria-label="Foto a schermo intero">
    <button class="lb-chiudi" type="button" aria-label="Chiudi">&times;</button>
    <button class="lb-freccia lb-prec" type="button" aria-label="Foto precedente">&#8249;</button>
    <figure><img alt=""></figure>
    <button class="lb-freccia lb-succ" type="button" aria-label="Foto successiva">&#8250;</button>
    <div class="lb-testo">
      <div><p class="lb-titolo"></p><p class="lb-meta"></p></div>
      <span class="lb-conto"></span>
    </div>
  </div>

  <script src="<?= e(risorsa('assets/js/sito.js')) ?>"></script>
  <?php foreach ($js_extra ?? [] as $x): ?><script src="<?= e(risorsa($x)) ?>"></script><?php endforeach; ?>
</body>
</html>
