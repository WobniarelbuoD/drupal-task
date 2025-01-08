<?php

namespace Drupal\task_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use Drupal\node\Entity\Node;

class TaskForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'task_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, ?Node $node = NULL) {
      $query = \Drupal::entityQuery('user')
          ->condition('status', 1)
          ->condition('roles', 'Superadmin')
          ->accessCheck(TRUE);

      $uids = $query->execute();
      $developers = User::loadMultiple($uids);

      if ($node) {
        $form_state->set('node', $node);
      }

      $developer_options = [];
      foreach ($developers as $developer) {
        $first_name = $developer->get('field_name')->value;
        $last_name = $developer->get('field_last_name')->value;
        $developer_options[$developer->id()] = $first_name . ' ' . $last_name;
      }

      $form['task_name'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Name of Task'),
          '#required' => TRUE,
          '#default_value' => $node ? $node->get('field_task_name')->value : '',
      ];

      $form['developer'] = [
          '#type' => 'select',
          '#title' => $this->t('Tech/Senior Developer'),
          '#options' => $developer_options,
          '#required' => TRUE,
          '#default_value' => $node ? $node->get('field_developer')->target_id : '',
      ];

      $form['senior_estimate'] = [
          '#type' => 'number',
          '#title' => $this->t('Tech/Senior Developer Estimated Time (hours)'),
          '#required' => TRUE,
          '#min' => 0,
          '#default_value' => $node ? $node->get('field_senior_estimate')->value : 0,
      ];

      $form['junior_estimate'] = [
          '#type' => 'number',
          '#title' => $this->t('Junior Developer Estimated Time (hours)'),
          '#required' => TRUE,
          '#min' => 0,
          '#default_value' => $node ? $node->get('field_junior_estimate')->value : 0,
      ];

      if ($node) {
        $form['junior_time'] = [
            '#type' => 'number',
            '#title' => $this->t('Junior Developer Time (hours)'),
            '#required' => FALSE,
            '#min' => 0,
            '#default_value' => $node ? $node->get('field_junior_time')->value : 0,
        ];
      } else {
        $form['junior_time'] = [
            '#type' => 'hidden',
            '#default_value' => 0,
        ];
      }

      $form['task_description'] = [
          '#type' => 'textarea',
          '#title' => $this->t('Description'),
          '#required' => TRUE,
          '#default_value' => $node ? $node->get('field_task_description')->value : '',
      ];

      // Graph container.
      $form['graph'] = [
        '#type' => 'markup',
        '#markup' => '<div id="graph" style="width: 100%; height: 300px;"></div>',
      ];

      // Attach the CSS and JavaScript library.
      $form['#attached']['library'][] = 'task_form/task_graph';
      $form['#attached']['drupalSettings']['taskGraph'] = [
          'junior_estimate' => $node ? $node->get('field_junior_estimate')->value : 0,
          'senior_estimate' => $node ? $node->get('field_senior_estimate')->value : 0,
          'junior_time' => $node ? $node->get('field_junior_time')->value : 0,
      ];

      // Actions to submit the form.
      $form['actions'] = [
          '#type' => 'actions',
      ];

      $form['actions']['submit'] = [
          '#type' => 'submit',
          '#value' => $this->t('Submit'),
      ];

      if ($node) {
        $form['actions']['finish'] = [
            '#type' => 'submit',
            '#value' => $this->t('Finish Task'),
            '#submit' => ['::finishTask'],
            '#limit_validation_errors' => [],
        ];
      }

      return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      $junior_time = $form_state->getValue('junior_time');
      $junior_estimate = $form_state->getValue('junior_estimate');
      $senior_estimate = $form_state->getValue('senior_estimate');
      if ($node = $form_state->get('node')) {
          // Editing existing task
          $node->set('field_task_name', $form_state->getValue('task_name'));
          $node->set('field_developer', $form_state->getValue('developer'));
          $node->set('field_senior_estimate', $form_state->getValue('senior_estimate'));
          $node->set('field_junior_estimate', $form_state->getValue('junior_estimate'));
          $node->set('field_junior_time', $form_state->getValue('junior_time'));
          $node->set('field_task_description', $form_state->getValue('task_description'));
          $node->set('field_status', $form_state->getValue('status'));
      } else {
          // Creating a new task
          $node = Node::create([
              'type' => 'tasks',
              'title' => $form_state->getValue('task_name'),
              'field_task_name' => $form_state->getValue('task_name'),
              'field_developer' => $form_state->getValue('developer'),
              'field_senior_estimate' => $form_state->getValue('senior_estimate'),
              'field_junior_estimate' => $form_state->getValue('junior_estimate'),
              'field_junior_time' => $form_state->getValue('junior_time'),
              'field_task_description' => $form_state->getValue('task_description'),
              'field_status' => $form_state->getValue('status'),
          ]);
      }
      $node->save();
      \Drupal::messenger()->addMessage($this->t('Task has been saved successfully.'));

      $form_state->setRedirect('entity.node.canonical', ['node' => $node->id()]);
  }

  public function finishTask(array &$form, FormStateInterface $form_state) {
    $node = $form_state->get('node');

    if ($node instanceof Node) {
        $node->set('field_status', 'completed');
        $node->save();
        \Drupal::messenger()->addMessage($this->t('Task has been marked as completed.'));
        $form_state->setRedirect('entity.node.canonical', ['node' => $node->id()]);
    } else {
        \Drupal::messenger()->addError($this->t('An error occurred while completing the task.'));
        $form_state->setRedirect('<front>');
    }
}
}
