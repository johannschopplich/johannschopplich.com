<footer class="sticky top-100vh due-py-xl">
  <div class="footer-container due-container-lg">
    <p>© <?= date('Y') ?> Johann Schopplich</p>

    <div class="footer-links flex space-x-5">
      <?php foreach ($site->footerPages()->toPages() as $p): ?>
        <a href="<?= $p->url() ?>">
          <?= $p->title() ?>
        </a>
      <?php endforeach ?>
    </div>

    <button id="theme-switcher" class="flex border-0 p-0 hover:text-accent" aria-label="<?= t('theme.switch') ?>">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="24" height="24" aria-hidden="true" focusable="false">
        <style>
          :root:not([data-theme]) .icon-dark,
          :root[data-theme="light"] .icon-dark,
          :root[data-theme="dark"] .icon-light {
            visibility: hidden;
          }
        </style>
        <g class="icon-light">
          <path d="M18.866 3.866A22.01 22.01 0 0 0 18 10c0 12.15 9.85 22 22 22a22.01 22.01 0 0 0 6.134-.866C43.478 40.299 35.022 47 25 47 12.85 47 3 37.15 3 25 3 14.978 9.7 6.522 18.866 3.866z"></path>
        </g>
        <g class="icon-dark">
          <circle cx="25" cy="25" r="14"></circle>
          <path d="M.605 19.177l1.236-3.804L8.5 17.536 7.263 21.34zM41.501 32.464l1.236-3.804 6.658 2.163-1.236 3.804zM11.923 46.4l-3.236-2.35 4.115-5.664 3.236 2.352zM37.198 11.614l-3.236-2.352L38.077 3.6l3.236 2.351zM41.313 44.05l-3.236 2.35-4.115-5.662 3.236-2.352zM16.038 9.262l-3.236 2.352L8.687 5.95l3.236-2.35zM48.159 15.373l1.236 3.804-6.658 2.163-1.236-3.804zM7.263 28.66l1.236 3.804-6.658 2.163-1.236-3.804zM23 0h4v7h-4zM23 43h4v7h-4z"></path>
        </g>
      </svg>
    </button>
  </div>
</footer>
