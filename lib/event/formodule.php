<?
namespace Rzn\Library\Event;
class ForModule
{
    static private $loaded = array();

    static public function load($name, $file_name = null)
    {
        if (!$file_name)
            $file_name = $name;
        if (array_key_exists($file_name, self::$loaded))
            return true;

        $file = \Rzn\Library\Registry::get('DOCUMENT_ROOT') . '/local/event_handlers/' . $file_name . '.php';

        if (is_file($file))
        {
            include($file);
            $classes = get_declared_classes();
            $className = array_pop($classes);
            $ref = new \ReflectionClass($className);
            if ($ref->isSubclassOf('\\Rzn\\Library\\Event\\AbstractClass'))
            {
                $methods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);
                foreach($methods as $method)
                {
                    \AddEventHandler($name, $method->name, array($method->class, $method->name));
                }
                //print_r($methods);
            }
            self::$loaded[$file_name] = true;
        }
        else
            self::$loaded[$file_name] = false;
        return true;
    }

}