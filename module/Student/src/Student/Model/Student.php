<?php
namespace Student\Model;
// Add these import statements
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
class Student implements InputFilterAwareInterface
{
    public $id;
    public $class;
    public $student_name;
    public $father_name;
    public $mother_name;
    public $admission_no;
    public $roll_no;
    public $parent_mobile_1;
    public $parent_mobile_2;
    public $student_update_date;
    protected $inputFilter;                       // <-- Add this variable

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->student_name  = (isset($data['student_name'])) ? $data['student_name'] : null;
        $this->father_name  = (isset($data['father_name'])) ? $data['father_name'] : null;
        $this->mother_name  = (isset($data['mother_name'])) ? $data['mother_name'] : null;
        $this->admission_no  = (isset($data['admission_no'])) ? $data['admission_no'] : null;
        $this->class = (isset($data['class'])) ? $data['class'] : null;
        $this->roll_no  = (isset($data['roll_no'])) ? $data['roll_no'] : null;
        $this->parent_mobile_1  = (isset($data['parent_mobile_1'])) ? $data['parent_mobile_1'] : null;
        $this->parent_mobile_2  = (isset($data['parent_mobile_2'])) ? $data['parent_mobile_2'] : null;
        $this->student_update_date  = (isset($data['student_update_date'])) ? $data['student_update_date'] : null;
    }
    
    // Add content to these methods:
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'class',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                      'name' =>'NotEmpty', 
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Please Select Class!' 
                            ),
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'father_name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                   array(
                      'name' =>'NotEmpty', 
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Please enter Father Name!' 
                            ),
                        ),
                    ),
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                            'messages' => array(
                                'stringLengthTooShort' => 'Please enter Father Name between 1 to 100 character!', 
                                'stringLengthTooLong' => 'Please enter Father Name between 1 to 100 character!', 
                            ),
                        ),
                    ),
                ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'mother_name',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'student_name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                   array(
                      'name' =>'NotEmpty', 
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Please enter Student Name!', 
                            ),
                        ),
                    ),
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                            'messages' => array(
                                'stringLengthTooShort' => 'Please enter Student Name between 1 to 100 character!', 
                                'stringLengthTooLong' => 'Please enter Student Name between 1 to 100 character!', 
                            ),
                        ),
                    ),
                ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'admission_no',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'roll_no',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                      'name' =>'NotEmpty', 
                        'options' => array(
                            'messages' => array(
                                'isEmpty' => 'Please enter Student Roll No!', 
                            ),
                            'class' => 'your-class-here',
                        ),
                    ),
                 ),
            )));
            
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
     // Add the following method:
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}