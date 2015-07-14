<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class AttributefieldForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('attributefield');
        $this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-attributefield');
		
				
		$this->add(array(
			 'type' => 'Zend\Form\Element\Hidden',
			 'name' => 'setId',
			
		 ));

        $this->add(array(
            'name' => 'fieldName',
			
			  'attributes' => array(
                'type'  => 'text',
				'class'  => 'form-control',
				'placeholder' => "Color Name",
				'required'  => 'required',
            ),
    
        ));
		
		 $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'fieldType',
			 'attributes' => array(
				'required'  => 'required',
				'class'  => 'form-control',
            ),
             'options' => array(
			  'label' => 'Field Type',
                            'value_options' => array(
                             'Text box' => 'Text box',
                             'Number box' => 'Number box',
                             
                     ),
             )
     ));
		
		
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'active',
			'attributes' => array(
				'required'  => 'required',
            ),
             'options' => array(
                     'label' => 'Active ?',
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
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
       
    }
}