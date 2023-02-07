<footer class="content py-3xl top-100vh bg-contrast-lower font-heading sticky">
  <a href="<?= $site->url() ?>" class="inline-block mb-8xl -ml-2" aria-label="<?= t('home') ?>">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 185.6 247.5" class="w-16 h-16" aria-hidden="true">
      <path d="M61.2,200.4c0,29.7-15.9,47.1-46.8,47.1c-4.8,0-10.8-0.3-14.4-1.5v-29.7c2.7,0.5,5.4,0.8,8.1,0.9c10.8,0,15.9-5.1,15.9-17.1 V60.9h37.2V200.4z" /><path d="M107.9,155.1c3,9.6,11.4,14.7,22.5,14.7c11.4,0,18.9-4.5,18.9-13.2c0-6-4.2-9-13.5-11.4l-27-6C88.4,134.4,74,123.9,74,101.4 c0-26.1,23.4-43.5,53.4-43.5c33.9,0,52.5,16.2,57,41.4H149c-2.4-7.5-8.7-13.2-21-13.2c-9,0-17.7,4.2-17.7,12.6 c0,5.4,3.6,8.4,12,10.5l27.6,6.3c23.4,5.7,35.7,18.6,35.7,39c0,27.3-23.7,43.5-54.9,43.5C98,198,74.9,182.4,71,155.1H107.9z" /><rect x="24" width="160.4" height="37.2" />
    </svg>
  </a>

  <div class="grid grid-cols-[1fr_auto] gap-1 sm:grid-cols-[1fr_auto_auto] sm:gap-lg">
    <div class="gap-xs font-500 flex items-center">
      <p>© <?= date('Y') ?> Johann Schopplich</p>
      <a href="https://www.instagram.com/johannschopplich/" class="i-fa-brands-instagram" aria-label="Instagram"></a>
      <a href="https://github.com/johannschopplich" class="i-fa-brands-github" aria-label="GitHub"></a>
    </div>

    <div class="gap-lg font-500 row-start-2 flex sm:row-start-auto">
      <?php foreach ($site->footerPages()->toPages() as $p): ?>
        <a href="<?= $p->url() ?>">
          <?= $p->title() ?>
        </a>
      <?php endforeach ?>
    </div>

    <button
      class="hover:text-primary du-light:i-bxs-moon du-dark:i-bx-bxs-sun"
      aria-label="<?= t('theme.switch') ?>"
      data-theme-switcher
    ></button>
  </div>
</footer>
