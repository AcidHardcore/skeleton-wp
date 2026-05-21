<?php

namespace Skeleton_WP\Skeleton_WP\Enqueue;

/**
 * Immutable value object representing a single asset (script or style).
 */
readonly class Asset
{

  /**
   * @param string $handle WordPress asset handle.
   * @param string $url Full URL to the asset file.
   * @param string $path Absolute server path for filemtime versioning.
   * @param string[] $dependencies List of asset handle dependencies.
   * @param array<string, mixed> $args Extra args (scripts only – strategy, in_footer).
   */
  public function __construct(
    public string $handle,
    public string $url,
    public string $path,
    public array  $dependencies = [],
    public array  $args = [],
    public bool   $enqueue = true
  )
  {
  }
}
