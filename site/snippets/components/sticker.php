<?php
/** @var string|null $emoji */

$id = \Kirby\Toolkit\Str::uuid();
?>
<div class="sticker inline-block select-none drop-shadow-[0.5px_0.5px_2px_rgba(0,_0,_0,_0.45)]">
  <svg width="0" height="0">
    <filter id="<?= $id ?>-normal">
      <feMorphology in="SourceAlpha" result="Dilated" operator="dilate" radius="4"></feMorphology>
      <feFlood flood-color="#ffffff" result="OutlineColor"></feFlood>
      <feComposite in="OutlineColor" in2="Dilated" operator="in" result="Outline"></feComposite>
      <feMerge>
        <feMergeNode in="Outline"></feMergeNode>
        <feMergeNode in="SourceGraphic"></feMergeNode>
      </feMerge>
    </filter>

    <filter id="<?= $id ?>-puffy" color-interpolation-filters="sRGB">
      <feGaussianBlur in="SourceAlpha" stdDeviation="3" result="blur1"></feGaussianBlur>
      <feSpecularLighting result="spec1" in="blur1" surfaceScale="5" specularConstant="0.5" specularExponent="120" lighting-color="#ffffff">
        <fePointLight class="sticker-light" x="-150" y="-150" z="300"></fePointLight>
      </feSpecularLighting>
      <feComposite in="spec1" in2="SourceAlpha" operator="in" result="specOut2"></feComposite>
      <feComposite in="SourceGraphic" in2="specOut2" operator="arithmetic" k1="0" k2="1" k3="1" k4="0" result="litPaint"></feComposite>
    </filter>
  </svg>
  <span style="filter: url(#<?= $id ?>-puffy);"><?= $emoji ?></span>
</div>
