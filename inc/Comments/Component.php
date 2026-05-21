<?php
/**
 * Skeleton_WP\Skeleton_WP\Comments\Component class
 *
 * @package skeleton_wp
 */

namespace Skeleton_WP\Skeleton_WP\Comments;

use Skeleton_WP\Skeleton_WP\Component_Interface;
use Skeleton_WP\Skeleton_WP\Templating_Component_Interface;
use function add_action;
use function is_singular;
use function comments_open;
use function get_option;
use function wp_enqueue_script;
use function wp_list_comments;
use function the_comments_navigation;

/**
 * Class for managing comments UI.
 *
 * Exposes template tags:
 * * `skeleton_wp()->the_comments( array $args = array() )`
 */
class Component implements Component_Interface, Templating_Component_Interface
{

  /**
   * Gets the unique identifier for the theme component.
   *
   * @return string Component slug.
   */
  public function get_slug(): string
  {
    return 'comments';
  }

  /**
   * Adds the action and filter hooks to integrate with WordPress.
   */
  public function initialize(): void
  {
    add_action('wp_enqueue_scripts', array($this, 'action_enqueue_comment_reply_script'));
  }

  /**
   * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `skeleton_wp()`.
   *
   * @return array Associative array of $method_name => $callback_info pairs.
   */
  public function template_tags(): array
  {
    return array(
      'the_comments' => array($this, 'the_comments'),
    );
  }

  /**
   * Enqueues the WordPress core 'comment-reply' script as necessary.
   */
  public function action_enqueue_comment_reply_script(): void
  {
    if (is_singular() && comments_open() && get_option('thread_comments')) {
      wp_enqueue_script('comment-reply');
    }
  }

  /**
   * Displays the list of comments for the current post.
   *
   * @param array $args Optional. Array of arguments. See `wp_list_comments()` for supported arguments.
   */
  public function the_comments(array $args = array()): void
  {
    $args = array_merge(
      $args,
      array(
        'style'      => 'ol',
        'short_ping' => true,
      )
    );

    ?>
    <ol class="comment-list">
      <?php wp_list_comments($args); ?>
    </ol><!-- .comment-list -->
    <?php

    the_comments_navigation();
  }
}
