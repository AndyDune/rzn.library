<?
$arModuleVersion = array(
    "VERSION" => "2.7.0",
    "VERSION_DATE" => "2015-08-18 17:00:00"
);
/**
 * Позиции в коде версии:
 * 1 - выкат нового подмодуля, серъезная изменения в совместимости
 * 2 - добавление метода или класса в подмодуль.
 * 3 - исправления ошибок, комментарии
 */

/**
 *
 *
 * 2.7.0
 * Добавил возможность описывать инструкции для инъектора в самом классе с указанием интерфейса.
 *
 * 2.6.1
 * Ошибка в инъекторе
 *
 * 2.6.0
 * Внедрена возможность прописывать остановки в конфиге водопада.
 * Внедрена возможность раннего окончания работы водопада с запуском финальной функции.
 * Приведен в рабочее состояние инъектор инициилизатор - инициилизирует через менеджер сервисов.
 * Для водопада добавлены классы для тестирования.
 *
 * 2.5.0
 * Изменения в водопаде для организации тестов: пропуск функций, остановка работы
 *
 * 2.4.0
 * Внедрен еще один сеттер инъектора. Внедрение произвольных параметров.
 *
 * 2.3.3
 * Для водопада сменил приоритет загрузки инстансов.
 *
 * 2.3.2
 * Добавлен метод для возврата кода ошибки
 *
 * 2.3.1
 * Ошибка в водопаде. Начальная инициилизация разделяемых парамтеров.
 *
 * 2.3.0
 * Появилась возможность разделения всех свойств. Разрешается флагом в описании водопада 'result_shared' => true
 *
 * 2.2.0
 * Injector встроен в менедежр событий.
 * Описывается инъектор стандартно.
 *
 *
 * 2.1.1
 * Устранена фатальная ошибка инициилизации медиатора и водопада.
 *
 * 2.1.0
 * injector добавлен в менеджер сервисов. Еще не проверен.
 *
 * 2.0.0
 * Инъектор реализовался в коде. Проведены успешные тестовые испытания. Требует комментирования и испытаний в работе.
 *
 *
 * 1.15.7
 * Для инъектора добавлен инициилизатор менеджера сервисов - инъектор может быть передан любому сервису через интерфейс.
 * Инъектор встроен в медиатор и водопад.
 * инициилизаторы медиатора и водопала чуть оптимизированы.
 *
 * 1.15.6
 * В медиаторе при отсутствии каналоа ошибка генерится правильно.
 *
 * 1.15.5
 * Поработал над инъектором. Изменил его суть.
 * Подразумевается использование его как часть других элементов: сервисов, хелперов, медиаторов и водопадов.
 * DI в общепринятом виде пока не считаю нужным. Или будет добавлен как есть из Zend FW
 *
 * 1.15.4
 * Вернут карту классов Zend
 *
 * 1.15.3
 * Устранена ошибка в строителе запросов CatalogElementFreeProperty для данных decimal
 * Добавлен отдельный автозагрузчик для Zend классов
 *
 * 1.15.2
 * Класс Rzn\Library\Component\HelperManager не используется.
 * Фабрика менеджера хелпров использует класс ServiceManager, который теперь един для всех менеджеров.
 *
 * 1.15.1
 * Доработан класс менеджера сервисов.
 * Заочно появилась возможность создавать другие менеджера сервисов.
 *
 * 1.15
 * Доработана инициилизация серсоов из конфига: в массиве желательно использовать ключ name для названия класса
 * Из helper_manager убрал перегрузку метода родителя.
 *
 * 1.14.1
 * Водопад заработал. Не в идеале.
 *
 * 1.14
 * Добавлен класс Rzn\Library\BitrixTrial\Iblock\GetList\FreeQueryModification
 *
 * 1.13.3
 * Изменена фильтрация для целых чисел - может быть пробел.
 * Добавлена фильтрация для чисел с дробной частью.
 *
 * 1.13.2
 * Доработка форматат конфига медиатороа.
 * Доработка интерфейса медиатора.
 *
 * 1.13.1
 * Доработка классов водопада. Еще не работает.
 * Добавил в конфиг инициилизаторы.
 *
 * 1.13.0
 * Медиатор дополнен обработкой ошибок. Надлен сервисом водопада.
 * Продвижение в разработке водопада - отказ от функции обратного вызова.
 *
 * 1.12.2
 * Медиатор проверен.
 *
 * 1.12.1
 * Добавлены классы для водопада.
 * Rzn\Library\Waterfall (все в папке)
 *
 * 1.11.1
 * Работа над медиатором
 * Rzn\Library\Mediator (все в папке)
 *
 * 1.11.0
 * Добавлены классы и интерфейсы для медиатора
 * Rzn\Library\Mediator (все в папке)
 * Добавлено описание в конфиг
 *
 * 1.10.1
 * Изменения в хелпере url
 *
 * 1.10.0
 * Новый хелпер showWithRename показа значения с переименовкой
 *
 * 1.9.0
 * Новый хелпер phoneNumberFormat фоматиров вывода номера телефона
 * Изменения в регистрации слушателей событий битрикса
 *
 * 1.8.0
 * Внедрение механизмуса отложенных на окончание работы запроса задач.
 *
 * 1.7.5
 * Rzn\Library\Format\ArrayModification метод keysIntVal - србатывание для всех
 *
 * 1.7.4
 * Rzn\Library\Format\ArrayModification наделен интерфейсом ArrayAccess
 *
 * 1.7.3
 * Для класса Rzn\Library\Format\ArrayModification
 * Передача названия обрабатываемого ключа массива в функцию-фильтр.
 * Метод keysTrim может работать со всеми ключами без перечисления их.
 * Метод keysRename - переименовка ключей массива.
 *
 * 1.7.2
 * Добавлено несколько методов для участия в цепочки: Rzn\Library\Session
 * Применение фильров для возвращаемых значений: Rzn\Library\ArrayContainer
 *
 * 1.7.1
 * Добавлена возможность указать требование на наличие параметра не важно какого значения.
 * local/modules/rzn.library/lib/component/helper/addcss.php
 *
 * 1.7.0
 * Инициилизация любых сервисов сервисом менеджера события
 * сервисы надо наделять интерфейсом: Rzn\Library\ServiceManager\EventManagerAwareInterface
 *
 * 1.6.1
 * Добавлена устанвока контекста для возможных события в объекта класа Rzn\Library\BitrixTrial\Iblock\Element\Update
 *
 * 1.6.0
 * Добавлена новй класс: Rzn\Library\BitrixTrial\Iblock\Element\Update
 * Этот класс перегружает CIBlockElement и позволяет обновлять стандартные свойства инфоблоков без затрагивания пользовательских.
 *
 * 1.5.4
 * Добавлена возможность устанавлиавть флаг shared для сервисов
 * local/modules/rzn.library/lib/servicemanager/servicemanager.php
 *
 * 1.5.3
 * Можно указывать максимальное количество сохраняемых картинок.
 * local/modules/rzn.library/lib/bitrixtrial/iblock/multifileproperty.php
 *
 * 1.5.2
 * Добавлен спецтальный обработчик события sale_onSaleComponentOrderOneStepPaySystem
 * Он тут: local/modules/rzn.library/lib/eventlistener/bitrixeventsdrive.php
 *
 * 1.5.1
 * Доработан класс Rzn\Library\Request
 *
 * 1.5.0
 * Новые классы: Rzn\Library\Component\Helper\AddCss и Rzn\Library\Component\Helper\AddJs
 *
 * 1.4.0
 * Новый класс: Rzn\Library\BitrixTrial\Iblock\MultiFileProperty
 *
 * 1.3.0
 * Дополнительно подключаемые конфиг файлы в зависимости от условий.
 * Модификация AttachTemplateConfig для прямой инъекции сервиса конфигов.
 * Добавил продолжение для события битрикса main - OnBeforeLocalRedirect (main_OnBeforeLocalRedirect) - можно модифицировать url для редиректа.
 *
 */