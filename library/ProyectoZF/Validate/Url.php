<?php 
class ProyectoZF_Validate_Url extends Zend_Validate_Abstract
{
	const INVALID_URL = 'invalidUrl';

	protected $_messageTemplates = array(
		self::INVALID_URL => "'%value%' no es una URL válida.",
	);

	public function isValid($value)
	{
		$valueString = (string) $value;

		$this->_setValue($valueString);

		if (!Zend_Uri::check($value)) {

			$this->_error(self::INVALID_URL);
			return false;

		}

		return true;
	}
}