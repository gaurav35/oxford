<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Student\Model;
// Add these import statements
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
class Studentupload implements InputFilterAwareInterface
{
    public $class;
    public $fileupload;
    protected $inputFilter;                       // <-- Add this variable

    public function exchangeArray($data)
    {
        $this->class = (isset($data['class'])) ? $data['class'] : null;
        $this->fileupload  = (isset($data['fileupload']))  ? $data['fileupload']     : null;
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

            $inputFilter->add(
                $factory->createInput(array(
                    'name'     => 'fileupload',
                    'required' => true,
                ))
            );
            
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