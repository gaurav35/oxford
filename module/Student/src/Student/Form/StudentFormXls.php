<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Student\Form;

use Zend\Form\Form;

class StudentFormXls extends Form {

    public $dbAdapter;
    
    public function __construct($name = null) {
        // we want to ignore the name passed
        parent::__construct('student');
        $this->dbAdapter = $name;
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');
        $this->add(array(
            'type' => 'Select',
            'name' => 'class',
            'attributes' => array(
                'id' => 'class',
                'class' => 'form-control'
            ),
            'required' => true,
            'options' => array(
                'value_options' => $this->getOptionsForSelect(),
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Upload Now',
                'class' => 'btn btn-primary'
            ),
        )); 
        $this->add(array(
            'name' => 'download',
            'attributes' => array(
                'type' => 'Button',
                'value' => 'Download',
               'class' => 'btn btn-primary'
            ),
        ));
        
          
        $this->add(array(
            'name' => 'fileupload',
            'attributes' => array(
                'type'  => 'file',
      
            ),
        )); 
        
        $this->add(array(
            'name' => 'reset',
                'attributes' => array(
                'type' => 'Button',
                'value' => 'Reset',
                'onclick' => 'javascript:alert(\'gURv\')',
                    'class' => 'btn btn-primary'
            ),
        ));
    }

    public function getOptionsForSelect() {
        //var_dump( get_class( $this->dbAdapter) );die;
        $dbAdapter = $this->dbAdapter;
        //$sql       = 'SELECT `id`,`class`  FROM `standard_class` where 1 ORDER BY class ASC';
        $statement = $dbAdapter->fetchAll();
        // $result    = $statement->execute();
        $selectData = array('0' => '--Select Class--');
        foreach ($statement as $res) {
            $selectData[$res->id] = $res->class;
        }
        return $selectData;
    }

}
