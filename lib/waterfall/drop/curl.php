<?php

/**
 * ----------------------------------------------
 * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>   |
 * | Сайт: www.rznw.ru                           |
 * | Телефон: +7 (4912) 51-10-23                 |
 * | Дата: 10.03.2016                            |
 * -----------------------------------------------
 *
 */
namespace Rzn\Library\Waterfall\Drop;

class Curl
{

    protected $jsonOption = 0;

    /**
     * Запуск из водопада.
     *
     * @param $params
     * @param \Rzn\Library\Waterfall\Result $result
     */
    public function __invoke($params, $result)
    {
        if (!isset($params['curl_url']) or !$params['curl_url']) {
            // Может быть частью водопада в котором этот этап может быть пропущен
            $result->error(['code' => 'no_url_for_curl', 'message' => 'Не указан URL для запроса.']);
            return;
        }

        if (isset($params['json_unescaped_unicode']) and $params['json_unescaped_unicode']) {
            $this->jsonOption = JSON_UNESCAPED_UNICODE;
        }

        $res = $this->execute($params['curl_url'], $params, $result);

        $result['curl_result'] = $res;
    }

    /**
     * @param $page
     * @param $params
     * @param \Rzn\Library\Waterfall\Result $result
     * @return bool|mixed
     */
    protected function execute($url, $params, $result)
    {
        $ch = curl_init();

        if (isset($params['query']) and is_array($params['query'])) {
            $query = [];
            array_walk($params['query'], function ($value, $key) use (&$query) {
                $query[] = $key . '=' . urlencode($value);
            });
            if (strpos($url, '?')) {
                $url = $url . "&" . implode('&', $query);
            } else {
                $url = $url . "?" . implode('&', $query);
            }

        }

        if (isset($params['test_print']) and $params['test_print']) {
            echo $url, '
</br>';
        }

        // Для проверки снаружи
        $result['log_url'] = $url;
        //file_put_contents(__DIR__.'/lg_url.txt',$url);
        curl_setopt($ch, CURLOPT_URL, $url);
        if (isset($params['curl_login']) and isset($params['curl_password'])) {
            curl_setopt($ch, CURLOPT_USERPWD, $params['curl_login'] . ":" . $params['curl_password']);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        // не проверять SSL сертификат
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // не проверять Host SSL сертификата
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0');

        if ($params['connect_timeout']) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $params['connect_timeout']);
        }

        /**
         * php://input
         */
        if (isset($params['json'])) {

            if (is_array($params['json'])) {
                $params['json'] = json_encode($params['json'], $this->jsonOption);
            }

            $result->addSharedResult('curl_json_to_send', $params['json']);

            if (isset($params['test_print']) and $params['test_print']) {
                pr($params['json']);
            }

            //$params['json'] = urlencode($params['json']);
            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $params['json']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json; charset=UTF-8',
                    'Content-Length: ' . $this->getLen($params['json']))
            );
        } else if(isset($params['xml'])) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params['xml']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/xml',
                    'Content-Length: ' . $this->getLen($params['xml']))
            );
        } else if(isset($params['post'])) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params['post']);
        }

        if(isset($params['to_file'])) {
            $fp = fopen($params['to_file'],'w+b');
            if (!$fp) {
                $result->error(['message' => 'Не удалось открыть файл для записи', 'code' => 'connection_no_file_open']);
                return;
            }
            curl_setopt($ch, CURLOPT_FILE, $fp);
        }

        $res = curl_exec($ch);
        //file_put_contents(__DIR__.'/lg_res.txt',$res);

        if ($err = curl_errno($ch)) {
            $result->error(['message' => curl_error($ch) . ' Номер: ' . $err, 'code' => 'connection']);
            return null;
        }
        curl_close($ch);

        if (isset($fp)) {
            fclose($fp);
        }

        return $res;
    }


    protected function getLen($data)
    {
        //file_put_contents(__DIR__ . '/test.txt', $data);
        $size = mb_strlen($data, '8bit');
        //$size = strlen($data);
        return $size;

    }
}