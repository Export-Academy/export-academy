<?php


namespace admin\controller;

use common\controller\Controller;
use common\models\access\Grants;
use common\models\access\Permission;
use common\models\access\Role;
use lib\app\database\Database;
use lib\app\route\Router;
use lib\util\Helper;

class UserController extends Controller
{
  public function secure()
  {
    return [
      "requiresAuth" => ["*"]
    ];
  }

  public function actionIndex()
  {
    $this->render('index', ["title" => "User Management"]);
  }


  public function actionRole()
  {
    $method = $this->request->method();


    switch ($method) {
      case "POST":
        $data = $this->request->data("Role", []);

        $id = Helper::getValue("id", $data);
        $role = Role::findOne(["id" => $id]);

        if (!isset($role)) {
          $role = new Role($data);
        } else {
          Helper::configure($role, $data);
        }

        $isNewRecord = $role->isNewRecord();


        $role->save();
        if ($isNewRecord) {
          Router::redirect("/academy/admin/user/role");
          return;
        }
        Router::redirect("/academy/admin/user/update_role?role=$role->id");


      default:
        $role_id = $this->request->params("role", false);
        $role = null;

        if ($role_id)
          $role = Role::findOne(["id" => $role_id]);


        $params = [
          "title" => "Manage User Roles",
        ];
        $view = "role";



        if ($role) {
          $params = [
            "title" => "$role->name Role",
            "role" => $role
          ];
          $view = "role-view";
        } else {
          $params["roles"] = Role::find()->all();
        }

        $this->render($view, $params);
        return;
    }
  }


  public function actionUpdateRole()
  {

    $method = $this->request->method();


    switch ($method) {
      case "POST":
        $data = $this->request->data("Grants", []);
        $roleId = $this->request->data("role");


        $db = Database::instance();

        $db->transaction(function ($tr) use ($roleId, $data) {
          $grants = Grants::find(["role_id" => +$roleId])->all();
          $updated = [];
          $removed = [];

          foreach ($grants as $grant) {
            $enabled = Helper::getValue($grant->permission_id, $data, null);

            if (!isset($enabled)) {
              $removed[] = $grant->permission_id;
            } else {
              $updated[$grant->permission_id] = $enabled;
            }
          }

          if (!empty($removed))
            Grants::deleteByCondition(["role_id" => +$roleId, "permission_id" => $removed], $tr);


          $toUpdate = array_diff_key($data, $updated);

          foreach ($toUpdate as $permission_id => $_) {
            $grant = new Grants(["role_id" => +$roleId, "permission_id" => +$permission_id]);
            $grant->save($tr);
          }
        });


        Router::redirect("/academy/admin/user/update_role?role=$roleId");
        return;


      default:
        $role_id = $this->request->params("role", false);
        $role = Role::findOne(["id" => $role_id]);

        if (!$role) {
          Router::redirect("/academy/admin/user/role");
          return;
        }

        /** @var Permission[] $permissions */
        $permissions = $role->permissions;
        $this->render('role-update', ["title" => "$role->name Role", "role" => $role, "granted_permissions" => $permissions]);
        return;
    }
  }



  public function actionDeleteRole()
  {
    $roleId = $this->request->params("role", null);
    if (!isset($roleId)) Router::redirect("/academy/admin/user/role");

    $role = Role::findOne(["id" => +$roleId]);

    if ($role)
      $role->delete();

    Router::redirect("/academy/admin/user/role");
  }


  public function actionPermission()
  {
    $permissions = Permission::find()->all();
    $this->render('permission', ['title' => "Permission Management", "permissions" => $permissions]);
  }
}
