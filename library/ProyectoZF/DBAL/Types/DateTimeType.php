<?php
namespace ProyectoZF\DBAL\types;

use Doctrine\DBAL\Types\DateTimeType as DoctrineDateTimeType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Override 'datetime' type in Doctrine to use Zend_Date
 */
class DateTimeType extends DoctrineDateTimeType
{

    /**
     * Convert from db to Zend_Date
     *
     * @param string $value
     * @param AbstractPlatform $platform
     * @return \Zend_Date|null
     */
    public function convertToPhpValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return null;
        }
        \Zend_Date::setOptions(array('format_type' => 'php', ));
        $phpValue = new \Zend_Date($value, $platform->getDateTimeFormatString());
        \Zend_Date::setOptions(array('format_type' => 'iso', ));

        return $phpValue;
    }

    /**
     * Convert from Zend_Date to db
     *
     * @param string $value
     * @param AbstractPlatform $platform
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return null;
        }
        \Zend_Date::setOptions(array('format_type' => 'php', ));
        $dbValue = $value->toString($platform->getDateTimeFormatString());
        \Zend_Date::setOptions(array('format_type' => 'iso', ));

        return $dbValue;
    }

}
