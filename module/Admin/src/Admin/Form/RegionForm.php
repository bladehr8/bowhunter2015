<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class RegionForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
		parent::__construct('region');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-region');
		
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
				'placeholder' => "Enter name",
				'required'  => 'required',
            ),	
    
        ));
		
		$this->add(array(
            'name' => 'short_key',			
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Enter Short Key",
				'required'  => 'required',
            ),	
    
        ));
		
		
		
		
		
		
		
	$this->add(array(
		'name' => 'require_xp_level',
		'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Minimum required xp level",
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