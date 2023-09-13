<?php
/** @var string|null $emoji */

$id = \Kirby\Toolkit\Str::uuid();
?>
<div class="inline-block select-none drop-shadow-[0.5px_0.5px_2px_rgba(0,_0,_0,_0.25)] du-dark:drop-shadow-[0.5px_0.5px_2px_rgba(255,_255,_255,_0.25)]" data-sticker="emoji">
  <svg width="0" height="0">
    <filter id="<?= $id ?>-puffy" color-interpolation-filters="sRGB">
      <feGaussianBlur in="SourceAlpha" stdDeviation="3" result="blur1"></feGaussianBlur>
      <feSpecularLighting result="spec1" in="blur1" surfaceScale="5" specularConstant="0.5" specularExponent="120" lighting-color="#ffffff">
        <fePointLight x="150" y="0" z="300" data-sticker-light></fePointLight>
      </feSpecularLighting>
      <feComposite in="spec1" in2="SourceAlpha" operator="in" result="specOut2"></feComposite>
      <feComposite in="SourceGraphic" in2="specOut2" operator="arithmetic" k1="0" k2="1" k3="1" k4="0" result="litPaint"></feComposite>
    </filter>
  </svg>
  <span style="filter: url(#<?= $id ?>-puffy);"><?= $emoji ?></span>
</div>
