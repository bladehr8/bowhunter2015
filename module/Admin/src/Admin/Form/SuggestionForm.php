<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class SuggestionForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
		parent::__construct('suggestion');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-suggestion');
		
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
				'placeholder' => "Enter name of hunt suggestion",
				'required'  => 'required',
            ),	
    
        ));
		
	$this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'suggestion_available',
			 'attributes' => array(
				'required'  => 'required',
				'class'  => 'form-control',
            ),
             'options' => array(
							'label' => '',
                            'value_options' => array('1' => 'Yes' , '0' => 'No'),
             )
     ));

        $this->add(array(
            'name' => 'hitpower',			
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Enter value in text format",
				'required'  => 'required',
            ),	
    
        ));		

        $this->add(array(
            'name' => 'sight',			
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Enter value in text format",
				'required'  => 'required',
            ),	
    
        ));			
		
		
		
        $this->add(array(
            'name' => 'nsight',			
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Enter value in text format",
				'required'  => 'required',
            ),	
    
        ));			
		
        $this->add(array(
            'name' => 'infra_red',			
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Enter value in text format",
				'required'  => 'required',
            ),	
    
        ));	


        $this->add(array(
            'name' => 'thermal',			
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Enter value in text format",
				'required'  => 'required',
            ),	
    
        ));		


        $this->add(array(
            'name' => 'stabilizer',			
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Enter value in text format",
				'required'  => 'required',
            ),	
    
        ));		


        $this->add(array(
            'name' => 'camouflage_dress',			
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Enter suggested camouflage dress (i.e  r1-camo01 , r1-camo02 , r1-camo03 etc )",
				'required'  => 'required',
            ),	
    
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
				'class'  => 'btn btn-primary',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
       
    }
}