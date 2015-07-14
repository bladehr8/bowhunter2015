<?php
namespace Admin\Model;

use Zend\Http\PhpEnvironment\Request;
use Zend\Filter;





use Zend\InputFilter\Input;
use Zend\InputFilter\FileInput;
use Zend\Filter\File\RenameUpload;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Zend\Validator\File\MimeType;
use Zend\Form\Element\File;

use Zend\Validator;










use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class PlayerRank implements InputFilterAwareInterface
{
    public $id;
    public $name;
	public $active;
	public $min_exp;
	public $max_exp;
	public $field_icon;
	
	
	protected $dbAdapter;
	
	protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id     			= (isset($data['id'])) ? $data['id'] : null;
		$this->name 			= (isset($data['name'])) ? $data['name'] : null;
		$this->min_exp 			= (isset($data['min_exp'])) ? $data['min_exp'] : null;
		$this->max_exp 			= (isset($data['max_exp'])) ? $data['max_exp'] : null;
		$this->field_icon 		= (isset($data['field_icon'])) ? $data['field_icon'] : null;
      	$this->active  			= (isset($data['active'])) ? $data['active'] : null;
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
					/*
					array(
                    'name'    => '\Zend\Validator\Db\NoRecordExists',
                    'options' => array(
                        'table' => 'player_rank_system',
                        'field' => 'name',
                        'adapter' => $this->dbAdapter,
						'messages' => array(
								\Zend\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => 'This value is already defined',
							),

                    ),
					),					
					*/

                ),
            )));
			
			 $inputFilter->add($factory->createInput(array(
                'name'     => 'field_icon',
                   
            )));
			
			
			



            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
	
	
	
}