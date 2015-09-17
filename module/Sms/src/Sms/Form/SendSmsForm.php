<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace Sms\Form;

use Zend\Form\Form;

class SendSmsForm extends Form
{
    protected $dbAdapter;
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('sendsms');
        $this->dbAdapter = $name;
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        

        
        $this->add(array(
            'name' => 'sms_id',
            'type' => 'radio',
            'options' => array(
            'value_options' => array(
                 'template' => array(
                     'value' => '',
                     'attributes' => array(
                              'id'=>'template',
                                          ),
                                )))
        ));
        
        $this->add(array(
            'name' => 'sms_checkbox',
            'type' => 'checkbox',
            'options' => array(
            'value_options' => array(
                 'template' => array(
                     'value' => '',
                     'attributes' => array(
                              'id'=>'class_checkbox',
                                          ),
                                )))
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
     public function getAllStudent($id){
          return $this->dbAdapter->getAllStudent($id);
    }
}