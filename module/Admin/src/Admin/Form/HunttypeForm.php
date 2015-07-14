<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class HunttypeForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
		parent::__construct('hunttype');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-hunttype');
		
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
				'placeholder' => "Enter hunt type",
				'required'  => 'required',
            ),	
    
        ));
		
		
	$this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'hunt_class_id',
			 'attributes' => array(
				'required'  => 'required',
				'class'  => 'form-control',
            ),
             'options' => array(
							'label' => '',
                            'value_options' => array('1' => 'Progression' , '2' => 'Cash' , '3' => 'Invitational' , '4' => 'Tournament'),
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
				'class'  => 'btn btn-primary',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
       
    }
}