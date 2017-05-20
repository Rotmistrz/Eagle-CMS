<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4ce1519f0248db1f18213ea0b34ac731
{
    public static $files = array (
        '0fd030c8ad534ff01a70126bad46847e' => __DIR__ . '/../..' . '/eagle-config.php',
    );

    public static $prefixesPsr0 = array (
        'T' => 
        array (
            'Twig_' => 
            array (
                0 => __DIR__ . '/..' . '/twig/twig/lib',
            ),
        ),
    );

    public static $classMap = array (
        'Button' => __DIR__ . '/../..' . '/classes/Button.php',
        'CategoriesCollection' => __DIR__ . '/../..' . '/classes/CategoriesCollection.php',
        'CategoriesList' => __DIR__ . '/../..' . '/classes/CategoriesList.php',
        'Category' => __DIR__ . '/../..' . '/classes/Category.php',
        'Checkbox' => __DIR__ . '/../..' . '/classes/Checkbox.php',
        'CheckboxesGroup' => __DIR__ . '/../..' . '/classes/CheckboxesGroup.php',
        'ChoiceForm' => __DIR__ . '/../..' . '/classes/ChoiceForm.php',
        'Component' => __DIR__ . '/../..' . '/classes/Component.php',
        'ContentManager' => __DIR__ . '/../..' . '/classes/ContentManager.php',
        'Contents' => __DIR__ . '/../..' . '/classes/Contents.php',
        'DataBase' => __DIR__ . '/../..' . '/classes/DataBase.php',
        'FileField' => __DIR__ . '/../..' . '/classes/FileField.php',
        'FileUploader' => __DIR__ . '/../..' . '/classes/FileUploader.php',
        'Form' => __DIR__ . '/../..' . '/classes/Form.php',
        'FormField' => __DIR__ . '/../..' . '/classes/FormField.php',
        'FormItem' => __DIR__ . '/../..' . '/interfaces/FormItem.php',
        'FormManager' => __DIR__ . '/../..' . '/classes/FormManager.php',
        'Information' => __DIR__ . '/../..' . '/classes/Information.php',
        'InformationManager' => __DIR__ . '/../..' . '/classes/InformationManager.php',
        'Input' => __DIR__ . '/../..' . '/classes/Input.php',
        'Item' => __DIR__ . '/../..' . '/classes/Item.php',
        'ItemsCollection' => __DIR__ . '/../..' . '/classes/ItemsCollection.php',
        'ItemsCollectionFactory' => __DIR__ . '/../..' . '/classes/ItemsCollectionFactory.php',
        'Label' => __DIR__ . '/../..' . '/classes/Label.php',
        'Languagable' => __DIR__ . '/../..' . '/interfaces/Languagable.php',
        'LanguagableCollection' => __DIR__ . '/../..' . '/interfaces/LanguagableCollection.php',
        'Language' => __DIR__ . '/../..' . '/classes/Language.php',
        'NoCategory' => __DIR__ . '/../..' . '/classes/NoCategory.php',
        'NoItem' => __DIR__ . '/../..' . '/classes/NoItem.php',
        'NoSuchCategory' => __DIR__ . '/../..' . '/classes/NoSuchCategory.php',
        'NoSuchItem' => __DIR__ . '/../..' . '/classes/NoSuchItem.php',
        'Order' => __DIR__ . '/../..' . '/classes/Order.php',
        'Orderable' => __DIR__ . '/../..' . '/interfaces/Orderable.php',
        'Select' => __DIR__ . '/../..' . '/classes/Select.php',
        'TemplateManager' => __DIR__ . '/../..' . '/classes/TemplateManager.php',
        'Textarea' => __DIR__ . '/../..' . '/classes/Textarea.php',
        'Viewable' => __DIR__ . '/../..' . '/interfaces/Viewable.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit4ce1519f0248db1f18213ea0b34ac731::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit4ce1519f0248db1f18213ea0b34ac731::$classMap;

        }, null, ClassLoader::class);
    }
}
