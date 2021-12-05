<?php
function validate($what,$conditions)
{
    
    //validate inputs 
    $errors = [];
    $error = true;
    
    foreach ($what as $input_key =>  $input) {
        foreach ($conditions as $expected) {
            if($input_key == $expected && strlen($input)>0)
            {   
                $error = false;
                //check if it s smaller than 255
                if( strlen($input) >= 255)
                {
                    $errors[$expected] = $expected . ' should be smaller than 255!';
                }
                break;
            }
        }
        if($error)
        {   
            $errors[$input_key] = $input_key . ' is required!';
        }
        $error = true;
    }
    return $errors;
}