<?php

class Admin_Form_Usuario extends Zend_Form {

    public function init() {
        
        $em = Zend_Registry::get('em');
        $adapter =  new ProyectoZF_Db_Adapter_Pdo_Doctrine($em->getConnection()->getWrappedConnection());
        
        $this->setAction($this->getView()
                ->baseUrl() . '/admin/usuario/guardar/')
                ->setMethod('post');

        
        
        $id = $this->createElement('hidden', 'id')
                ->removeDecorator('HtmlTag')
                ->removeDecorator('label');



        $uniqueNombreValidator = new Zend_Validate_Db_NoRecordExists(
				    array(
				        'table' => 'usuarios',
				        'field' => 'nombre',
				    	'adapter' => $adapter
				    ));
        $uniqueNombreValidator->setMessages(array(
            Zend_Validate_Db_Abstract::ERROR_RECORD_FOUND => "El nombre de usuario '%value%' pertenece al sistema"
        ));     
        
        $nombre = $this->createElement('text', 'nombre');
        $nombre->setLabel('Nombre')
                ->setAttrib('maxLength', 45)
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Requerido')))
                ->addValidator('alnum', true, array('allowWhiteSpace' => true, 'messages' => array('notAlnum' => "El valor no es alfanúmerico")))
                ->addValidator($uniqueNombreValidator, true)       
                ->setRequired(true)
                ->setOptions(array('class' => 'verifyText'))
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

       
        
        
        
        $apellido = $this->createElement('text', 'apellido');
        $apellido->setLabel('Apellido')
                ->setAttrib('maxLength', 45)
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Requerido')))
                ->addValidator('alnum', true, array('allowWhiteSpace' => true, 'messages' => array('notAlnum' => "El valor no es alfanúmerico")))
                ->setRequired(true)
                ->setOptions(array('class' => 'verifyText'))
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

        
        
        $uniqueEmailValidator = new Zend_Validate_Db_NoRecordExists(
				    array(
				        'table' => 'usuarios',
				        'field' => 'email',
				    	'adapter' => $adapter
				    ));
        $uniqueEmailValidator->setMessages(array(
            Zend_Validate_Db_Abstract::ERROR_RECORD_FOUND => "('%value%') pertenece a un usuario registrado"
        ));  
        
        $email = $this->createElement('text', 'email');
        $email->setLabel('E-mail')
                ->setAttrib('maxLength', 45)
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Requerido')))
                ->addValidator(new ProyectoZF_Validate_Email())
                ->addValidator($uniqueEmailValidator, true)                       
                ->setRequired(true)
                ->setOptions(array('class' => 'verifyMail'))
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

        
        
        
        $passwordText = $this->createElement('text', 'passwordText', array('label' => 'Clave actual'));
        $passwordText->setAttribs(array(
            'disabled' => true,
            'readonly' => true))                 
                    ->setDecorators(array(
                        array('ViewHelper'),
                        array('Description'),
                        array('Label', array(
                                'escape' => false,
                                'separator' => ' ')),
                        array('HtmlTag', array(
                                'tag' => 'fieldset',
                                'class' => 'element_form clearfix'))));                
                

        
        
        
        $password = $this->createElement('password', 'password');
        $password->setLabel('Clave')
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Requerido')))
                ->addValidator('alnum', true, array('messages' => array('notAlnum' => "El valor no es alfanúmerico")))
                ->addValidator('stringLength', true, array(4, 8, 'messages' => array(
                        'stringLengthTooShort' => 'Valor demasiado corto, al menos %min% caracteres',
                        'stringLengthTooLong' => 'Valor demasiado largo, máximo %max% caracteres')))
                ->setRequired(true)
                ->setOptions(array('class' => 'verifyPassword'))
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


        
        
        
        $passwordVerify = $this->createElement('password', 'passwordVerify');
        $passwordVerify->setLabel('Confirmar<br />Clave')
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Requerido')))
                ->addValidator('alnum', true, array('messages' => array('notAlnum' => "El valor no es alfanúmerico")))
                ->addValidator('stringLength', true, array(4, 8, 'messages' => array(
                        'stringLengthTooShort' => 'Valor demasiado corto, al menos %min% caracteres',
                        'stringLengthTooLong' => 'Valor demasiado largo, máximo %max% caracteres')))
                ->addValidator('Identical', true, array('password', 'messages' => array('notSame' => 'El valor no coincide')))
                ->setRequired(true)
                ->setOptions(array('class' => 'verifyPasswordConfirm'))
                ->setDecorators(array(
                    array('ViewHelper'),
                    array('Errors'),
                    array('Description'),
                    array('Label', array(
                            'requiredSuffix' => ' *',
                            'escape' => false,
                            'separator' => ' ',
                            'class' => 'passwordVerify')),
                    array('HtmlTag', array(
                            'tag' => 'fieldset',
                            'class' => 'element_form required clearfix'))));
        
        
        
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

//        $this->setElementDecorators(array(
//            array('ViewHelper'),
//            array('Errors'),
//            array('Description'),
//            array('Label', array(
//                    'requiredSuffix' => ' *',
//                    'escape' => false,
//                    'separator' => ' ')),
//            array('HtmlTag', array(
//                    'tag' => 'fieldset',
//                    'class' => 'element_form clearfix'))));
        

        
        
        
        //$this->id->removeDecorator('HtmlTag');

        
        
        
//        $this->passwordVerify->addDecorator('Label', array(
//            'requiredSuffix' => ' *',
//            'escape' => false,
//            'separator' => ' ',
//            'class' => 'passwordVerify'));

        
        
        
        $this->enviar->setDecorators(array(
            array('ViewHelper'),
            array('HtmlTag', array(
                    'tag' => 'div',
                    'class' => 'element_form submit'))));
        
        
        
    }

}

