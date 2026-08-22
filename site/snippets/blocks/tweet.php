<?php

use Kirby\Http\Url;

/** @var \Kirby\Cms\Block $block */

$ownName = 'Johann Schopplich';

$url = $block->url()->value();
if (!$url) {
  return;
}

$handle = preg_match('%^https?://(?:www\.)?(?:x|twitter)\.com/([\w]{1,15})/status/%i', $url, $matches)
  ? $matches[1]
  : null;

$isExternal = $block->external()->toBool();
$author = $block->author()->or($isExternal ? $handle : $ownName)->value();
$avatar = $isExternal
  ? $block->avatar()->toFile()
  : page('linktree')?->files()->find('profile-image.png');

$text = $block->text()->permalinksToUrls();
$caption = $block->caption();
$image = $block->image()->toFile();
$published = $block->published();
$retweets = $block->retweets()->isNotEmpty() ? $block->retweets()->toInt() : null;
$likes = $block->likes()->isNotEmpty() ? $block->likes()->toInt() : null;

$formatCount = fn (int $count): string => MessageFormatter::formatMessage(
  kirby()->languageCode() ?? 'en',
  '{0, number, :: compact-short .#}',
  [$count]
) ?: (string) $count;

if ($caption->isEmpty() && $image) {
  $caption = $image->caption()->permalinksToUrls();
}

$isFeed = preg_match('/feeds\/(?:rss|json)$/', Url::current());

if ($isFeed): ?>
  <blockquote cite="<?= $url ?>">
    <?= $text ?>
    <?php if ($image): ?>
      <img src="<?= $image->resize(1024)->url() ?>" alt="<?= $image->alt()->escape() ?>">
    <?php endif ?>
    <footer>
      <a href="<?= $url ?>">
        <?= $author ?><?php if ($published->isNotEmpty()): ?>, <?= $published->toDate(dateFormatter()) ?><?php endif ?>
      </a>
    </footer>
  </blockquote>
<?php return; endif ?>

<figure class="is-inset text-start shadow-none">
  <div class="hyphenate p-lg border-1 border-contrast-lower border-solid rounded-2xl [&>*:not(:last-child)]:mb-xs sm:p-xl">
    <div class="flex gap-xs items-center">
      <?php if ($avatar): ?>
        <img
          src="<?= $avatar->resize(80)->url() ?>"
          alt=""
          width="40"
          height="40"
          loading="lazy"
          class="shrink-0 size-10 rounded-full"
        >
      <?php endif ?>

      <?php if ($author || $handle): ?>
        <div class="min-w-0 leading-dense">
          <?php if ($author): ?>
            <div class="title truncate"><?= html($author) ?></div>
          <?php endif ?>
          <?php if ($handle): ?>
            <a href="https://x.com/<?= $handle ?>" class="block truncate text-[0.875em] font-400 text-contrast-medium">
              @<?= $handle ?>
            </a>
          <?php endif ?>
        </div>
      <?php endif ?>

      <a href="<?= $url ?>" class="shrink-0 ml-auto text-contrast-medium" aria-label="<?= t('tweet.viewOnX') ?>">
        <span class="i-tabler-brand-x size-8" aria-hidden="true"></span>
      </a>
    </div>

    <?= $text ?>

    <?php if ($image): ?>
      <img
        src="<?= $image->thumbhashUri() ?>"
        data-srcset="<?= $image->srcset() ?>"
        data-sizes="auto"
        width="<?= $image->width() ?>"
        height="<?= $image->height() ?>"
        alt="<?= $image->alt()->escape() ?>"
        loading="lazy"
        style="aspect-ratio: <?= $image->width() ?>/<?= $image->height() ?>"
        class="w-full border-1 border-contrast-lower border-solid rounded-2xl"
      >
    <?php endif ?>

    <?php if ($published->isNotEmpty()): ?>
      <footer>
        <a href="<?= $url ?>" class="text-[0.875em] text-contrast-medium">
          <?= $published->toDate(dateFormatter()) ?>
        </a>
      </footer>
    <?php endif ?>

    <?php if ($retweets !== null || $likes !== null): ?>
      <div class="flex gap-lg pt-xs text-[0.875em] text-contrast-medium border-t-1 border-contrast-lower border-solid">
        <?php if ($retweets !== null): ?>
          <span><span class="font-600 text-contrast-higher"><?= $formatCount($retweets) ?></span> <?= t('tweet.retweets') ?></span>
        <?php endif ?>
        <?php if ($likes !== null): ?>
          <span><span class="font-600 text-contrast-higher"><?= $formatCount($likes) ?></span> <?= t('tweet.likes') ?></span>
        <?php endif ?>
      </div>
    <?php endif ?>
  </div>

  <?php if ($caption->isNotEmpty()): ?>
    <figcaption>
      <?= $caption ?>
    </figcaption>
  <?php endif ?>
</figure>
