<?php

namespace Classes\Validate;

class Validate
{

  protected $data;




  public static function isEmail($data)
  {

    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
    if (!(preg_match($regex, $data))) {
      return false;
    }
    return true;
  }
  public static function notEmplty($data)
  {
    if (!empty($data)) {
      return true;
    }
    return false;
  }
  public static function isAlphanumeric($data)
  {
    $specialChars = '@_!#$%^&*()-=+[{]};:\'",<.>/?\\|';
    if (strpbrk($data, $specialChars) !== false) {
      return false;
    }
  }
  public static function isNumeric($data)
  {
    $specialChars = '@_!#$%^&*()-=+[{]};:\'",<.>/?\\|abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if (strpbrk($data, $specialChars) !== false) {
      return false;
    }
    return true;
  }
  public static function isAlpha($data)
  {

    $specialChars = '@_!#$%^&*()-=+[{]};:\'",<.>/?\\|1234567890';
    if (strpbrk($data, $specialChars) !== false) {
      return false;
    }
    return true;
  }

  public static function isDuplicate($data, $table, $column, $column2)
  {
    if (!empty($column2)) {
      $checkDuplicate = $con->query("SELECT $column FROM $table where $column2 = $data");
      if ($checkDuplicate->num_rows != 0) {
        return true;
      }
    } else {
      $checkDuplicate = $con->query("SELECT $column FROM $table where $column = $data");
      if ($checkDuplicate->num_rows != 0) {
        return true;
      }
    }
  }
};
