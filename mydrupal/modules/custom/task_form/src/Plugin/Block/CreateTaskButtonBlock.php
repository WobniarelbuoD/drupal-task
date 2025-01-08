<?php

namespace Drupal\task_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url; // Import the Url class.

/**
 * Provides a 'Create Task Button' Block.
 *
 * @Block(
 *   id = "create_task_button",
 *   admin_label = @Translation("Create Task Button"),
 * )
 */

class CreateTaskButtonBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    // Set the URL to the route defined in the routing file.
    $form_url = Url::fromRoute('task_form.create')->toString(); // Use the Url class to create the URL.

    // Return the button markup.
    return [
      '#markup' => '<a href="' . $form_url . '" class="button">Create New Task</a>',
      '#allowed_tags' => ['a'], // Allow anchor tags to be rendered.
    ];
  }
}
