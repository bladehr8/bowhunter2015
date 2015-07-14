<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class CustomerSupportForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
		parent::__construct('customersupport');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'customersupport');
		
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
				'placeholder' => "Your Name",
				'id' => 'name',
				'required'  => 'required',				
				'pattern' => "\b\w+\b$",
            ),	
    
        ));
		
        $this->add(array(
            'name' => 'email',			
			  'attributes' => array(
                'type'  => 'email',
				'class'  => 'form-control',
				'placeholder' => "Your E-mail",
				'id' => 'email',
				'required'  => 'true',
				'pattern' => "^\w+([.-]?\w+)*@\w+([.-]?\w+)*(.\w{2,3})+$",
            ),	
    
        ));
		
        $this->add(array(
            'name' => 'contactno',			
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Contact Number",
				'required'  => 'true',
				'id' => 'contactno',	
            ),	
    
        ));		
		
		
	        $this->add(array(
            'name' => 'subject',			
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Your Subject",
				'required'  => 'true',
				 'id' => 'subject',	
            ),	
    
        ));		
			
		 $this->add(array(
        'name'      => 'short_note',
        'attributes'=> array(
								'id'    => 'summary',
								'type'  => 'textarea',
								'class' => 'form-control',
								'placeholder' => "Your Content",
								),

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