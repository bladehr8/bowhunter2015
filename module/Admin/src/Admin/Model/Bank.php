<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Bank implements InputFilterAwareInterface
{
    public $id;
    public $name;
	public $price;
	public $bucks;
	public $gold_coins;
	public $created_on;
	public $active;
	protected $inputFilter;     
	
	//protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id     			= (isset($data['id'])) ? $data['id'] : null;
		$this->name 			= (isset($data['name'])) ? $data['name'] : null;
		$this->bucks			= (isset($data['bucks'])) ? $data['bucks'] : null;
		$this->gold_coins 		= (isset($data['gold_coins'])) ? $data['gold_coins'] : null;
		$this->price 			= (isset($data['price'])) ? $data['price'] : 0.00;		
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

            $inputFilter->add($factory->createInput(array(
                'name'     => 'bucks',
                'required' => true,     
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'gold_coins',
                'required' => true,     
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'price',
                'required' => true,     
            )));
			
			
			
			
			
			
			

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
	
	
	
}