<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace Member\Form;

use Zend\Form\Form;

class MemberForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('login');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');
        
        $this->add(array(
            'name' => 'user_name',
            'type' => 'text',
            'required' => true,
            'attributes' => array(
                'class' => 'form-control',
                           'id' => 'user_name'
            )
        ));
        
        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'required' => true,
            'attributes' => array(
                'class' => 'form-control',
                           'id' => 'password'
            ) 
        ));
        
        $this->add(array(
            'name' => 'changepassword',
            'type' => 'password',
            'required' => true,
            'attributes' => array(
                'class' => 'form-control',
                           'id' => 'changepassword'
            ) 
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'btn btn-success btn-lg',               
            ),
        ));
      }
}