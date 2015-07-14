<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Clothing 
{
    public $id;
	public $setId;
    public $name;
	public $price_gold;
	public $price_bucks;
	public $region_id;
	
	public $deer_panic;
	public $player_facing_name;
	public $basic_alertness;
	public $deer_reaction_range;
	
	public $created_on;
	public $active;
	protected $inputFilter; 
	
	//protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id     			= (isset($data['id'])) ? $data['id'] : null;
		$this->setId    		= (isset($data['setId'])) ? $data['setId'] : null;
		$this->name 			= (isset($data['name'])) ? $data['name'] : null;
		
		$this->price_gold 		= (isset($data['price_gold'])) ? $data['price_gold'] : null;
		$this->price_bucks 		= (isset($data['price_bucks'])) ? $data['price_bucks'] : null;
		$this->region_id 		= (isset($data['region_id'])) ? $data['region_id'] : null;
		
		
		$this->deer_panic 				= (isset($data['deer_panic'])) ? $data['deer_panic'] : null;
		$this->player_facing_name 		= (isset($data['player_facing_name'])) ? $data['player_facing_name'] : null;
		$this->basic_alertness 			= (isset($data['basic_alertness'])) ? $data['basic_alertness'] : null;		
		$this->deer_reaction_range 		= (isset($data['deer_reaction_range'])) ? $data['deer_reaction_range'] : null;			
		
		
		
		$this->created_on  			= (isset($data['created_on'])) ? $data['created_on'] : null;
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
                'name'     => 'name',
                'required' => true,     
            )));
			 $inputFilter->add($factory->createInput(array(
                'name'     => 'price_gold',
                'required' => true,     
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
	
	
	
}