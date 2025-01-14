<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1215edd8bb5002c109c774425fb393e5
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PrisjaktFeed\\' => 13,
        ),
        'M' => 
        array (
            'Micropackage\\Requirements\\' => 26,
            'Micropackage\\Internationalization\\' => 34,
        ),
        'A' => 
        array (
            'Ageno\\Prisjakt\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PrisjaktFeed\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Micropackage\\Requirements\\' => 
        array (
            0 => __DIR__ . '/..' . '/micropackage/requirements/src',
        ),
        'Micropackage\\Internationalization\\' => 
        array (
            0 => __DIR__ . '/..' . '/micropackage/internationalization/src',
        ),
        'Ageno\\Prisjakt\\' => 
        array (
            0 => __DIR__ . '/..' . '/ageno-packages/prisjakt-feed',
        ),
    );

    public static $classMap = array (
        'Ageno\\Prisjakt\\Api\\FeedItemInterface' => __DIR__ . '/..' . '/ageno-packages/prisjakt-feed/Api/FeedItemInterface.php',
        'Ageno\\Prisjakt\\Api\\SettingsProviderInterface' => __DIR__ . '/..' . '/ageno-packages/prisjakt-feed/Api/SettingsProviderInterface.php',
        'Ageno\\Prisjakt\\Api\\TemplateInterface' => __DIR__ . '/..' . '/ageno-packages/prisjakt-feed/Api/TemplateInterface.php',
        'Ageno\\Prisjakt\\Component\\Adapter\\ResourceAdapterInterface' => __DIR__ . '/..' . '/ageno-packages/prisjakt-feed/Component/Adapter/ResourceAdapterInterface.php',
        'Ageno\\Prisjakt\\Model\\DataObject' => __DIR__ . '/..' . '/ageno-packages/prisjakt-feed/Model/DataObject.php',
        'Ageno\\Prisjakt\\Model\\Feed' => __DIR__ . '/..' . '/ageno-packages/prisjakt-feed/Model/Feed.php',
        'Ageno\\Prisjakt\\Model\\FeedItem' => __DIR__ . '/..' . '/ageno-packages/prisjakt-feed/Model/FeedItem.php',
        'Ageno\\Prisjakt\\Model\\FeedItem\\Collection' => __DIR__ . '/..' . '/ageno-packages/prisjakt-feed/Model/FeedItem/Collection.php',
        'Ageno\\Prisjakt\\View\\FeedItemTemplate' => __DIR__ . '/..' . '/ageno-packages/prisjakt-feed/View/FeedItemTemplate.php',
        'Ageno\\Prisjakt\\View\\FeedTemplate' => __DIR__ . '/..' . '/ageno-packages/prisjakt-feed/View/FeedTemplate.php',
        'Ageno\\Prisjakt\\View\\Template' => __DIR__ . '/..' . '/ageno-packages/prisjakt-feed/View/Template.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Micropackage\\Internationalization\\Internationalization' => __DIR__ . '/..' . '/micropackage/internationalization/src/Internationalization.php',
        'Micropackage\\Requirements\\Abstracts\\Checker' => __DIR__ . '/..' . '/micropackage/requirements/src/Abstracts/Checker.php',
        'Micropackage\\Requirements\\Checker\\DocHooks' => __DIR__ . '/..' . '/micropackage/requirements/src/Checker/DocHooks.php',
        'Micropackage\\Requirements\\Checker\\PHP' => __DIR__ . '/..' . '/micropackage/requirements/src/Checker/PHP.php',
        'Micropackage\\Requirements\\Checker\\PHPExtensions' => __DIR__ . '/..' . '/micropackage/requirements/src/Checker/PHPExtensions.php',
        'Micropackage\\Requirements\\Checker\\Plugins' => __DIR__ . '/..' . '/micropackage/requirements/src/Checker/Plugins.php',
        'Micropackage\\Requirements\\Checker\\SSL' => __DIR__ . '/..' . '/micropackage/requirements/src/Checker/SSL.php',
        'Micropackage\\Requirements\\Checker\\Theme' => __DIR__ . '/..' . '/micropackage/requirements/src/Checker/Theme.php',
        'Micropackage\\Requirements\\Checker\\WP' => __DIR__ . '/..' . '/micropackage/requirements/src/Checker/WP.php',
        'Micropackage\\Requirements\\Interfaces\\Checkable' => __DIR__ . '/..' . '/micropackage/requirements/src/Interfaces/Checkable.php',
        'Micropackage\\Requirements\\Requirements' => __DIR__ . '/..' . '/micropackage/requirements/src/Requirements.php',
        'PrisjaktFeed\\App\\Backend\\Core\\Dashboard' => __DIR__ . '/../..' . '/src/App/Backend/Core/Dashboard.php',
        'PrisjaktFeed\\App\\Backend\\Core\\Feeds\\Actions' => __DIR__ . '/../..' . '/src/App/Backend/Core/Feeds/Actions.php',
        'PrisjaktFeed\\App\\Backend\\Core\\Feeds\\Columns' => __DIR__ . '/../..' . '/src/App/Backend/Core/Feeds/Columns.php',
        'PrisjaktFeed\\App\\Backend\\Core\\Feeds\\Feed\\Feed' => __DIR__ . '/../..' . '/src/App/Backend/Core/Feeds/Feed/Feed.php',
        'PrisjaktFeed\\App\\Backend\\Core\\Feeds\\Feed\\Steps' => __DIR__ . '/../..' . '/src/App/Backend/Core/Feeds/Feed/Steps.php',
        'PrisjaktFeed\\App\\Backend\\Core\\Feeds\\Feeds' => __DIR__ . '/../..' . '/src/App/Backend/Core/Feeds/Feeds.php',
        'PrisjaktFeed\\App\\Backend\\Core\\Feeds\\Menu' => __DIR__ . '/../..' . '/src/App/Backend/Core/Feeds/Menu.php',
        'PrisjaktFeed\\App\\Backend\\Core\\Feeds\\Messages' => __DIR__ . '/../..' . '/src/App/Backend/Core/Feeds/Messages.php',
        'PrisjaktFeed\\App\\Backend\\Core\\Feeds\\PostType' => __DIR__ . '/../..' . '/src/App/Backend/Core/Feeds/PostType.php',
        'PrisjaktFeed\\App\\Backend\\Core\\Product\\Product' => __DIR__ . '/../..' . '/src/App/Backend/Core/Product/Product.php',
        'PrisjaktFeed\\App\\Backend\\Core\\Settings\\Menu' => __DIR__ . '/../..' . '/src/App/Backend/Core/Settings/Menu.php',
        'PrisjaktFeed\\App\\Backend\\Core\\Settings\\Settings' => __DIR__ . '/../..' . '/src/App/Backend/Core/Settings/Settings.php',
        'PrisjaktFeed\\App\\Backend\\Enqueue' => __DIR__ . '/../..' . '/src/App/Backend/Enqueue.php',
        'PrisjaktFeed\\App\\Backend\\Plugin' => __DIR__ . '/../..' . '/src/App/Backend/Plugin.php',
        'PrisjaktFeed\\App\\Backend\\Templates' => __DIR__ . '/../..' . '/src/App/Backend/Templates.php',
        'PrisjaktFeed\\App\\DataStorage\\Attributes\\Attributes' => __DIR__ . '/../..' . '/src/App/DataStorage/Attributes/Attributes.php',
        'PrisjaktFeed\\App\\DataStorage\\Attributes\\CustomAttributes' => __DIR__ . '/../..' . '/src/App/DataStorage/Attributes/CustomAttributes.php',
        'PrisjaktFeed\\App\\DataStorage\\DataStorage' => __DIR__ . '/../..' . '/src/App/DataStorage/DataStorage.php',
        'PrisjaktFeed\\App\\DataStorage\\Feed\\CategoryMappingAutocomplete' => __DIR__ . '/../..' . '/src/App/DataStorage/Feed/CategoryMappingAutocomplete.php',
        'PrisjaktFeed\\App\\DataStorage\\Feed\\CategoryMappingData' => __DIR__ . '/../..' . '/src/App/DataStorage/Feed/CategoryMappingData.php',
        'PrisjaktFeed\\App\\DataStorage\\Feed\\FieldMappingData' => __DIR__ . '/../..' . '/src/App/DataStorage/Feed/FieldMappingData.php',
        'PrisjaktFeed\\App\\DataStorage\\Feed\\FiltersData' => __DIR__ . '/../..' . '/src/App/DataStorage/Feed/FiltersData.php',
        'PrisjaktFeed\\App\\DataStorage\\Feed\\FiltersValues' => __DIR__ . '/../..' . '/src/App/DataStorage/Feed/FiltersValues.php',
        'PrisjaktFeed\\App\\DataStorage\\Feed\\GeneralSettingsData' => __DIR__ . '/../..' . '/src/App/DataStorage/Feed/GeneralSettingsData.php',
        'PrisjaktFeed\\App\\DataStorage\\Feed\\GlobalFeedSettings' => __DIR__ . '/../..' . '/src/App/DataStorage/Feed/GlobalFeedSettings.php',
        'PrisjaktFeed\\App\\DataStorage\\Form\\Messages' => __DIR__ . '/../..' . '/src/App/DataStorage/Form/Messages.php',
        'PrisjaktFeed\\App\\DataStorage\\Settings\\ExtraFieldsData' => __DIR__ . '/../..' . '/src/App/DataStorage/Settings/ExtraFieldsData.php',
        'PrisjaktFeed\\App\\DataStorage\\Settings\\SettingsData' => __DIR__ . '/../..' . '/src/App/DataStorage/Settings/SettingsData.php',
        'PrisjaktFeed\\App\\DataStorage\\Settings\\SystemsCheckData' => __DIR__ . '/../..' . '/src/App/DataStorage/Settings/SystemsCheckData.php',
        'PrisjaktFeed\\App\\Feed\\Mysql' => __DIR__ . '/../..' . '/src/App/Feed/Mysql.php',
        'PrisjaktFeed\\App\\Feed\\SettingsProvider' => __DIR__ . '/../..' . '/src/App/Feed/SettingsProvider.php',
        'PrisjaktFeed\\App\\Frontend\\Enqueue' => __DIR__ . '/../..' . '/src/App/Frontend/Enqueue.php',
        'PrisjaktFeed\\App\\General\\Cron' => __DIR__ . '/../..' . '/src/App/General/Cron.php',
        'PrisjaktFeed\\App\\General\\Queries' => __DIR__ . '/../..' . '/src/App/General/Queries.php',
        'PrisjaktFeed\\App\\Pages\\Feed\\CategoryMapping' => __DIR__ . '/../..' . '/src/App/Pages/Feed/CategoryMapping.php',
        'PrisjaktFeed\\App\\Pages\\Feed\\Feed' => __DIR__ . '/../..' . '/src/App/Pages/Feed/Feed.php',
        'PrisjaktFeed\\App\\Pages\\Feed\\FieldMapping' => __DIR__ . '/../..' . '/src/App/Pages/Feed/FieldMapping.php',
        'PrisjaktFeed\\App\\Pages\\Feed\\Filters' => __DIR__ . '/../..' . '/src/App/Pages/Feed/Filters.php',
        'PrisjaktFeed\\App\\Pages\\Feed\\GeneralSettings' => __DIR__ . '/../..' . '/src/App/Pages/Feed/GeneralSettings.php',
        'PrisjaktFeed\\App\\Pages\\Settings\\ExtraFieldsPage' => __DIR__ . '/../..' . '/src/App/Pages/Settings/ExtraFieldsPage.php',
        'PrisjaktFeed\\App\\Pages\\Settings\\SettingsPage' => __DIR__ . '/../..' . '/src/App/Pages/Settings/SettingsPage.php',
        'PrisjaktFeed\\App\\Pages\\Settings\\SystemsCheckPage' => __DIR__ . '/../..' . '/src/App/Pages/Settings/SystemsCheckPage.php',
        'PrisjaktFeed\\Bootstrap' => __DIR__ . '/../..' . '/src/Bootstrap.php',
        'PrisjaktFeed\\Common\\Abstracts\\Base' => __DIR__ . '/../..' . '/src/Common/Abstracts/Base.php',
        'PrisjaktFeed\\Common\\Functions' => __DIR__ . '/../..' . '/src/Common/Functions.php',
        'PrisjaktFeed\\Common\\Traits\\Requester' => __DIR__ . '/../..' . '/src/Common/Traits/Requester.php',
        'PrisjaktFeed\\Common\\Traits\\Singleton' => __DIR__ . '/../..' . '/src/Common/Traits/Singleton.php',
        'PrisjaktFeed\\Common\\Utils\\Errors' => __DIR__ . '/../..' . '/src/Common/Utils/Errors.php',
        'PrisjaktFeed\\Common\\Utils\\Fields\\Fields' => __DIR__ . '/../..' . '/src/Common/Utils/Fields/Fields.php',
        'PrisjaktFeed\\Common\\Utils\\Form\\Elements\\Element' => __DIR__ . '/../..' . '/src/Common/Utils/Form/Elements/Element.php',
        'PrisjaktFeed\\Common\\Utils\\Form\\Elements\\Input\\Checkbox' => __DIR__ . '/../..' . '/src/Common/Utils/Form/Elements/Input/Checkbox.php',
        'PrisjaktFeed\\Common\\Utils\\Form\\Elements\\Input\\Hidden' => __DIR__ . '/../..' . '/src/Common/Utils/Form/Elements/Input/Hidden.php',
        'PrisjaktFeed\\Common\\Utils\\Form\\Elements\\Input\\Radio' => __DIR__ . '/../..' . '/src/Common/Utils/Form/Elements/Input/Radio.php',
        'PrisjaktFeed\\Common\\Utils\\Form\\Elements\\Input\\Text' => __DIR__ . '/../..' . '/src/Common/Utils/Form/Elements/Input/Text.php',
        'PrisjaktFeed\\Common\\Utils\\Form\\Elements\\Label' => __DIR__ . '/../..' . '/src/Common/Utils/Form/Elements/Label.php',
        'PrisjaktFeed\\Common\\Utils\\Form\\Elements\\Select\\Select' => __DIR__ . '/../..' . '/src/Common/Utils/Form/Elements/Select/Select.php',
        'PrisjaktFeed\\Common\\Utils\\Form\\Form' => __DIR__ . '/../..' . '/src/Common/Utils/Form/Form.php',
        'PrisjaktFeed\\Common\\Utils\\Form\\FormAction' => __DIR__ . '/../..' . '/src/Common/Utils/Form/FormAction.php',
        'PrisjaktFeed\\Common\\Utils\\Form\\FormActions' => __DIR__ . '/../..' . '/src/Common/Utils/Form/FormActions.php',
        'PrisjaktFeed\\Common\\Utils\\Table\\Cell' => __DIR__ . '/../..' . '/src/Common/Utils/Table/Cell.php',
        'PrisjaktFeed\\Common\\Utils\\Table\\Column' => __DIR__ . '/../..' . '/src/Common/Utils/Table/Column.php',
        'PrisjaktFeed\\Common\\Utils\\Table\\Columns' => __DIR__ . '/../..' . '/src/Common/Utils/Table/Columns.php',
        'PrisjaktFeed\\Common\\Utils\\Table\\Notice' => __DIR__ . '/../..' . '/src/Common/Utils/Table/Notice.php',
        'PrisjaktFeed\\Common\\Utils\\Table\\Notices' => __DIR__ . '/../..' . '/src/Common/Utils/Table/Notices.php',
        'PrisjaktFeed\\Common\\Utils\\Table\\Row' => __DIR__ . '/../..' . '/src/Common/Utils/Table/Row.php',
        'PrisjaktFeed\\Common\\Utils\\Table\\Rows' => __DIR__ . '/../..' . '/src/Common/Utils/Table/Rows.php',
        'PrisjaktFeed\\Common\\Utils\\Table\\TableAction' => __DIR__ . '/../..' . '/src/Common/Utils/Table/TableAction.php',
        'PrisjaktFeed\\Common\\Utils\\Table\\TableActions' => __DIR__ . '/../..' . '/src/Common/Utils/Table/TableActions.php',
        'PrisjaktFeed\\Common\\Utils\\Tabs\\Tab' => __DIR__ . '/../..' . '/src/Common/Utils/Tabs/Tab.php',
        'PrisjaktFeed\\Common\\Utils\\Tabs\\Tabs' => __DIR__ . '/../..' . '/src/Common/Utils/Tabs/Tabs.php',
        'PrisjaktFeed\\Config\\Classes' => __DIR__ . '/../..' . '/src/Config/Classes.php',
        'PrisjaktFeed\\Config\\I18n' => __DIR__ . '/../..' . '/src/Config/I18n.php',
        'PrisjaktFeed\\Config\\Plugin' => __DIR__ . '/../..' . '/src/Config/Plugin.php',
        'PrisjaktFeed\\Config\\PluginActivation' => __DIR__ . '/../..' . '/src/Config/PluginActivation.php',
        'PrisjaktFeed\\Config\\PluginDeactivation' => __DIR__ . '/../..' . '/src/Config/PluginDeactivation.php',
        'PrisjaktFeed\\Config\\Requirements' => __DIR__ . '/../..' . '/src/Config/Requirements.php',
        'PrisjaktFeed\\Config\\Setup' => __DIR__ . '/../..' . '/src/Config/Setup.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1215edd8bb5002c109c774425fb393e5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1215edd8bb5002c109c774425fb393e5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1215edd8bb5002c109c774425fb393e5::$classMap;

        }, null, ClassLoader::class);
    }
}
