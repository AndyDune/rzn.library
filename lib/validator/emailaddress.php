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


class EmailAddress extends AbstractValidator
{
    /**
     * @var array
     */
    protected $messageTemplates = array(
        'wrongEmail'     => "Введенный email неверен. Используейте стандартный формат: local-part@hostname",
    );


    public function isValid($value)
    {
        $this->setValue($value);
        if ($value = filter_var($value, FILTER_VALIDATE_EMAIL)) {
            // Сохраняем отфильтрованное
            $this->setValue($value);
            return true;
        }
        $this->error(null);
        return false;
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