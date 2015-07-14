<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class DeerFacingForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
		parent::__construct('deerfacing');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-deerfacing');
		
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
				'placeholder' => "Enter deer facing value",
				'required'  => 'required',
            ),	
    
        ));
		

        $this->add(array(
            'name' => 'exact_angle_range',			
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Exact angle rangle(i.e  9-11)",
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