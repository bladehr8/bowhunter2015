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

class AdReport implements InputFilterAwareInterface
{
    public $id;
    public $user_id;
	public $add_id;	
	public $click_count;
	public $view_count;
	//public $date;
	public $status;

	protected $inputFilter;     
	
	//protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id     				= (isset($data['id'])) ? $data['id'] : null;
		$this->user_id 				= (isset($data['user_id'])) ? $data['user_id'] : null;	
		$this->add_id 				= (isset($data['add_id'])) ? $data['add_id'] : null;
		
		$this->click_count 			= (isset($data['click_count'])) ? $data['click_count'] : null;	
		$this->view_count 			= (isset($data['view_count'])) ? $data['view_count'] : null;	
		//$this->date 				= (isset($data['date'])) ? $data['date'] : null;	
		$this->status 				= (isset($data['content'])) ? $data['content'] : null;	

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
	/*
	public function addInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();

        // File Input
        $fileInput = new InputFilter\FileInput('image-1');
        $fileInput->setRequired(true);
        $fileInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'    => dirname(__DIR__).'/assets/avatar.png',
                'randomize' => true,
            )
        );
        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
    }
	
	*/
	
	
	
	
	
	
	
	
	
	

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
		
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();
			

			

            $inputFilter->add($factory->createInput(array(
                'name'     => 'duration',
                'required' => true,   
				'filters'  => array(
										array('name' => 'Int'),
									),
			    'validators' => array(
										  array(	
											  'name' => 'Between',
											  'options' => array(
																	'min' => 1,
																	'max' => 1000,
																),
												),
									),	
				
				
				
				
				

				
            )));
			
			$inputFilter->add($factory->createInput(array(
                'name'     => 'image_1',
                'required' => true,  

            )));
			

		$inputFilter->add($factory->createInput(array(
                'name'     => 'content',
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
                            'max'      => 500,
                        ),
                    ),
                ),
            )));
			
			

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
	
	
	
}