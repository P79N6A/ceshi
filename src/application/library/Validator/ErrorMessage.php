<?php namespace Validator;


/**
 * Class ValidatorErrorMessage
 */
class ErrorMessage extends \Sirius\Validation\ErrorMessage {
    protected $translator;

    function __construct($translator=null, $template=null, $options = array()) {
        parent::__construct($template, $options);
        $this->translator = $translator;
    }

    function __toString() {
        // write your implementation here
        return $this->variables['label'];
    }
}