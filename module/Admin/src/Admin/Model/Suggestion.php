<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Suggestion implements InputFilterAwareInterface
{
	public $id;
	public $name;
	public $suggestion_available;
	public $hitpower;
	public $sight;
	public $nsight;
	public $infra_red;
	public $thermal;
	public $stabilizer;
	public $camouflage_dress;
	
	public $created_on;
	public $active;
	protected $inputFilter;     
	
	//protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id     				= (isset($data['id'])) ? $data['id'] : null;
		$this->name 				= (isset($data['name'])) ? $data['name'] : null;
		
		$this->suggestion_available 		= (isset($data['suggestion_available'])) ? $data['suggestion_available'] : null;	
		$this->hitpower 					= (isset($data['hitpower'])) ? $data['hitpower'] : null;	
		$this->sight 						= (isset($data['sight'])) ? $data['sight'] : null;	
		$this->nsight 						= (isset($data['nsight'])) ? $data['nsight'] : null;	
		$this->infra_red 					= (isset($data['infra_red'])) ? $data['infra_red'] : null;	
		$this->thermal 						= (isset($data['thermal'])) ? $data['thermal'] : null;	
		$this->stabilizer 					= (isset($data['stabilizer'])) ? $data['stabilizer'] : null;	
		$this->camouflage_dress 			= (isset($data['camouflage_dress'])) ? $data['camouflage_dress'] : null;	
		
		$this->created_on 			= (isset($data['created_on'])) ? $data['created_on'] : null;
      	$this->active  				= (isset($data['active'])) ? $data['active'] : null;
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
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'hitpower',
                'required' => true, 
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),



				
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'camouflage_dress',
                'required' => true,  
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),

            )));
			
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'nsight',
                'required' => true,  
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),

            )));


			$inputFilter->add($factory->createInput(array(
                'name'     => 'sight',
                'required' => true,  
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),

            )));

			$inputFilter->add($factory->createInput(array(
                'name'     => 'infra_red',
                'required' => true,  
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),

            )));

			$inputFilter->add($factory->createInput(array(
                'name'     => 'thermal',
                'required' => true,  
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),

            )));

			$inputFilter->add($factory->createInput(array(
                'name'     => 'stabilizer',
                'required' => true,  
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),

            )));			
			
			
			

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
	
	
	
}