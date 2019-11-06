<?php
/**
 * Created by PhpStorm.
 * User: eketchabepa
 * Date: 01.03.2019
 * Time: 18:12
 */

namespace App\Service;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

/*
 * Service which builds Flash Messages and saves it into the user's session
 */

class FlashMessageBuilder
{
    public $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /*
     * Builds a flash message
     * Expects at least 2 parameters:
     * $type = Type of message (error, success, info)
     * $message = Message which is shown to the user
     * $field = id of the field which has errors when validating (only needed if message of type "error")
     */
    public function addFlashMessage($type, $message, $field = "no error")
    {
        $this->session->getFlashBag()->add
        (
            $type,
            [
                "field" => $field,
                "message" => $message
            ]
        );
    }

    /*
     * Builds a flash message of type success
     * Expects 1 parameter:
     * $message = message which is shown to the user
     */
    public function addSuccessMessage($message)
    {
        $this->session->getFlashBag()->add
        (
            'success',
            [
                'message' => $message
            ]
        );
    }

    /*
     * Builds a flash message of type error
     * Expects 2 parameters:
     * $message = message which is shown to the user
     * $field = id of the field which is not valid (due to validation)
     */
    public function addErrorMessage($message, $field = "no field")
    {
        $this->session->getFlashBag()->add
        (
            'error',
            [
                'field' => $field,
                'message' => $message
            ]
        );
    }

    /*
     * Builds a flash message of type info
     * Expects 1 parameter:
     * $message = message which is shown to the user
     */
    public function addInfoMessage($message)
    {
        $this->session->getFlashBag()->add
        (
            'info',
            [
                'message' => $message
            ]
        );
    }

}
