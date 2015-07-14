<?php
namespace Admin\Model;

use Zend\Http\PhpEnvironment\Request;
use Zend\Filter;



use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\Input;
use Zend\InputFilter\FileInput;
use Zend\Filter\File\RenameUpload;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Zend\Validator\File\MimeType;


use Zend\Validator;







use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import
 
class subscribersMail 
{
public $id;
public $mailContent;
public $date;
public $time;
public $groupOfUser;
    
public function exchangeArray($data)
{
    $this->id              = (isset($data['id']))            ? $data['id']            : null;
    $this->mailContent     = (isset($data['mailContent']))   ? $data['mailContent']   : null;
    $this->date            = (isset($data['date']))          ? $data['date']          : null;
    $this->time            = (isset($data['time']))          ? $data['time']          : null;
    $this->groupOfUser     = (isset($data['groupOfUser']))   ? $data['groupOfUser']   : null;
} 
public function getArrayCopy() 
{
     return get_object_vars($this);     
}
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
                'name'     => 'content',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 5,
                            'max'      => 1000,
                        ),
                    ),
                ),
            )));
			
			

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }          
}
?>