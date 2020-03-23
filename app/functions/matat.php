<?php

if (! function_exists('is_dev')) {
    function is_dev()
    {
        $email = auth()->user()->email;
        $regex = '/@matat\.co\.il$/i';
        return preg_match($regex, $email);
    }
}
