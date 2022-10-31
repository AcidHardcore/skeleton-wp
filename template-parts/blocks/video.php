<?php /**
 * @var array $args
 */

$youtube_id = $args['youtube_id'] ?? '';

if ( $youtube_id ): ?>
<div class="video-wrap">
	<iframe width="560" height="315" src="https://www.youtube.com/embed/<?= esc_url($youtube_id) ?>?cc_load_policy=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
</div>
<?php endif;
