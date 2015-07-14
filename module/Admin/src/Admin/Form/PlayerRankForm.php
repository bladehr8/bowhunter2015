<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class PlayerRankForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('playerrank');
        $this->setAttribute('method', 'post');
		$this->setAttribute('enctype' , 'multipart/form-data');
		$this->setAttribute('class', 'form-playerrank');
		
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
				'placeholder' => "Enter rank badges",
				'required'  => 'required',
            ),
			
    
        ));
		
		
        $this->add(array(
            'name' => 'min_exp',			
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Enter minimum experience points",
				'required'  => 'required',
            ),	
    
        ));		
		
        $this->add(array(
            'name' => 'max_exp',			
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Enter maximum experience points",
				'required'  => 'required',
            ),	
    
        ));			
		
		
	$this->add(array(
    'type' => 'Zend\Form\Element\File',
    'name' => 'field_icon',
    'attributes' => array(
        'class' => 'file-input',		
    )
	));		
		
		
		
		
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'active',
			
			 'attributes' => array(
				'required'  => 'required',
				'class' => 'radio-inline',
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
				'class'  => 'btn btn-primary',
                'value' => 'Save',
                'id' => 'submitbutton',
            ),
        ));
       
    }
}