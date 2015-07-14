<?php
namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class FaceColor implements InputFilterAwareInterface
{
    public $id;
    public $name;
	public $active;
	protected $dbAdapter;
	
	protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id     		= (isset($data['id'])) ? $data['id'] : null;
		$this->name 		= (isset($data['name'])) ? $data['name'] : null;
      	$this->active  		= (isset($data['active'])) ? $data['active'] : null;
    }
	
	public function getArrayCopy()
    {
        return get_object_vars($this);
    }
	
	public function setDbAdapter($dbAdapter) {
		$this->dbAdapter = $dbAdapter;
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
                            'min'      => 3,
                            'max'      => 100,
                        ),
                    ),
					
					array(
                    'name'    => '\Zend\Validator\Db\NoRecordExists',
                    'options' => array(
                        'table' => 'facecolor',
                        'field' => 'name',
                        'adapter' => $this->dbAdapter,
						'messages' => array(
								\Zend\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => 'This value is already defined',
							),

                    ),
                ),					
					
					
					
					
					
					
					
					
					
					
                ),
            )));



            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
	
	
	
}