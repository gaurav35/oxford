<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Student\Form;

use Zend\Form\Form;

class StudentForm extends Form {

    public $dbAdapter;

    public function __construct($name = null) {
        // we want to ignore the name passed
        parent::__construct('student');
        $this->dbAdapter = $name;
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'type' => 'Select',
            'name' => 'class',
            'attributes' => array(
                'id' => 'form_class',
                'class' => 'form-control required',
            ),
            'required' => true,
            'options' => array(
                'value_options' => $this->getOptionsForSelect(),
            ),
        ));
        $this->add(array(
            'name' => 'student_name',
            'required' => true,
            'type' => 'text',
            'placeholder' => 'Please enter your name.',
            'attributes' => array(
            'class' => 'form-control',
                'id' => 'form_student_name'
                ),
        ));
        $this->add(array(
            'name' => 'father_name',
            'required' => true,
            'type' => 'text',
            'attributes' => array(
            'class' => 'form-control',
                'id' => 'form_father_name'
                ),
        ));
        $this->add(array(
            'name' => 'mother_name',
            'type' => 'text',
             'attributes' => array(
            'class' => 'form-control',
                'id' => 'form_mother_name'
                ),
        ));

        $this->add(array(
            'name' => 'admission_no',
            'type' => 'text',
             'attributes' => array(
            'class' => 'form-control',
                'id' => 'form_admission_no'
                ),
        ));

        $this->add(array(
            'name' => 'roll_no',
            'required' => true,
            'type' => 'text',
           'attributes' => array(
            'class' => 'form-control',
                'id' => 'form_roll_no'
                ),
        ));
        $this->add(array(
            'name' => 'parent_mobile_1',
            'type' => 'text',
            'attributes' => array(
            'class' => 'form-control',
                'id' => 'form_parent_mobile_1'
                ),
        ));
        $this->add(array(
            'name' => 'parent_mobile_2',
            'type' => 'text',
             'attributes' => array(
            'class' => 'form-control',
                'id' => 'form_parent_mobile_2'
                ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
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
