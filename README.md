![](http://glide.name/docs/zfc-transition/logo.png)

Transition fork of CMF ZFCore [http://code.google.com/p/zfcore/](http://code.google.com/p/zfcore/)


## Основные отличия ##

- PHP 5.2 больше **не** поддерживается. Минимальная версия php для работы CMF - **5.3.5**
- **Быстрая установка** вместе с зависимостями через **Composer**
- Добавлена **поддержка PHPUnit 3.7.10**
- Улучшена производительность (модуль APC теперь обязательный + используется classmap autoloader)
- Модули **blog** и **forum** больше не поддерживаются (удалены)


## Новые модули и возможности ##
- Добавлен **[Meta Fixtures Manager](https://github.com/uglide/zfcore-transition/wiki/Meta-Fixtures-Manager-%5BMFM%5D)**
- Обновлены миграции Сore_Migration для совместимости с **[ZFCore Auto Update](https://github.com/uglide/zfcore-autoupdate)**
- Добавлен **модуль Console** для запуска приложения из консоли
- Добавлен **Core_Image** для простого изменения размеров изображений
- Добавлен **Email Error Reporter**
- Добавлен **Core_PDF** (грязный бекпорт)
- Rowset расширен **Core\_Db\_Table\_Rowset\_Abstract**. Добавлены полезные методы.
- В Core_Form **добавлена защита от CSRF**
- В **модуль Mail** добавлена поддержка писем с рендерингом шаблона через Zend_View 
- Добавлен Sphinx\_Db\_Table\_Abstract для работы со **SphinxSE**
- Обновлен **Core_Grid**
- Добавлены исправления и улучшения в большую часть системы


## Установка ##

`php composer.phar create-project uglide/zfcore-transition путь/установки/ 1.0.1`



## Документация ##

[Документация в вики](https://github.com/uglide/zfcore-transition/wiki)