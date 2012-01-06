<?php

class Admin_Form_Usuario extends Zend_Form
{

    public function init()
    {
        $this->setAction($this->getView()
            ->baseUrl().'/admin/usuario/guardar/')
            ->setMethod('post');
        
        $id = $this->createElement('hidden', 'id');
        
        $nombre = $this->createElement('text', 'nombre');
        $nombre->setLabel('Nombre')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Requerido')))
            ->addValidator('alnum', true, array('allowWhiteSpace' => true, 'messages' => array('notAlnum' => "El valor no es alfanúmerico")))
            ->setRequired(true);
        
        $apellido = $this->createElement('text', 'apellido');
        $apellido->setLabel('Apellido')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Requerido')))
            ->addValidator('alnum', true, array('allowWhiteSpace' => true, 'messages' => array('notAlnum' => "El valor no es alfanúmerico")))
            ->setRequired(true);
        
        $email = $this->createElement('text', 'email');
        $email->setLabel('E-mail')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Requerido')))
            ->addValidator(new ProyectoZF_Validate_Email())
            ->setRequired(true);        
        
        $passwordText = $this->createElement('text', 'passwordText', array('label' => 'Clave actual'));
        $passwordText->setAttribs(array(
            'disabled'=> true,
            'readonly'=>true));
        
        $password = $this->createElement('password', 'password');
        $password->setLabel('Clave')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Requerido')))                   
            ->addValidator('alnum', true, array('messages' => array('notAlnum' => "El valor no es alfanúmerico")))                 
            ->addValidator('stringLength', true, array(4,8, 'messages' => array(
                'stringLengthTooShort' => 'Valor demasiado corto, al menos %min% caracteres',
                'stringLengthTooLong' => 'Valor demasiado largo, máximo %max% caracteres')))
            ->setRequired(true);  
        
        $passwordVerify = $this->createElement('password', 'passwordVerify');
        $passwordVerify->setLabel('Confirmar<br />Clave')
            ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Requerido')))                   
            ->addValidator('alnum', true, array('messages' => array('notAlnum' => "El valor no es alfanúmerico")))                 
            ->addValidator('stringLength', true, array(4,8, 'messages' => array(
                'stringLengthTooShort' => 'Valor demasiado corto, al menos %min% caracteres',
                'stringLengthTooLong' => 'Valor demasiado largo, máximo %max% caracteres')))
            ->addValidator('Identical', true, array('password', 'messages' => array('notSame' => 'El valor no coincide')))
            ->setRequired(true);          
        
        $this->addElement($id)
            ->addElement($nombre)
            ->addElement($apellido)
            ->addElement($email)
            ->addElement($passwordText)
            ->addElement($password)
            ->addElement($passwordVerify)
            ->addElement('submit', 'enviar', array('label' => 'Enviar'));        
        
        $this->clearDecorators();
       
        $this->addDecorator('FormElements')
            ->addDecorator('HtmlTag', array(
                'tag' => '<div>',
                'class' => 'registroForm'))
            ->addDecorator('Form');
       
        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array(
                'requiredSuffix' => ' *',
                'escape' => false,
                'separator' => ' ')),
            array('HtmlTag', array(
                'tag' => 'fieldset',
                'class' => 'element_form clearfix'))));
        
        $this->id->removeDecorator('HtmlTag'); 
        
        $this->passwordVerify->addDecorator('Label', array(
            'requiredSuffix' => ' *',
            'escape' => false,
            'separator' => ' ',
            'class' => 'passwordVerify'));
                
        $this->enviar->setDecorators(array(
            array('ViewHelper'),
            array('HtmlTag', array(
                'tag' => 'div',
                'class' => 'element_form submit'))));                
        
    }


}

