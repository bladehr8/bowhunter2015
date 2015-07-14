<?php
namespace Application\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class CustomerSupport implements InputFilterAwareInterface
{
    public $id;
    public $name;
	public $email;

	public $contactno;
	public $subject;

	protected $inputFilter;     
	
	//protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id     			= (isset($data['id'])) ? $data['id'] : null;
		$this->name 			= (isset($data['name'])) ? $data['name'] : null;
		$this->email			= (isset($data['email'])) ? $data['email'] : null;		
		$this->contactno 		= (isset($data['contactno'])) ? $data['contactno'] : null;
      	$this->subject  		= (isset($data['subject'])) ? $data['subject'] : null;

		
    }
	
	public function getArrayCopy()
    {
        return get_object_vars($this);
    }


	
	// Add content to this method:
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
                'name'     => 'name',
                'required' => true,     
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'email',
                'required' => true,     
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'contactno',
                'required' => true,     
            )));			
			
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'subject',
                'required' => true,     
            )));			
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'short_note',
                'required' => true,     
            )));				
			
			
			


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
	
	
	
}