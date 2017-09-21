<?php

namespace Btn\DomainBundle\Util;

use Assert\AssertionFailedException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

class FormUtil
{
    public static function passValidationErrorsToForm(AssertionFailedException $exception, FormInterface $form)
    {
        $propertyPath = $exception->getPropertyPath();
        $formError = new FormError($exception->getMessage());

        if ($form->has($propertyPath)) {
            $form->get($propertyPath)->addError($formError);

            return;
        }

        $propertyPathLowerCase = StringUtil::camelToUnderscore($propertyPath);
        if ($form->has($propertyPathLowerCase)) {
            $form->get($propertyPathLowerCase)->addError($formError);

            return;
        }

        $form->addError($formError);
    }
}
