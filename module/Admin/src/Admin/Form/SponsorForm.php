<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class SponsorForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
		parent::__construct('sponsor');
		$this->setAttribute('method', 'post');
		$this->setAttribute('enctype' , 'multipart/form-data');
		$this->setAttribute('class', 'form-sponsor');
		
		$this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));


        $this->add(array(
            'name' => 'name',			
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Enter Sponsor name",
				'required'  => 'required',
            ),	
    
        ));
		

		
		
		
	        $this->add(array(
            'name' => 'external_url',			
			  'attributes' => array(
                'type'  => 'url',
				'class'  => 'form-control',
				'placeholder' => "Enter external url start with [ http:// ] ",
				'required'  => 'required',
            ),	
    
        ));	
		
		

		
		



		$this->add(array(
			'type' => 'Zend\Form\Element\File',
			'name' => 'field_image',
			'attributes' => array(
				'class' => 'file-input',				
			)
		));


	
		
		
		

		
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'active',
			
			 'attributes' => array(
				'required'  => 'required',
            ),
             'options' => array(
               'label' => 'Is Active ?',
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
                'value' => 'Save',
                'id' => 'submitbutton',
            ),
        ));
       
    }
}