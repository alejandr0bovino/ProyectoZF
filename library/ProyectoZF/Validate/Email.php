<?php

class ProyectoZF_Validate_Email extends Zend_Validate_EmailAddress {
	
     protected $_messageTemplates = array(
        self::INVALID            => "Invalid type given. String expected",
        self::INVALID_FORMAT     => "'%value%' formato inválido",
        self::INVALID_HOSTNAME   => "'%hostname%' hostname inválido",
        self::INVALID_MX_RECORD  => "'%hostname%' does not appear to have a valid MX record for the email address '%value%'",
        self::INVALID_SEGMENT    => "'%hostname%' is not in a routable network segment. The email address '%value%' should not be resolved from public network",
        self::DOT_ATOM           => "'%localPart%' can not be matched against dot-atom format",
        self::QUOTED_STRING      => "'%localPart%' can not be matched against quoted-string format",
        self::INVALID_LOCAL_PART => "'%localPart%' is no valid local part for email address '%value%'",
        self::LENGTH_EXCEEDED    => "'%value%' exceeds the allowed length",
    );
    
    public function getMessages() {
        $messages = array_values($this->_messages);
        return (array)$messages[0]; 
    }
}