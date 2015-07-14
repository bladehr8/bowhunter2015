<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class DeerinfoForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
		parent::__construct('deerinfo');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-deerinfo');
		
		$this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

	 $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'deer_activity_id',
			 'attributes' => array(
				'required'  => 'required',
				'class'  => 'form-control',
            ),
             'options' => array(
							'label' => '',
                            'value_options' => array(),
             )
     ));
		
		 $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'deer_size_id',
			 'attributes' => array(
				'required'  => 'required',
				'class'  => 'form-control',
            ),
             'options' => array(
							'label' => '',
                            'value_options' => array(),
             )
     ));	
		
		
		
			 $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'deer_facing_id',
			 'attributes' => array(
				'required'  => 'required',
				'class'  => 'form-control',
            ),
             'options' => array(
							'label' => '',
                            'value_options' => array(),
             )
     ));
		
		
	$this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'kill_for_success',
			 'attributes' => array(
				'required'  => 'required',
				'class'  => 'form-control',
            ),
             'options' => array(
							'label' => '',
                            'value_options' => array('1' => 'Success' , '2' => 'Failure'),
             )
     ));
		
		
		
		
		
		
		
		
		
		
		
			 $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'deer_position_id',
			 'attributes' => array(
				'required'  => 'required',
				'class'  => 'form-control',
            ),
             'options' => array(
							'label' => '',
                            'value_options' => array(),
             )
     ));
		
		
		
		
		
		
		

        $this->add(array(
            'name' => 'min_start_range',			
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Enter min start range",
				'required'  => 'required',
            ),
			
    
        ));
		
		
		
	$this->add(array(
		'name' => 'max_start_range',
		'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Enter max start range",
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
				'class'  => 'btn btn-bricky custom-form-control',
                'value' => 'Save',
                'id' => 'submitbutton',
            ),
        ));
       
    }
}