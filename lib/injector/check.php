<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 03.09.2015                                      
  * ----------------------------------------------------
  *
  */


namespace Rzn\Library\Injector;


class Check extends Injector
{
    protected $errors = null;


    protected $errorsTotal = [];


    public function getErrors()
    {
        return $this->errorsTotal;
    }

    public function addError($error)
    {
        if (is_array($error)) {
            $this->errorsTotal = array_merge($this->errorsTotal, $error);
        } else {
            $this->errorsTotal[] = $error;
        }
    }


    public function inject($object, $options = null)
    {
        $this->errorsTotal = [];
        $this->errors = null;
        if ($this->needInit) {
            $this->initServicesFromConfig();
            $this->needInit = false;
        }

        if (!$options) {
            // Есть описание для инъектора внутри объекта
            if ($object instanceof OptionsRetrieverInterface) {
                $options = $object->getInjectorOptions();
            } else {
                // По умолчанию делается инициилизация
                $options = $this->defaultOption;
            }
        }
        foreach($options as $optionName => $optionValue) {
            if (isset($optionValue['type'])) {
                $handler = $optionValue['type'];
            } else if (isset($optionValue['handler'])) {
                $handler = $optionValue['handler'];
            } else {
                $this->addError('Обработчик инъекции не указан.');
                $this->errors[$optionName] = 'Обработчик инъекции не указан.';
                continue;
            }
            if (!isset($this->handlerObject[$handler])) {
                $this->addError('Обработчик инъекции не зарегистрирован: ' . $handler);
                $this->errors[$optionName] = 'Обработчик инъекции не зарегистрирован: ' . $handler;
                continue;
            }

            if (isset($optionValue['options'])) {
                $optionValueOptions = $optionValue['options'];
            } else {
                $optionValueOptions = null;
            }
            //$this->handlerObject[$handler]->execute($object, $optionValueOptions);
            $this->errors[$optionName] = $this->_executeInject($this->handlerObject[$handler], $object, $optionValueOptions);
        }
    }

    /**
     * Для перегрузки в тестовом скрипте
     *
     * @param $handler
     * @param $object
     * @param $optionValueOptions
     */
    protected function _executeInject($handler, $object, $optionValueOptions)
    {
        if (method_exists($handler, 'check')) {
            $errors = $handler->check($object, $optionValueOptions);
            if (!$errors) {
                $errors = 'OK';
            } else {
                $this->addError($errors);
            }
        } else {
            $errors = 'Обработчик инъекции не имеет проверочного метода ' . get_class($handler);
        }
        return $errors;
    }

    public function getCheckResult()
    {
        return $this->errors;
    }

}