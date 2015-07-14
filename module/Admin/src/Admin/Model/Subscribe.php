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

class Subscribe implements InputFilterAwareInterface
{
    public $id;
    public $title;
	public $seo_title;
	public $short_note;
	public $content;	
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
		$this->seo_title 			= (isset($data['seo_title'])) ? $data['seo_title'] : null;	
		$this->short_note 			= (isset($data['short_note'])) ? $data['short_note'] : null;	
		$this->content 				= (isset($data['content'])) ? $data['content'] : null;		
		$this->field_image 			= (isset($data['field_image'])) ? $data['field_image'] : null;	

		
		
		
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
                            'min'      => 4,
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
                'name'     => 'short_note',
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
                            'max'      => 1000,
                        ),
                    ),
                ),
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
                            'max'      => 100000,
                        ),
                    ),
                ),
            )));
			
			

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
	
	
	
}