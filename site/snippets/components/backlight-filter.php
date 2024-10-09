<svg width="0" height="0">
  <filter id="blur-and-scale" y="-50%" x="-50%" width="200%" height="200%">
    <feGaussianBlur in="SourceGraphic" stdDeviation="40" result="blurred" />
    <feColorMatrix type="saturate" in="blurred" values="5" />
    <feComposite in="SourceGraphic" operator="over" />
  </filter>
</svg>