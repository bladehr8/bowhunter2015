<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Missions
{
    public $id;
	public $hunt_suggestion_id;
	public $shorthand_target_type_id;
	public $hunt_types_id;
	public $player_position_id;
	public $region_id;
	public $light_conditions_id;
	public $kill_type_id;
	public $mission_objectives_id;
	//public $deer_information_id;
	
	public $name;
	public $minimum_points_required;
	public $duration;
	public $recommended_gear;
	public $difficulty_level;
	public $no_more_hunts;
	
	public $kill_for_success;
	public $premium_currency_rewarded;
	public $normal_currency_rewarded;
	public $xp_rewarded;
	
	public $created_on;
	public $active;
	protected $inputFilter;   
	//protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id     		= (isset($data['id'])) ? $data['id'] : null;
		
		$this->hunt_suggestion_id 			= (isset($data['hunt_suggestion_id'])) ? $data['hunt_suggestion_id'] : null;
		$this->shorthand_target_type_id 	= (isset($data['shorthand_target_type_id'])) ? $data['shorthand_target_type_id'] : null;
		
		$this->hunt_types_id 			= (isset($data['hunt_types_id'])) ? $data['hunt_types_id'] : null;
		$this->player_position_id 		= (isset($data['player_position_id'])) ? $data['player_position_id'] : null;
		$this->region_id 				= (isset($data['region_id'])) ? $data['region_id'] : null;
		$this->light_conditions_id 		= (isset($data['light_conditions_id'])) ? $data['light_conditions_id'] : null;
		$this->kill_type_id 			= (isset($data['kill_type_id'])) ? $data['kill_type_id'] : null;
		
		$this->mission_objectives_id 	= (isset($data['mission_objectives_id'])) ? $data['mission_objectives_id'] : null;
		//$this->deer_information_id 		= (isset($data['deer_information_id'])) ? $data['deer_information_id'] : null;

		
		
		
		
		
		
		$this->name 						= (isset($data['name'])) ? $data['name'] : null;
		$this->minimum_points_required 		= (isset($data['minimum_points_required'])) ? $data['minimum_points_required'] : null;
		$this->duration 					= (isset($data['duration'])) ? $data['duration'] : null;
		$this->recommended_gear 			= (isset($data['recommended_gear'])) ? $data['recommended_gear'] : null;
		$this->difficulty_level 			= (isset($data['difficulty_level'])) ? $data['difficulty_level'] : null;	
		$this->no_more_hunts 				= (isset($data['no_more_hunts'])) ? $data['no_more_hunts'] : null;
		
		
		$this->kill_for_success     		= (isset($data['kill_for_success'])) ? $data['kill_for_success'] : null;
		$this->premium_currency_rewarded    = (isset($data['premium_currency_rewarded'])) ? $data['premium_currency_rewarded'] : null;
		$this->normal_currency_rewarded 	= (isset($data['normal_currency_rewarded'])) ? $data['normal_currency_rewarded'] : null;
		$this->xp_rewarded 					= (isset($data['xp_rewarded'])) ? $data['xp_rewarded'] : null;
		

		$this->createOn  					= (isset($data['createOn'])) ? $data['createOn'] : null;		
      	$this->active  						= (isset($data['active'])) ? $data['active'] : null;
		
		
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

      
			/*
            $inputFilter->add($factory->createInput(array(
                'name'     => 'fieldName',
                'required' => true,
                'filters'  => array(
                    array('fieldName' => 'StripTags'),
					array('fieldName' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'fieldName'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));
		*/
		
            $inputFilter->add($factory->createInput(array(
                'name'     => 'name',
                'required' => true,     
            )));
			
			 $inputFilter->add($factory->createInput(array(
                'name'     => 'minimum_points_required',
                'required' => true,     
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'duration',
                'required' => true,     
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'difficulty_level',
                'required' => true,     
            )));
			
			
			

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
	
	public function getArrayCopy()
    {
        return get_object_vars($this);
    }

	
	
	
}