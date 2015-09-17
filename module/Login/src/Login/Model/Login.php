<?php
namespace Login\Model;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
class Login implements InputFilterAwareInterface
{
    public $user_name;
    public $password;
    public $id;
    public $salt;
    public $role;
    public $date_created;
    protected $inputFilter;                       // <-- Add this variable

    public function exchangeArray($data)
    {
        $this->user_name     = (isset($data['user_name'])) ? $data['user_name'] : null;
        $this->password     = (isset($data['password'])) ? $data['password'] : null;
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->salt     = (isset($data['salt'])) ? $data['salt'] : null;
        $this->role     = (isset($data['role'])) ? $data['role'] : null;
        $this->date_created    = (isset($data['date_created'])) ? $data['date_created'] : null;
        
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
                'name'     => 'user_name',
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
                                'isEmpty' => 'Please enter Correct Username!' 
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
                                'stringLengthTooShort' => 'Please enter Username between 1 to 100 character!', 
                                'stringLengthTooLong' => 'Please enter Username between 1 to 100 character!', 
                            ),
                        ),
                    ),
                ),
            )));
            
                    
            $inputFilter->add($factory->createInput(array(
                'name'     => 'password',
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
                                'isEmpty' => 'Please enter Correct Password!', 
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
                                'stringLengthTooShort' => 'Please enter Correct Password.', 
                                'stringLengthTooLong' => 'Please enter Correct Password.', 
                            ),
                        ),
                    ),
                ),
            )));
           $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
    
}