<?php

namespace Drupal\sjhuda_ief\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DefaultForm.
 */
class DefaultForm extends FormBase {
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Ignore the 'name_field' and 'num_names'. They will need to be renamed.
    $i = 0;
    $name_field = $form_state->get('num_names');
    $form['#tree'] = TRUE;
    $form['sections_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Sections'),
      '#prefix' => '<div id="names-fieldset-wrapper">',
      '#suffix' => '</div>',
    ];
    // Check if we have any sections, if not - set it to 1.
    if (empty($name_field)) {
      $name_field = $form_state->set('num_names', 1);
    }
    // Loop through the sections
    for ($i = 0; $i < $name_field; $i++) {
      $form['sections_fieldset'][$i] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Details'),
        '#prefix' => '<div id="names-fieldset-wrapper-details">',
        '#suffix' => '</div>',
      ];
      $form['sections_fieldset'][$i]['name'][$i] = [
        '#type' => 'textfield',
        '#title' => t('Name'),
      ];
      $form['sections_fieldset'][$i]['text'][$i] = [
        '#type' => 'text_format',
        '#title' => t('Description'),
        '#format' => 'full_html',
      ];
    }
    $form['actions'] = [
      '#type' => 'actions',
    ];
    // Adds the 'add' button and ajax callback
    $form['sections_fieldset']['actions']['add_name'] = [
      '#type' => 'submit',
      '#value' => t('Add section'),
      '#submit' => array('::addOne'),
      '#ajax' => [
        'callback' => '::addmoreCallback',
        'wrapper' => 'names-fieldset-wrapper',
      ],
    ];
    // if we have more than one;
    // Adds the 'remove' button and ajax callback
    if ($name_field > 1) {
      $form['sections_fieldset']['actions']['remove_name'] = [
        '#type' => 'submit',
        '#value' => t('Remove previous section'),
        '#submit' => array('::removeCallback'),
        '#ajax' => [
          'callback' => '::addmoreCallback',
          'wrapper' => 'names-fieldset-wrapper',
        ]
      ];
    }
    $form_state->setCached(FALSE);
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  public function getFormId() {
    return 'fapi_example_ajax_addmore';
  }

  public function addOne(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('num_names');
    $add_button = $name_field + 1;
    $form_state->set('num_names', $add_button);
    $form_state->setRebuild();
  }

  public function addmoreCallback(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('num_names');
    return $form['sections_fieldset'];
  }

  public function removeCallback(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('num_names');
    if ($name_field > 1) {
      $remove_button = $name_field - 1;
      $form_state->set('num_names', $remove_button);
    }
    $form_state->setRebuild();
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValue(array('sections_fieldset'));

    // loop through results and create special section entity from them
  }

}
