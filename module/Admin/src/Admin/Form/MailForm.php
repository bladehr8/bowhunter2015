<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class MailForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
		parent::__construct('pages');
		$this->setAttribute('method', 'post');
		$this->setAttribute('enctype' , 'multipart/form-data');
		$this->setAttribute('class', 'form-advertisement');
		
	

		
		
		
		
		
	  $this->add(array(
        'name'      => 'content',
        'attributes'=> array(
								'id'    => 'content',
								'type'  => 'textarea',
								'class' => 'ckeditor form-control',
								'placeholder' => "Enter your email message",
								'cols' => 10,
								'rows' =>10,
								),

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