<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 12.09.14                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\Validator;
use Rzn\Library\Extract\PhoneNumbers;

class PhoneNumber extends AbstractValidator
{
    /**
     * @var array
     */
    protected $messageTemplates = array(
        'wrongPhoneNumber'     => "Номер телефона некорректен",
    );


    public function isValid($value)
    {
        $this->setValue($value);
        // Используем извлекатора номеров телефонов.
        $object = new PhoneNumbers($value);
        if (!$object->count()) {
            $this->error(null);
            return false;
        }
        $result = $object->get();
        $this->setValue(current($result));
        return true;
    }

    /**
     * Наделяем валидатора способностью к изменению фильтруемого значения
     *
     * @return mixed отвалидированное значение
     */
    public function getValue()
    {
        return $this->value;
    }


} 