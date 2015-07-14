<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Weaponattrubutevalue 
{
    public $id;
	public $attribute_field_id;
    public $attribute_field_name;
	public $productId;
	public $value;
	
	
	
	//protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id     		= (isset($data['id'])) ? $data['id'] : null;
		$this->attribute_field_id     		= (isset($data['attribute_field_id'])) ? $data['attribute_field_id'] : null;
		$this->attribute_field_name 	= (isset($data['attribute_field_name'])) ? $data['attribute_field_name'] : null;
		$this->productId 	= (isset($data['productId'])) ? $data['productId'] : null;
      	$this->value  		= (isset($data['value'])) ? $data['value'] : null;
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
	
	
	
}