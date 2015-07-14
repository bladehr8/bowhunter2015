<?php
namespace Application\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Subscribe implements InputFilterAwareInterface
{
    public $id;
    public $name;
	public $email;

	public $created_on;
	public $active;

	protected $inputFilter;     
	
	//protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id     			= (isset($data['id'])) ? $data['id'] : null;
		$this->name 			= (isset($data['name'])) ? $data['name'] : null;
		$this->email			= (isset($data['email'])) ? $data['email'] : null;		
		$this->created_on 		= (isset($data['created_on'])) ? $data['created_on'] : null;
      	$this->active  			= (isset($data['active'])) ? $data['active'] : null;

		
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


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
	
	
	
}