# FotoMatera – sito di Studio 54

Nuova veste grafica del sito di Studio 54: stessi contenuti e stesse funzioni, grafica diversa.
Colori chiari (bianco e azzurro nebbia con il blu notte), titoli in DM Serif Display, foto e schede
con gli angoli tondi. In home c'è il carosello a "storie": un lavoro per volta, la scheda in basso
apre il lavoro. In fondo a ogni pagina le informazioni dello studio.

Il sito è in **HTML, CSS, JavaScript e PHP** nella cartella [`sito/`](sito): home, lavori,
biografia, preventivo, area clienti con l'album. L'area riservata è la stessa di Studio 54.

## Anteprima online

https://felixone04.github.io/fotoMatera/

GitHub Pages non esegue PHP: a ogni push su `master` la pubblicazione trasforma le pagine PHP in HTML
statico (`sito/tools/esporta.sh`).

## Sito in locale

```bash
php -S localhost:8054 -t sito
```
