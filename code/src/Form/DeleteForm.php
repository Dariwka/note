<?php

/**
 * @file
 * Install, update and uninstall functions for the note module.
 */
namespace Drupal\note\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Messenger;
use Drupal\Core\Link;


class DeleteForm extends ConfirmFormBase {

  public function getformId(){
    return 'delete_form';
  }

  public $cid;

  public function getQuestion() {
    return t('Delete Note?');
  }

  public function getCancelUrl(){
    return new Url('note.note_controller_listing');


  }

  public function getDescription() {

    return t('Are you sure you want to delete record?');
  }

  public function getConfirmText() {

    return t('Delete it');
  }

  public function getCancelText(){
    return t('Cancel');
  }

  public function buildForm(array $form, FormStateInterface $form_state, $cid = NULL)
  {
    $this->id = $cid;
    return parent::buildForm($form, $form_state);
  }

  public function validateForm(array &$form, FormStateInterface $form_state){
    parent::validateForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state){
    $query = \Drupal::database();
    $query->delete('note')->condition('id',$this->id)->execute();

    $this->messenger()->addMessage('Successfully deleted note');
    $form_state->setRedirect('note.note_controller_listing');

  }

}
