<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 02.09.2015                                      
  * ----------------------------------------------------
  *
  * Пересылка сообщений между участками кода в программе.
  * Сообщение имеет адресс, тело и ключ доступа.
  * Нельзя отослать второе сообщение по адресу если не было принято предыдушее.
  *
  */


namespace Rzn\Library\InnerMessage;


class Manager 
{
    protected $messages = [];
    protected $messageLockKeys = [];

    /**
     * Послать сообшение
     *
     * @param string $messageAddress адрес сообщения
     * @param mixed $message тело собщения -
     * @param null|string $pass ключ к доступу к сообщению
     * @throws Exception если пытаемся отослать повтрное сообщение
     */
    public function send($messageAddress, $message, $pass = null)
    {
        if (isset($this->messages[$messageAddress])) {
            throw new Exception('Сообщение с адресом ' . $messageAddress . ' находится в абонентском ящике и не может быть продублировано');
        }
        $this->messages[$messageAddress] = $message;
        $this->messageLockKeys[$messageAddress] = $pass;
    }

    /**
     * Получить сообщение.
     * Если сообщения нет - возвращает null
     *
     * @param string $messageAddress адресс
     * @param null|string $pass
     * @return mixed
     * @throws Exception если ключ доступа не подходит
     */
    public function receive($messageAddress, $pass = null)
    {
        if (!isset($this->messages[$messageAddress])) {
            return null;
        }

        if ($pass != $this->messageLockKeys[$messageAddress]) {
            throw new Exception('Сообщение с адресом ' . $messageAddress . ' не может быть разблокировано ключем "' . $pass . '" - получение невозможно');
        }
        $message = $this->messages[$messageAddress];
        unset($this->messages[$messageAddress]);
        return $message;
    }

    /**
     * Проверка на существование сообщения.
     *
     * @param $messageAddress адресс сообщения
     * @return bool
     */
    public function has($messageAddress)
    {
        if (isset($this->messages[$messageAddress])) {
            return true;
        }
        return false;
    }

    /**
     * Обязательное получение сообщения.
     *
     * @param string $messageAddress
     * @param null|string $pass
     * @return mixed тело сообщения
     * @throws Exception При отсутствии сообщения и
     */
    public function requireMessage($messageAddress, $pass = null)
    {
        if (!isset($this->messages[$messageAddress])) {
            throw new Exception('Сообщение с адресом ' . $messageAddress . ' не существует. Получение невозможно.');
        }
        return $this->receive($messageAddress, $pass);
    }

}