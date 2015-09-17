<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace Sms\Form;

use Zend\Form\Form;

class SmsForm extends Form
{
    public $dbAdapter;
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('sms');
        $this->dbAdapter = $name;
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        
        $this->add(array(
            'name' => 'sms_title',
            'type' => 'text',
            'attributes' => array(
            'class' => 'form-control',
                'id' => 'form-sms-title'
                ),
             ));
        
        $this->add(array(
            'name' => 'sms_temp',
            'type' => 'textarea',
            'attributes' => array(
            'class' => 'form-control textareaclass',
                'id' => 'form-sms-temp'
                ) 
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'form-submitbutton',
                'class' => 'btn btn-primary'
                )
        ));
    }
}