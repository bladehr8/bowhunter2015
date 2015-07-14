<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class AdvertisementForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
		parent::__construct('advertisement');
		$this->setAttribute('method', 'post');
		$this->setAttribute('enctype' , 'multipart/form-data');
		$this->setAttribute('class', 'form-advertisement');
		
		$this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));


        $this->add(array(
            'name' => 'duration',			
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Enter duration in days",
				'required'  => 'required',
            ),	
    
        ));
		
	  $this->add(array(
        'name'      => 'content',
        'attributes'=> array(
								'id'    => 'summary',
								'type'  => 'textarea',
								'class' => 'form-control',
								'placeholder' => "Enter short description here",
								),

    ));	
		
		
		
	        $this->add(array(
            'name' => 'url',			
			  'attributes' => array(
                'type'  => 'text',
				'id' => 'url',
				'class'  => 'form-control',
				'placeholder' => "URL (e.g: http://www.yoursite.com) ",
				'required'  => 'required',
            ),	
    
        ));	
		
		
		
		

		
	$this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'advertisement_type_id',
			 'attributes' => array(
				'required'  => 'required',
				'class'  => 'form-control',
            ),
             'options' => array(
							'label' => '',
                            'value_options' => array('1' => 'BASIC AD' , '2' => 'TOURNAMENT AD' , '3'=>'NOTIFICATION AD' ),
             )
     ));
				
		
	$this->add(array(
    'type' => 'Zend\Form\Element\File',
    'name' => 'image_1',
    'attributes' => array(
        'class' => 'file-input',
		'required'  => 'required',
    )
));

	$this->add(array(
    'type' => 'Zend\Form\Element\File',
    'name' => 'image_2',
    'attributes' => array(
        'class' => 'file-input',
		'required'  => 'required',
    )
));


	
		
		
		

		
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'active',
			
			 'attributes' => array(
				'required'  => 'required',
            ),
             'options' => array(
               'label' => 'Active Status?',
                     'value_options' => array(
                             '0' => 'Inactive',
                             '1' => 'Active',
                     ),
             )
        ));
		$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
				'class'  => 'btn btn-bricky',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
       
    }
}