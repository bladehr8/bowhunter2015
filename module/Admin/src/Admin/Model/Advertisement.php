<?php
namespace Admin\Model;

use Zend\Http\PhpEnvironment\Request;
use Zend\Filter;



use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\Input;
use Zend\InputFilter\FileInput;
use Zend\Filter\File\RenameUpload;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Zend\Validator\File\MimeType;


use Zend\Validator;







use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Advertisement implements InputFilterAwareInterface
{
    public $id;
    public $advertisement_type_id;
	public $click_count;
	
	public $duration;
	public $notification;
	public $image_1;
	public $content;
	public $image_2;
	public $url;

	
	
	
	
	public $modified_on;
	public $created_on;
	public $active;
	protected $inputFilter;     
	
	//protected $inputFilter;  

    public function exchangeArray($data)
    {
        $this->id     					= (isset($data['id'])) ? $data['id'] : null;
		$this->advertisement_type_id 	= (isset($data['advertisement_type_id'])) ? $data['advertisement_type_id'] : null;	
		$this->click_count 				= (isset($data['click_count'])) ? $data['click_count'] : null;
		
		$this->duration 			= (isset($data['duration'])) ? $data['duration'] : null;	
		$this->notification 		= (isset($data['notification'])) ? $data['notification'] : null;	
		$this->image_1 				= (isset($data['image_1'])) ? $data['image_1'] : null;	
		$this->content 				= (isset($data['content'])) ? $data['content'] : null;	
		$this->image_2 				= (isset($data['image_2'])) ? $data['image_2'] : null;	
		$this->url 					= (isset($data['url'])) ? $data['url'] : null;	
		
		
		
		$this->modified_on 			= (isset($data['modified_on'])) ? $data['modified_on'] : null;
		$this->created_on 			= (isset($data['created_on'])) ? $data['created_on'] : null;
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