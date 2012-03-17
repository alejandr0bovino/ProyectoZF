<?php

class Admin_Form_Usuario extends Zend_Form {

    public function init() {
        
        $config = Zend_Registry::get('config');

        $em = Zend_Registry::get('em');
        $adapter =  new ProyectoZF_Db_Adapter_Pdo_Doctrine($em->getConnection()->getWrappedConnection());
        
        $this->setAction($this->getView()
                ->baseUrl() . '/admin/usuario/guardar/')
                ->setMethod('post');

        
        
        $id = $this->createElement('hidden', 'id')
                ->removeDecorator('HtmlTag')
                ->removeDecorator('label');



        $uniqueUsuarioValidator = new Zend_Validate_Db_NoRecordExists(
				    array(
				        'table' => 'usuarios',
				        'field' => 'usuario',
				    	'adapter' => $adapter
				    ));
        $uniqueUsuarioValidator->setMessages(array(
            Zend_Validate_Db_Abstract::ERROR_RECORD_FOUND => "El nombre de usuario '%value%' pertenece al sistema"
        ));     

        $usuario = $this->createElement('text', 'usuario');
        $usuario->setLabel('Usuario')
                ->setAttrib('maxLength', 30)
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Requerido')))
                //->addValidator('alnum', true, array('messages' => array('notAlnum' => "'%value%' El valor no es alfanúmerico")))
                ->addValidator('regex', true, array(
                    //'pattern' => '(^(?=.*?[A-Za-z])[a-zA-Z0-9]{5,20}$)',
                    'pattern' => '(^[A-Za-z][a-zA-Z0-9]{4,20}$)',                    
                    'messages' => array(
                        'regexInvalid'   => "El valor no es alfanumérico",
                        'regexNotMatch' => 'El valor debe comenzar con una letra, debe contener entre 5 y 20 caracteres, no puede contener "ñ" , "Ñ" o caracteres de puntuación',
                        'regexErrorous'  => "Error '%pattern%'")))                
                ->addValidator($uniqueUsuarioValidator, true)       
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
                            'class' => 'element_form element_form_usuario required clearfix')))); 
        
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
                            'class' => 'element_form element_form_email required clearfix')))); 



        
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



                                    
        $nombre = $this->createElement('text', 'nombre');
        $nombre->setLabel('Nombre')
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
                            'class' => 'element_form element_form_nombre required clearfix'))));                

       
        
        
        
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



        $website = $this->createElement('text', 'website');        
        $website->setLabel('Sitio web')
                //->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Requerido')))
                ->addValidator(new ProyectoZF_Validate_Url)
                ->setRequired(false)
                ->setOptions(array('class' => 'verifyUrl'))
                ->setDecorators(array(
                        array('ViewHelper'),
                        array('Errors'),
                        array('Description'),
                        array('Label', array(
                                //'requiredSuffix' => ' *',
                                'escape' => false,
                                'separator' => ' ')),
                        array('HtmlTag', array(
                                'tag' => 'fieldset',
                                'class' => 'element_form clearfix')))); 




        $fotoActual = $this->createElement('hidden', 'fotoActual')                
                ->removeDecorator('HtmlTag')
                ->removeDecorator('label');

        $fotoFilterResize = new Zend_Filter();
        $fotoFilterResize->appendFilter(new ProyectoZF_Filter_File_Resize(array(
            'width' => 150,
            'height' => 150
        )));
        $fotoFilterResize->appendFilter(new ProyectoZF_Filter_File_Resize(array(
            'directory' => $config->parametros->mvc->usuarios->perfil->foto->thumb,
            'width' => 40,
            'height' => 40
        )));

        $foto = new Zend_Form_Element_File('foto');
        $foto->setLabel('Foto')              
                ->setDestination($config->parametros->mvc->usuarios->perfil->foto->large)
                ->addValidator('ImageSize', false, array(
                          'minwidth'  => 30, 
                          'minheight' => 30, 
                          'maxwidth'  => 470, 
                          'maxheight' => 470
                         ))      
                ->addValidator('Count', false, 1)
                ->setMaxFileSize(8388608)
                ->addValidator('Count', false, 1)           
                ->addValidator('Size', false, 102400) 
                ->addValidator('Extension', false, 'jpg,jpeg,png,gif')
                ->setDescription('<img src="' . $this->getView()->baseUrl() . '/' . $config->parametros->mvc->usuarios->perfil->foto->thumb . $config->parametros->mvc->usuarios->perfil->foto->default . '" />')
                ->addFilter($fotoFilterResize)
                ->setRequired(false);

        $fotoEliminar = new Zend_Form_Element_Checkbox('fotoEliminar');
        $fotoEliminar->setLabel('Eliminar')
                ->setRequired(false)
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
                            'class' => 'element_form element_form_foto_eliminar clearfix'))));
        

        
        
        $this->addElement($id)
                ->addElement($usuario)
                ->addElement($email)
                ->addElement($passwordText)
                ->addElement($password)
                ->addElement($passwordVerify)
                ->addElement($nombre)
                ->addElement($apellido)
                ->addElement($website)
                ->addElement($fotoActual)
                ->addElement($foto)
                ->addElement($fotoEliminar)                                
                ->addElement('submit', 'enviar', array('label' => 'Enviar'));

        
        
        
        $this->clearDecorators();

        $this->addDecorator('FormElements')
                ->addDecorator('HtmlTag', array(
                    'tag' => '<div>',
                    'class' => 'registroForm'))
                ->addDecorator('Form');

        
        $this->setDefault('fotoActual', $config->parametros->mvc->usuarios->perfil->foto->default);                

        $this->foto->setDecorators(array(
            array('File'),            
            array('Description', array('tag' => 'div', 'class' => 'element_form_description element_form_description_foto', 'escape' => false)),
            array('Label', array(
                'requiredSuffix' => ' *',
                'escape' => false,
                'separator' => ' ')),
            array('Errors'),
            array('HtmlTag', array(
                'tag' => 'fieldset',
                'class' => 'element_form element_form_foto clearfix'))));

        
        $this->enviar->setDecorators(array(
            array('ViewHelper'),
            array('HtmlTag', array(
                    'tag' => 'div',
                    'class' => 'element_form submit'))));
        
        
        
    }

}

