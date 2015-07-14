<?php
namespace Application\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class HuntClubGame implements InputFilterAwareInterface
{
    public $id;
    public $hunt_clubs_id;
	public $light_conditions_id;
	public $regions_id;
	public $number_of_animals;
	public $time_to_complete;

	public $created_on;
	public $active;
	protected $inputFilter;     
	
	//protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id     					= (isset($data['id'])) ? $data['id'] : null;
		$this->hunt_clubs_id 			= (isset($data['hunt_clubs_id'])) ? $data['hunt_clubs_id'] : null;
		$this->light_conditions_id		= (isset($data['light_conditions_id'])) ? $data['light_conditions_id'] : null;
		$this->regions_id  				= (isset($data['regions_id'])) ? $data['regions_id'] : null;
		$this->number_of_animals  		= (isset($data['number_of_animals'])) ? $data['number_of_animals'] : null;
		$this->time_to_complete  		= (isset($data['time_to_complete'])) ? $data['time_to_complete'] : null;

		
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
                'name'     => 'hunt_clubs_id',
				'required' => true,
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'light_conditions_id',
				'required' => true,
            )));			
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'regions_id',
				'required' => true,
            )));			
			
			
			

            $inputFilter->add($factory->createInput(array(
                'name'     => 'time_to_complete',
                'required' => true,     
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'number_of_animals',
                'required' => true, 

            )));
			
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
	
	
	
}