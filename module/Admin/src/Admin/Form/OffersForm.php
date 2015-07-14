<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class OffersForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
		parent::__construct('offers');
		$this->setAttribute('method', 'post');
		$this->setAttribute('enctype' , 'multipart/form-data');
		$this->setAttribute('class', 'form-offers');
		
		$this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));


        $this->add(array(
            'name' => 'title',			
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Enter offers title",
				'required'  => 'required',
            ),	
    
        ));
		
		 $this->add(array(
        'name'      => 'short_note',
        'attributes'=> array(
								'id'    => 'summary',
								'type'  => 'textarea',
								'class' => 'form-control',
								'placeholder' => "Enter short description here",
								),

		));	
		
		
		
	        $this->add(array(
            'name' => 'external_link',			
			  'attributes' => array(
                'type'  => 'url',
				'class'  => 'form-control',
				'placeholder' => "Enter external url i.e. http://demo.com",
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