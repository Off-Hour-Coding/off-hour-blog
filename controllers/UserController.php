<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use HelpersClass\Helpers;

define('BASE_PATH', dirname(__DIR__) . '/');
require_once(BASE_PATH . '_app/Configurations.php');
require_once(BASE_PATH . 'models/UserModel.php');
require_once(BASE_PATH . '_app/functions.php');
require_once(BASE_PATH . 'classes/Helpers.php');


class UserController extends RenderView
{

    private $User;
    private $Helpers;

    public function __construct()
    {
        $this->User = new User();
        $this->Helpers = new Helpers();
    }

    public function index()
    {
    }

    public function get_user_by_id()
    {

        verify_post_method();

        if (!isset(Post()->id)) return;

        $id = Post()->id;

        $res = $this->User->FetchUserByID($id);

        if ($res->status == "success") {

            $response = $this->Helpers::CreateResponse(
                $type = "success",
                $message = "",
                $data = $res->results[0],
                $status_code = 200,
            );
            echo TreatedJson($response);
            return;
        }

        echo TreatedJson( $this->Helpers::CreateResponse(
            "error",
            "An error occurred while fetching user...",
            412
        ) );
    }
}
