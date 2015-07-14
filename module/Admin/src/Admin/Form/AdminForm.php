<?php
namespace Admin\Form;

use Zend\Form\Form;

class AdminForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('admin');
        $this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-login');

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'email',
				'class'  => 'form-control',
				'placeholder' => "Email",
            ),
    
        ));
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
				'class'  => 'form-control password',
				'placeholder' => "Password",
            ),
  
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