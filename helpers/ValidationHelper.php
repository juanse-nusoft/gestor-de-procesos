<?php

namespace Helpers;

use Helpers\Alert;

class ValidationHelper {

    public static function validateFields(array $fields) {
        foreach ($fields as $fieldName => $value) {
            if (empty(trim($value))) {
                Alert::setAlert('error', ucfirst($fieldName) . ' es obligatorio.');
            }
        }
    }
}
