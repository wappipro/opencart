<?php

class ModelExtensionwappiproValidator extends Model
{

    /**
     * @param $str
     *
     * @return bool
     */
    public static function isEmpty($str): bool
    {
        return !empty($str);
    }

    /**
     * @param $number
     *
     * @return bool
     */
    public static function isPhoneNumber($number): bool
    {
        if (empty($number)) {
            return false;
        }

        return !empty($number) && preg_match('/^[+0-9. ()-]*$/', $number);
    }
}
