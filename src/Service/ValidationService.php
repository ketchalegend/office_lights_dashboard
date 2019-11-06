<?php
/**
 * Created by PhpStorm.
 * User: eketchabepa
 * Date: 01.03.2019
 * Time: 18:11
 */

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

/*
 * This Service validates an entity and builds flash messages if there are any errors automatically
 * You just need to return a JSONResponse in the controller and the flash messages will be displayed automatically
 * if AJAX call ist completed
 */

class ValidationService
{
    public $validator;
    public $flashMessageBuilder;

    public function __construct(ValidatorInterface $validator, FlashMessageBuilder $flashMessageBuilder)
    {
        $this->flashMessageBuilder = $flashMessageBuilder;
        $this->validator = $validator;
    }

    /**
     * This method validates an entity and creates flash messages automatically
     * It expects 1 parameter $entity which is the Entity to be validated.
     */
    public function isValid($entity)
    {
        $errors = $this->validator->validate($entity);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $errorMessage = $error->getMessage();
                $errorField = $error->getPropertyPath();
                $this->flashMessageBuilder->addErrorMessage($errorMessage, $errorField);
            };
            return false;
        }
        return true;
    }

}
