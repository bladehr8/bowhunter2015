<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Tournamentmission 
{
    public $id;
	public $name;
	public $regionType;
	public $startDate;
	public $endDate;
	public $tournamentmissionType;
	public $minPointForEntry;
	public $createOn;
	public $modifyOn;
	public $active;
	
	
	//protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id     		= (isset($data['id'])) ? $data['id'] : null;
		$this->name     		= (isset($data['name'])) ? $data['name'] : null;
		$this->regionType     		= (isset($data['regionType'])) ? $data['regionType'] : null;
		$this->startDate 	= (isset($data['startDate'])) ? $data['startDate'] : null;
		$this->endDate 	= (isset($data['endDate'])) ? $data['endDate'] : null;
		$this->tournamentmissionType 	= (isset($data['tournamentmissionType'])) ? $data['tournamentmissionType'] : null;
		$this->minPointForEntry 	= (isset($data['minPointForEntry'])) ? $data['minPointForEntry'] : null;
		$this->createOn  		= (isset($data['createOn'])) ? $data['createOn'] : null;
		$this->modifyOn  		= (isset($data['modifyOn'])) ? $data['modifyOn'] : null;
      	$this->active  		= (isset($data['active'])) ? $data['active'] : null;
		
		
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

            $inputFilter->add($factory->createInput(array(
                'name'     => 'fieldName',
                'required' => true,     
            )));
			 $inputFilter->add($factory->createInput(array(
                'name'     => 'fieldType',
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