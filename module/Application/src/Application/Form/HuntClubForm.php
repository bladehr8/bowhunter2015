<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class HuntClubForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
		parent::__construct('huntclub');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'huntclub-light');
		
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
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'active',
			
			 'attributes' => array(
				'required'  => 'required',
            ),
             'options' => array(
               'label' => 'Active Status ? ',
			    'label_attributes' => array(
						'class'  => 'radio-inline'
						),
			   
			   
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
                'value' => 'Submit',
                'id' => 'submitbutton',
            ),
        ));
       
    }
}