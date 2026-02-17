<?php

/** @var \Kirby\Cms\Blocks $blocks */
/** @var string|null $lang */
/** @var string|null $breakoutClass */

$lang ??= null;
$breakoutClass ??= '';
$breakoutTypes = ['gallery'];

$sections = [];
$proseBlocks = [];

foreach ($blocks as $block) {
  if (in_array($block->type(), $breakoutTypes, true)) {
    if ($proseBlocks) {
      $sections[] = ['type' => 'prose', 'blocks' => $proseBlocks];
      $proseBlocks = [];
    }
    $sections[] = ['type' => 'breakout', 'block' => $block];
  } else {
    $proseBlocks[] = $block;
  }
}

if ($proseBlocks) {
  $sections[] = ['type' => 'prose', 'blocks' => $proseBlocks];
}
?>

<?php foreach ($sections as $section): ?>
  <?php if ($section['type'] === 'breakout'): ?>
    <div <?= attr(['class' => trim('mt-$un-prose-space-y ' . $breakoutClass)]) ?>>
      <?= $section['block'] ?>
    </div>
  <?php else: ?>
    <div <?= attr(['class' => 'px-lg md:px-gutter', 'lang' => $lang]) ?>>
      <div class="prose max-w-prose">
        <?php foreach ($section['blocks'] as $block): ?>
          <?= $block ?>
        <?php endforeach ?>
      </div>
    </div>
  <?php endif ?>
<?php endforeach ?>
