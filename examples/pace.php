<?php
declare(strict_types=1);
// Adapted campaign status thresholds; null represents an undefined ratio.
function campaignPace(float $progress,float $elapsed,bool $ended): array {
    if (!is_finite($progress)||!is_finite($elapsed)||$progress<0||$elapsed<0||$elapsed>1) throw new InvalidArgumentException('Invalid progress');
    if ($progress>=1) return ['Goal hit!',null];
    if ($ended) return [$progress>=0.8?'So close':'Missed',0.0];
    if ($elapsed<=0) return ['Just started',null];
    $pace=$progress/max($elapsed,0.0001);
    return [$pace>=1.05?'Ahead of pace':($pace>=0.95?'On pace':($pace>=0.70?'Slipping':'Behind')),$pace];
}
