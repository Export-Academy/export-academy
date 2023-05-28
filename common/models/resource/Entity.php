<?


namespace common\models\resource;

use common\models\base\BaseModel;

class Entity extends BaseModel
{


  public $id;
  public $name;

  public function entityInstance($properties = [])
  {
    $name = $this->name;
    return new $name($properties);
  }
}