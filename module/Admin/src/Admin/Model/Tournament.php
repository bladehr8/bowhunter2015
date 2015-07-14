<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Tournament 
{
    public $id;
	public $name;
	public $duration;
	public $start_date;
	public $end_date;
	public $tournament_type;
	public $entry_points;
	public $created_on;
	public $modified_on;
	public $active;
	
	
	protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id     				= (isset($data['id'])) ? $data['id'] : null;
		$this->name     			= (isset($data['name'])) ? $data['name'] : null;
		$this->duration     		= (isset($data['duration'])) ? $data['duration'] : null;
		$this->start_date 			= (isset($data['start_date'])) ? $data['start_date'] : null;
		$this->end_date 			= (isset($data['end_date'])) ? $data['end_date'] : null;
		$this->tournament_type 		= (isset($data['tournament_type'])) ? $data['tournament_type'] : null;
		$this->entry_points 		= (isset($data['entry_points'])) ? $data['entry_points'] : null;
		$this->created_on  			= (isset($data['created_on'])) ? $data['created_on'] : null;
		$this->modified_on  		= (isset($data['modified_on'])) ? $data['modified_on'] : null;
      	$this->active  				= (isset($data['active'])) ? $data['active'] : null;
		
		
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
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));
/*
            $inputFilter->add($factory->createInput(array(
                'name'     => 'name',
                'required' => true,     
            )));
	*/		
			 $inputFilter->add($factory->createInput(array(
                'name'     => 'duration',
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