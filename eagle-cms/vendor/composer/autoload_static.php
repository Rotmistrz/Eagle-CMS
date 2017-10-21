<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4ce1519f0248db1f18213ea0b34ac731
{
    public static $files = array (
        '0fd030c8ad534ff01a70126bad46847e' => __DIR__ . '/../..' . '/eagle-config.php',
    );

    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twig\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twig\\' => 
        array (
            0 => __DIR__ . '/..' . '/twig/twig/src',
        ),
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
        'Button' => __DIR__ . '/../..' . '/classes/Forms/Button.php',
        'CategoriesCollection' => __DIR__ . '/../..' . '/classes/CategoriesCollection.php',
        'CategoriesCollectionFactory' => __DIR__ . '/../..' . '/classes/CategoriesCollectionFactory.php',
        'CategoriesList' => __DIR__ . '/../..' . '/classes/CategoriesList.php',
        'Category' => __DIR__ . '/../..' . '/classes/Category.php',
        'Checkbox' => __DIR__ . '/../..' . '/classes/Forms/Checkbox.php',
        'CheckboxesGroup' => __DIR__ . '/../..' . '/classes/Forms/CheckboxesGroup.php',
        'ChoiceForm' => __DIR__ . '/../..' . '/classes/Forms/ChoiceForm.php',
        'Component' => __DIR__ . '/../..' . '/classes/Component.php',
        'ContentManager' => __DIR__ . '/../..' . '/classes/ContentManager.php',
        'Contents' => __DIR__ . '/../..' . '/classes/Contents.php',
        'DataBase' => __DIR__ . '/../..' . '/classes/DataBase.php',
        'Eventviva\\ImageResize' => __DIR__ . '/..' . '/eventviva/php-image-resize/lib/ImageResize.php',
        'Eventviva\\ImageResizeException' => __DIR__ . '/..' . '/eventviva/php-image-resize/lib/ImageResize.php',
        'File' => __DIR__ . '/../..' . '/classes/File.php',
        'FileField' => __DIR__ . '/../..' . '/classes/Forms/FileField.php',
        'FileNotFoundException' => __DIR__ . '/../..' . '/classes/Exceptions/FileNotFoundException.php',
        'FileType' => __DIR__ . '/../..' . '/classes/FileType.php',
        'FileTypeException' => __DIR__ . '/../..' . '/classes/Exceptions/FileTypeException.php',
        'FileUploader' => __DIR__ . '/../..' . '/classes/FileUploader.php',
        'Form' => __DIR__ . '/../..' . '/classes/Forms/Form.php',
        'FormField' => __DIR__ . '/../..' . '/classes/Forms/FormField.php',
        'FormItem' => __DIR__ . '/../..' . '/interfaces/FormItem.php',
        'FormManager' => __DIR__ . '/../..' . '/classes/Forms/FormManager.php',
        'Gallery' => __DIR__ . '/../..' . '/classes/Gallery.php',
        'GalleryPicture' => __DIR__ . '/../..' . '/classes/GalleryPicture.php',
        'GalleryPicturesCollection' => __DIR__ . '/../..' . '/classes/GalleryPicturesCollection.php',
        'Hideable' => __DIR__ . '/../..' . '/interfaces/Hideable.php',
        'Information' => __DIR__ . '/../..' . '/classes/Information.php',
        'InformationManager' => __DIR__ . '/../..' . '/classes/InformationManager.php',
        'Input' => __DIR__ . '/../..' . '/classes/Forms/Input.php',
        'Item' => __DIR__ . '/../..' . '/classes/Item.php',
        'ItemsCollection' => __DIR__ . '/../..' . '/classes/ItemsCollection.php',
        'ItemsCollectionFactory' => __DIR__ . '/../..' . '/classes/ItemsCollectionFactory.php',
        'Label' => __DIR__ . '/../..' . '/classes/Forms/Label.php',
        'Languagable' => __DIR__ . '/../..' . '/interfaces/Languagable.php',
        'LanguagableCollection' => __DIR__ . '/../..' . '/interfaces/LanguagableCollection.php',
        'Language' => __DIR__ . '/../..' . '/classes/Language.php',
        'NoCategory' => __DIR__ . '/../..' . '/classes/NoCategory.php',
        'NoGalleryPicturesCollection' => __DIR__ . '/../..' . '/classes/NoGalleryPicturesCollection.php',
        'NoItem' => __DIR__ . '/../..' . '/classes/NoItem.php',
        'NoSuchCategory' => __DIR__ . '/../..' . '/classes/NoSuchCategory.php',
        'NoSuchGalleryPicture' => __DIR__ . '/../..' . '/classes/NoSuchGalleryPicture.php',
        'NoSuchItem' => __DIR__ . '/../..' . '/classes/NoSuchItem.php',
        'Order' => __DIR__ . '/../..' . '/classes/Order.php',
        'Orderable' => __DIR__ . '/../..' . '/interfaces/Orderable.php',
        'Select' => __DIR__ . '/../..' . '/classes/Forms/Select.php',
        'TemplateManager' => __DIR__ . '/../..' . '/classes/TemplateManager.php',
        'TextEditor' => __DIR__ . '/../..' . '/classes/Forms/TextEditor.php',
        'Textarea' => __DIR__ . '/../..' . '/classes/Forms/Textarea.php',
        'TwigTemplateManager' => __DIR__ . '/../..' . '/classes/TwigTemplateManager.php',
        'User' => __DIR__ . '/../..' . '/classes/User.php',
        'UserRegisterException' => __DIR__ . '/../..' . '/classes/UserRegisterException.php',
        'Viewable' => __DIR__ . '/../..' . '/interfaces/Viewable.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4ce1519f0248db1f18213ea0b34ac731::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4ce1519f0248db1f18213ea0b34ac731::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit4ce1519f0248db1f18213ea0b34ac731::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit4ce1519f0248db1f18213ea0b34ac731::$classMap;

        }, null, ClassLoader::class);
    }
}
