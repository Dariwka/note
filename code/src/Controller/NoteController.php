<?php

/**
 * @file
 * Install, update and uninstall functions for the note module.
 */

 namespace Drupal\note\Controller;

 use Drupal\Core\Controller\ControllerBase;
 use Drupal\Core\Database\Database;
 use Drupal\Core\Url;
 use Drupal\Core\Link;


 class MycrudController extends ControllerBase {

   public function Listing() {

     // table apache_request_headers
    $header_table = ['id'=>t('ID'), 'title'=>t('Title'), 'time'=>t('Time'), 'text'=>t('Text'),'
    opt1'=> t('Operation'),];
    $row = [];
    $conn = Database::getConnection();
    $query = $conn->select('note', 'm');
    $query->fields('m', ['id', 'title', 'time', 'text']);
    $result = $query->execute()->fetchAll();

    foreach($result as $value)
    {
      $delete = Url::fromUserInput('/notes/form/delete/'.$value->id);
      $edit = Url::fromUserInput('/notes/form/data?id='.$value->id);

      $row[]=['id'=>$value->id, 'title'=>$value->title, 'time'=>$value->time, 'text'=>$value->text,'opt'=>Link::
          fromTextAndUrl('Edit', $edit)->toString(), 'opt1'=>Link::fromTextAndUrl('Delete',$delete)->toString(),];
    }
    $add = Url::fromUserInput('/notes/form/data');
    $text = "Add Note";

    $data['table'] = ['#type'=>'table', '#header'=>$header_table, '#rows'=>$row,'#empty'=>t('
    No record found'), '#caption'=>Link::fromTextAndUrl($text, $add)->toString(),];

    $this->messenger()->addMessage('Notes added successfully');
    return $data;
   }
 }
