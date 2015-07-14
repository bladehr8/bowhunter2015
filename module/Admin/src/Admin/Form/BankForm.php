<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class BankForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
		parent::__construct('bank');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-bank');
		
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
				'placeholder' => "Enter pack type",
				'required'  => 'required',
            ),
			
    
        ));
		
		
		
	$this->add(array(
		'name' => 'bucks',
		'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Enter bucks i.e - 1000",
				'required'  => 'required',
            ),
		
		
		));
		
		
		$this->add(array(
		'name' => 'gold_coins',
		'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Enter Gold Coins i.e - 1000",
				'required'  => 'required',
            ),
		
		
		));	
		
		
		
		$this->add(array(
		'name' => 'price',
		'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Enter Price - i.e - 9.99",
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