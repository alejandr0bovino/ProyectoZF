<?php

class Admin_Form_Login extends Zend_Form
{

    public function init()
    {
        $this->setAction($this->getView()
            ->baseUrl().'/admin/login/autenticar/')
            ->setMethod('post');

        //        $notEmpty = new Zend_Validate_NotEmpty();
        //        $notEmpty->setMessage('Requerido');
        //        $notEmpty->zfBreakChainOnFailure = true;

        $email = $this->createElement('text', 'email');
        
        $email->setLabel('E-mail')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Requerido')))
            ->addValidator(new ProyectoZF_Validate_Email())
            ->setRequired(true)
            ->setOptions(array('class'=>'verifyMail'))
            ->setDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array(
                'requiredSuffix' => ' *',
                'escape' => false,
                'separator' => ' ')),
            array('HtmlTag', array(
                'tag' => 'fieldset',
                'class' => 'element_form required clearfix'))));        
        
        
        $password = $this->createElement('password', 'password');
        
        $password->setLabel('Clave')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Requerido')))                   
            ->addValidator('alnum', true, array('messages' => array('notAlnum' => "El valor no es alfanúmerico")))                 
            ->addValidator('stringLength', true, array(4,8,'messages' => array(
                'stringLengthTooShort' => 'Valor demasiado corto, al menos %min% caracteres',
                'stringLengthTooLong' => 'Valor demasiado largo, máximo %max% caracteres'
                )))
            ->setRequired(true)
            ->setOptions(array('class'=>'verifyPassword'))
            ->setDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array(
                'requiredSuffix' => ' *',
                'escape' => false,
                'separator' => ' ')),
            array('HtmlTag', array(
                'tag' => 'fieldset',
                'class' => 'element_form required clearfix'))));


        $this->addElement($email)
            ->addElement($password)
            ->addElement('submit', 'login', array('label' => 'Login'));

        $this->clearDecorators();

        $this->addDecorator('FormElements')
            ->addDecorator('HtmlTag', array(
                'tag' => '<div>',
                'class' => 'loginForm'))
            ->addDecorator('Form');



        $this->login->setDecorators(array(
            array('ViewHelper'),
            array('HtmlTag', array(
                'tag' => 'div',
                'class' => 'element_form submit'))));

    }


}

