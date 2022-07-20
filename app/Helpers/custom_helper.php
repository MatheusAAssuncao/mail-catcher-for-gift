<?php

if (! function_exists('helperShowErrorIfExists')) {
    function helperShowErrorIfExists($inputField, $errors, $applyStyle = true) {
        if(!empty($errors[$inputField])) {
            $styleClass = $applyStyle ? 'error_message' : '';
            return "<div class='$styleClass color-red'>" . $errors[$inputField] . "</div>";
        }
    
        return '';
    }
}

if (! function_exists('helperShowSuccessIfExists')) {
    function helperShowSuccessIfExists($inputField, $errors, $applyStyle = true) {
        if(!empty($errors[$inputField])) {
            $styleClass = $applyStyle ? 'success_message' : '';
            return "<div class='$styleClass color-green'>" . $errors[$inputField] . "</div>";
        }
    
        return '';
    }
}