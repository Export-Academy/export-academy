<?php

namespace common\models;

use common\models\base\BaseModel;
use DateTimeZone;

/**
 * System default values saved in the database
 * 
 * @property string $timezone
 */
class System extends BaseModel
{
  public $timezone;

  public static function getDefaultTimezone()
  {
    $system = System::findOne();
    return new DateTimeZone($system->timezone);
  }
}
