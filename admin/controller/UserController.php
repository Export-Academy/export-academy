<?php


namespace admin\controller;

use common\controller\Controller;
use common\models\access\Grants;
use common\models\access\Role;
use lib\app\database\Database;
use lib\app\database\Transaction;
use lib\app\log\Logger;
use lib\app\router\Router;

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
    $this->render('index', ["title" => "User Management"], 'dashboard');
  }


  public function actionRole()
  {
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

    $this->render($view, $params, 'dashboard');
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
          foreach ($data as $key => $value) {
            $grant = new Grants(["role_id" => +$roleId, "permission_id" => +$key]);

            if ($value) {
              $grant->save($tr);
            } else {
              $grant->delete($tr);
            }

            return $tr->execute();
          }
        });


        $this->jsonResponse($this->request->data());
        return;


      default:
        $role_id = $this->request->params("role", false);
        $role = Role::findOne(["id" => $role_id]);

        if (!$role) {
          Router::redirect("/academy/user/role");
          return;
        }

        /** @var Permission[] $permissions */
        $permissions = $role->permissions;
        $this->render('role-update', ["title" => "$role->name Role", "role" => $role, "granted_permissions" => $permissions], "dashboard");
        return;
    }
  }








  public function actionDeleteRole()
  {
    $roleId = $this->request->data("role", null);
    if (!isset($roleId)) $this->jsonResponse("Failed to delete role");

    Role::deleteByCondition(["id" => +$roleId]);
    Router::redirect("/academy/admin/user/role");
  }


  public function actionPermission()
  {
    $this->render('permission', ['title' => "Permission Management"], 'dashboard');
  }
}
