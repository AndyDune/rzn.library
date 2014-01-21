<?
/**
 * Загрузчик классов библиотеки модуля.
 *
 */

namespace Rzn\Library;

class Loader
{
    /**
     * Имена загруженных классов
     *
     * @var array
     * @access private
     */
    protected static $_loadedClasses = array();

    /**
     * Загрузка класса из папки классов.
     *
     *
     * @param string $name
     */
    static public function loadClass($name, $auto = false)
    {

        $fullName = $nameOriginal = $name;
        //$name = ltrim(strtolower($name), ' \\'); // Оказалось не удобно
        $name = ltrim($name, ' \\');
        $prefix = 'Rzn\\Library\\';
        if (strpos($name, $prefix) === 0)
        {
            $name = substr($name, 11);
        }
        else if ($auto)
        {
            throw new \Exception('Класс не мой', 1);
        }
        else
        {
            $fullName = 'Rzn\\Library\\' . $nameOriginal;
        }


        if (in_array($name, self::$_loadedClasses))
        {
            return true;
        }

        if (class_exists($fullName))
            goto next;
        $parts = explode('\\', $name);
        $file = __DIR__ . '/' . implode('/', $parts) . '.php';
        if (is_file($file))
            require $file;
        else // Совметимость с Битрикс 14
        {
            $file = __DIR__ . '/' . strtolower(implode('/', $parts)) . '.php';
            require $file;
        }

        next:
        self::$_loadedClasses[] = $name;
    }


    /**
     * Загрузка собственных специальных классов.
     *
     * @param $name
     * @return bool
     * @throws \Exception
     */
    static public function loadCustomClass($name, $test = false)
    {

        return false; // Функционал отключен

        $fullName = $name;
        //$name = ltrim(strtolower($name), ' \\'); // Оказалось не удобно
        $name = ltrim($name, ' \\');


        $prefix = \COption::GetOptionString("rzn.library", "autoload_class_prefix");
        if (!$prefix)
            return false;

        if (strpos($name, $prefix) === 0)
        {
            $name = substr($name, strlen($prefix));
        }
        else
        {
            return false;
        }


        if (class_exists($fullName))
            goto next;

        if (!$directory = \COption::GetOptionString("rzn.library", "autoload_class_folder"))
            return false;
        $directory = $_SERVER['DOCUMENT_ROOT'] . '/' . $directory;


        $parts = explode('\\', $name);
        $file = $directory . '/' . implode('/', $parts) . '.php';

        if (!is_file($file))
        {
            if ($test)
                return false;
            throw new \Exception('Файла с классом не существует: ' . $file, 1);
        }

        require $file;

        next:
        return true;

    }

    public static function autoload($class)
    {
        try
        {
            if (!self::loadCustomClass($class))
                self::loadClass($class, true);
            return $class;
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

}
