<?php

namespace Drupal\my_crud\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Messenger;
use Drupal\Core\Link;

class NoteForm extends FormBase {

public function getFormid(){
  return 'note_form';
}

public function buildform(array $form, FormStateInterface $form_state){
  $conn = Database::getConnection();
  $record = [];
  if(isset($_GET['id'])) {
    $query= $conn->select('note', 'm')->condition('id', $_GET['id'])->fields('m');
    $record=$query->execute()->fetchAssoc();
  }
  $form['title']=['#type'=>'textfield', 'title'=>t('Title'), '#required'=>TRUE, '#default_value'=>(
    isset($record['title']) && $_GET['id'])? $record['title']:'',];
  $form['time']=['#type'=>'textfield', 'title'=>t('Time'), '#required'=>TRUE, '#default_value'=>(
      isset($record['time']) && $_GET['id'])? $record['time']:'',];
  $form['text']=['#type'=>'textfield', 'title'=>t('Text'), '#required'=>TRUE, '#default_value'=>(
      isset($record['text']) && $_GET['id'])? $record['text']:'',];
  $form['action']=['#type'=>'action',];

  $form['action']['submit']=['#type'=>'submit','#value'=> t('Save'),];

  $form['action']['reset']=['#type'=>'button','#value'=>t('Reset'), '#attributes'=>['onclick'=>'
  this.form.reset(); return false;',],];

  $link = Url::fromUserInput('/note/');

  $form['action']['cancel'] = ['#markup'=>Link::fromTextAndUrl(t('Back to page'),$link,['
  attributes'=>['class'=>'button']])->toString(),];
  return $form;
}

public function validateForm(array &$form, FormStateInterface $form_state){
    $title = $form_state->getValue('title');

    if(preg_match('/[^A-Za-z]/', $title)) {
      $form_state->setErrorByName('title', $this->t('Name must be in Characters Only'));

    }
    $time = $form_state->getValue('time');
    if(!preg_match('/[^A-Za-z]/', $time)) {
      $form_state->setErrorByName('time', $this->t('Time must be in Numbers only'));
    }
    $text = $form_state->getValue('text');
    if(!preg_match('/[^A-Za-z]/', $text)) {
      $form_state->setErrorByName('text', $this->t('Text must be in Characters only'));
    }

parent::validateForm($form, $form_state);

  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $field = $form_state->getValues();

    $title= $field['title'];
    $time = $field['time'];
    $text = $field['text'];

    if(isset($_GET['id']))
    {
      $field = ['title'=>$title, 'time'=>$time, 'text'=>$text];

      $query = \Drupal::database();
      $query->update('note')->fields($field)->condition('id', $_GET['id'])->execute();
      $this->messenger()->addMessage('Successfully updated notes');
    }
    else
    {
      $field = ['title'=>$title, 'time'=>$time,'text'=>$text,];
      $query = \Drupal::database();
      $query->insert('note')->fields($field)->execute();
      $this->messenger()->addMessage('Successfully saved notes');
    }

$form_state->setRedirect('note.note_controller_listing');
  }

}
