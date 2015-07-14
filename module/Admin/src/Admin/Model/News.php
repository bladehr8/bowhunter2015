<?php
namespace Admin\Model;

use Zend\Http\PhpEnvironment\Request;
use Zend\Filter;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\Input;

use Zend\Validator;

use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class News implements InputFilterAwareInterface
{
    public $id;
    public $title;
	public $external_url;
	public $news_content;	
	public $field_image;

	public $modified_on;
	public $created_on;
	public $active;
	protected $inputFilter;     
	protected $dbAdapter;
	//protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id     				= (isset($data['id'])) ? $data['id'] : null;
		$this->title 				= (isset($data['title'])) ? $data['title'] : null;	
		$this->external_url 		= (isset($data['external_url'])) ? $data['external_url'] : null;	
		$this->news_content 		= (isset($data['news_content'])) ? $data['news_content'] : null;		
		//$this->field_image 			= (isset($data['field_image'])) ? $data['field_image'] : null;	

		
		
		
		$this->modified_on 			= (isset($data['modified_on'])) ? $data['modified_on'] : null;
		$this->created_on 			= (isset($data['created_on'])) ? $data['created_on'] : null;
      	$this->active  				= (isset($data['active'])) ? $data['active'] : null;
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
                'name'     => 'title',
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
                            'max'      => 50,
                        ),
                    ),
					
					/*
					array(
                    'name'    => '\Zend\Validator\Db\NoRecordExists',
                    'options' => array(
                        'table' => 'pages',
                        'field' => 'title',
                        'adapter' => $this->dbAdapter,
						'messages' => array(
								\Zend\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => 'Page name is already defined',
							),

                    ),
                ),						
					*/
	
                ),
            )));
			
		$inputFilter->add($factory->createInput(array(
                'name'     => 'external_url',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),

            )));

			

		$inputFilter->add($factory->createInput(array(
                'name'     => 'news_content',
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
                            'max'      => 5000,
                        ),
                    ),
                ),
            )));
			
			

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
	
	
	
}