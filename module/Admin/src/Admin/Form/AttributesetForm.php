<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class AttributesetForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('attributeset');
        $this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-attributeset');

        $this->add(array(
            'name' => 'name',
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Attribute Set Name",
				'required'  => 'required',
            ),
    
        ));
		
	
		
		$this->add(array(
		'type' => 'Zend\Form\Element\Select',
		'name' => 'attribute_type',
		'attributes' => array('class' => 'form-control' , 'required'  => 'required',),
		'options' => array(					
					'options' => array( '1' => 'Assets', '2' =>'Consumable Items', '3'=>'Clothing' ),
					'empty_option'  => 'Select Option',
				)
		));
		
		
		
		
		
		
		
		
		
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'active',
			'attributes' => array(
				'required'  => 'required',
            ),
             'options' => array(
                     'label' => 'Active Status ?',
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
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
       
    }
}