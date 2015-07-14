<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Deerinfo implements InputFilterAwareInterface
{
    public $id;
    public $deer_activity_id;
	public $deer_size_id;
	public $deer_facing_id;
	
	public $deer_position_id;
	public $min_start_range;
	public $max_start_range;
	public $kill_for_success;
	
	
	public $created_on;
	public $active;
	protected $inputFilter;     
	
	//protected $inputFilter;  

    public function exchangeArray($data)
    {
		$this->id     						= (isset($data['id'])) ? $data['id'] : null;
		$this->deer_activity_id 			= (isset($data['deer_activity_id'])) ? $data['deer_activity_id'] : null;
		$this->deer_size_id					= (isset($data['deer_size_id'])) ? $data['deer_size_id'] : null; 
		$this->deer_facing_id 				= (isset($data['deer_facing_id'])) ? $data['deer_facing_id'] : null;
		$this->deer_position_id 			= (isset($data['deer_position_id'])) ? $data['deer_position_id'] : null;
		$this->min_start_range 				= (isset($data['min_start_range'])) ? $data['min_start_range'] : null;
		$this->max_start_range 				= (isset($data['max_start_range'])) ? $data['max_start_range'] : null;
		$this->kill_for_success 			= (isset($data['kill_for_success'])) ? $data['kill_for_success'] : null;




		$this->created_on 					= (isset($data['created_on'])) ? $data['created_on'] : null;
		$this->active  						= (isset($data['active'])) ? $data['active'] : null;
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

      
/*
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
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));
*/
            $inputFilter->add($factory->createInput(array(
                'name'     => 'min_start_range',
                'required' => true,     
            )));

		    $inputFilter->add($factory->createInput(array(
                'name'     => 'max_start_range',
                'required' => true,     
            )));	
			
			
			
			
			
			
			
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
	
	
	
}