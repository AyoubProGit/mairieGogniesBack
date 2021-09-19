<?php


namespace App\Controller\Admin\Field;


use Doctrine\DBAL\Types\ArrayType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

final class AuthorField implements FieldInterface
{
    use FieldTrait;

    public const CURRENT_USER = 'getUser';

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplateName('crud/field/currency')
            ->setFormType(ArrayType::class)
            ->addCssClass('field-currency')
            ->setCustomOption(self::CURRENT_USER, true);
    }



}