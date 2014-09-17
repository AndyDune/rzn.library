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


abstract class AbstractValidator implements ValidatorInterface
{
    /**
     * Значение для валидации.
     *
     * @var mixed
     */
    protected $value;

    protected $abstractOptions = array(
        'messages'             => array(), // массив сообщений об ошибке
        'messageTemplates'     => array(), // массив шаблонов сообщений об ошибке
        'messageVariables'     => array(), // массив дополнительныз начений, которые должны использоваться в шаблонах сообщений
        // in error messages
    );

    /**
     * Ограничивает максимальную длинну возвращаемого сообщения об ошибке.
     *
     * @var int
     */
    protected static $messageLength = -1;

    /**
     * Абстрактный конструктор для всех валидаторов.
     * Валидатор может принимать следубщие параметры:
     *  - ничего т.е. Validator()
     *  - одно или несколько скалярных значений т.у. Validator($first, $second, $third)
     *  - массив т.е. Validator(array($first => 'first', $second => 'second', $third => 'third'))
     *
     * @param array $options
     */
    public function __construct($options = null)
    {
        if (isset($this->messageTemplates)) {
            $this->abstractOptions['messageTemplates'] = $this->messageTemplates;
        }

        if (isset($this->messageVariables)) {
            $this->abstractOptions['messageVariables'] = $this->messageVariables;
        }

        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * Веруть опцию валидатора с указанным ключем.
     *
     * @param string $option
     * @return mixed
     * @throws Exception
     */
    public function getOption($option)
    {
        if (array_key_exists($option, $this->abstractOptions)) {
            return $this->abstractOptions[$option];
        }

        if (isset($this->options) && array_key_exists($option, $this->options)) {
            return $this->options[$option];
        }

        throw new Exception("Неверная опция '$option'");
    }

    /**
     * Вернуть все опции.
     *
     * @return array массив всех доступных опций.
     */
    public function getOptions()
    {
        $result = $this->abstractOptions;
        if (isset($this->options)) {
            $result += $this->options;
        }
        return $result;
    }

    /**
     * Установка одной или нескольких опций.
     *
     * @param  array $options опции для установки
     * @throws Exception если $options не является массивом
     * @return AbstractValidator
     */
    public function setOptions($options = array())
    {
        foreach ($options as $name => $option) {
            $fname = 'set' . ucfirst($name);
            $fname2 = 'is' . ucfirst($name);
            if (($name != 'setOptions') && method_exists($this, $name)) {
                $this->{$name}($option);
            } elseif (($fname != 'setOptions') && method_exists($this, $fname)) {
                $this->{$fname}($option);
            } elseif (method_exists($this, $fname2)) {
                $this->{$fname2}($option);
            } elseif (isset($this->options)) {
                $this->options[$name] = $option;
            } else {
                $this->abstractOptions[$name] = $option;
            }
        }

        return $this;
    }

    /**
     * Возврат сообщений об ошибке.
     *
     * @return array
     */
    public function getMessages()
    {
        return array_unique($this->abstractOptions['messages']);
    }

    /**
     * Срабатывает на запуск объекта-валидатора как функции.
     *
     * @param  mixed $value
     * @return bool
     */
    public function __invoke($value)
    {
        return $this->isValid($value);
    }


    /**
     * Возвращает массив ключей значений, которые используются для создания сообщений об ошибке.
     *
     * @return array
     */
    public function getMessageVariables()
    {
        return array_keys($this->abstractOptions['messageVariables']);
    }

    /**
     * Возвращает шаблоны сообщений от модератора.
     *
     * @return array
     */
    public function getMessageTemplates()
    {
        return $this->abstractOptions['messageTemplates'];
    }

    /**
     * Установка шаблона сообщения об ошибке.
     *
     * @param  string $messageString
     * @param  string $messageKey     OPTIONAL
     * @return AbstractValidator можно делать цепочки методов
     * @throws Exception
     */
    public function setMessage($messageString, $messageKey = null)
    {
        if ($messageKey === null) {
            $keys = array_keys($this->abstractOptions['messageTemplates']);
            foreach ($keys as $key) {
                $this->setMessage($messageString, $key);
            }
            return $this;
        }

        if (!isset($this->abstractOptions['messageTemplates'][$messageKey])) {
            throw new Exception("Нет шаблона сообщения для ключа '$messageKey'");
        }

        $this->abstractOptions['messageTemplates'][$messageKey] = $messageString;
        return $this;
    }

    /**
     *
     * Внедрение сообщений об ошибках валидатора в виде массива. Ключе массива - это ключи сообщений, значений - шаблоны сообщений.
     *
     * @param  array $messages
     * @return AbstractValidator
     */
    public function setMessages(array $messages)
    {
        foreach ($messages as $key => $message) {
            $this->setMessage($message, $key);
        }
        return $this;
    }

    /**
     * Магическая шункция возвращает запрошенные параметры.
     *
     * @param  string $property
     * @return mixed
     * @throws Exception
     */
    public function __get($property)
    {
        if ($property == 'value') {
            return $this->value;
        }

        if (array_key_exists($property, $this->abstractOptions['messageVariables'])) {
            $result = $this->abstractOptions['messageVariables'][$property];
            if (is_array($result)) {
                $result = $this->{key($result)}[current($result)];
            } else {
                $result = $this->{$result};
            }
            return $result;
        }

        if (isset($this->messageVariables) && array_key_exists($property, $this->messageVariables)) {
            $result = $this->{$this->messageVariables[$property]};
            if (is_array($result)) {
                $result = $this->{key($result)}[current($result)];
            } else {
                $result = $this->{$result};
            }
            return $result;
        }

        throw new Exception("Не существует свойства с именем: '$property'");
    }

    /**
     * Создает и возращает сообщения об ошибках с учетом указанных ключа и значения.
     *
     * Возвращает null есои указанному ключю $messageKey не соответствует ни один шаблон сообщения.
     *
     *
     * @param  string              $messageKey
     * @param  string|array|object $value
     * @return string
     */
    protected function createMessage($messageKey, $value)
    {
        if (!isset($this->abstractOptions['messageTemplates'][$messageKey])) {
            return null;
        }

        $message = $this->abstractOptions['messageTemplates'][$messageKey];

        if (is_object($value) &&
            !in_array('__toString', get_class_methods($value))
        ) {
            $value = get_class($value) . ' object';
        } elseif (is_array($value)) {
            $value = var_export($value, 1);
        } else {
            $value = (string) $value;
        }

        $message = str_replace('%value%', (string) $value, $message);
        foreach ($this->abstractOptions['messageVariables'] as $ident => $property) {
            if (is_array($property)) {
                $value = $this->{key($property)}[current($property)];
                if (is_array($value)) {
                    $value = '[' . implode(', ', $value) . ']';
                }
            } else {
                $value = $this->$property;
            }
            $message = str_replace("%$ident%", (string) $value, $message);
        }

        $length = self::getMessageLength();
        if (($length > -1) && (strlen($message) > $length)) {
            $message = substr($message, 0, ($length - 3)) . '...';
        }

        return $message;
    }

    /**
     * @param  string $messageKey
     * @param  string $value      OPTIONAL
     * @return void
     */
    protected function error($messageKey, $value = null)
    {
        if ($messageKey === null) {
            $keys = array_keys($this->abstractOptions['messageTemplates']);
            $messageKey = current($keys);
        }

        if ($value === null) {
            $value = $this->value;
        }

        $this->abstractOptions['messages'][$messageKey] = $this->createMessage($messageKey, $value);
    }

    /**
     * Returns the validation value
     *
     * @return mixed Value to be validated
     */
    protected function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value to be validated and clears the messages and errors arrays
     *
     * @param  mixed $value
     * @return void
     */
    protected function setValue($value)
    {
        $this->value               = $value;
        $this->abstractOptions['messages'] = array();
    }


    /**
     * Возврат максимально допустимой длинны сообщения
     *
     * @return int
     */
    public static function getMessageLength()
    {
        return static::$messageLength;
    }

    /**
     * Внедрение максимально допустимой длинны сообщения.
     *
     * @param int $length
     */
    public static function setMessageLength($length = -1)
    {
        static::$messageLength = $length;
    }


} 