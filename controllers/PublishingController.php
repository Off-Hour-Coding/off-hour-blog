<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

use HelpersClass\Helpers;

define('BASE_PATH', dirname(__DIR__) . '/');
require_once(BASE_PATH . '_app/Configurations.php');
require_once(BASE_PATH . 'models/PostsModel.php');
require_once(BASE_PATH . 'models/UserModel.php');
require_once(BASE_PATH . '_app/functions.php');
require_once(BASE_PATH . 'classes/Helpers.php');

class PublishingController extends RenderView
{

    private $Posts;
    private $Helpers;
    private $User;
    public function __construct()
    {
        $this->Posts = new Posts();
        $this->Helpers = new Helpers();
        $this->User = new User();
    }

    public function create_new_topic()
    {
        verify_post_method();
        // verifying post data
        $author_id = "";

        $post = Post();
        if (!is_object($post)) {
            echo $this->Helpers::CreateResponse(
                "error",
                "Invalid request data",
                "400"
            );
            return;
        }
        $post_array = (array)$post;

        // Verifying post data
        if (!isset($post->title) || !isset($post->content) || !isset($post->excerpt) || !isset($post->status) || !isset($post->category_id) || !isset($post->tags)) {
            echo $this->Helpers::CreateResponse(
                "error",
                "Missing required fields",
                "400"
            );
            return;
        }

        $author_id = isset($post->author_id) ? $post->author_id : $_SESSION['user'];
        $author_id = $this->Helpers->decodeURL($author_id);

        // inserting into database
        $res = $this->Posts->Publishing(
            $post->title,
            $post->content,
            $post->excerpt,
            $author_id,
            $post->status,
            $post->category_id,
            $post->tags
        );

        // creating response 
        if ($res->status != "success") {
            if (strpos($res->message, "Duplicate entry") !== false && strpos($res->message, "for key 'slug'") !== false) {
                $response = $this->Helpers::CreateResponse("error", "The title already exists. Please choose a different title.");
            } else {
                $response = $this->Helpers::CreateResponse("error", "An error occurred while publishing...");
            }
            echo TreatedJson($response);
            return;
        }

        $response = $this->Helpers::CreateResponse("success", "Published successfully!");
        echo TreatedJson($response);
    }

    public function create_new_topic_API()
    {
        verify_post_method();
        
        if (!isset(Post()->Token)) {
            echo $this->Helpers->CreateResponse(
                "error",
                "Token required",
                "",
                "404"
            );
            return;
        }
        $id = $this->User->AuthToken(Post()->Token);
        if (!isset($id)) return;

        $response = makePostRequest(SITE . "/create/post", [
            'title' => Post()->title,
            'content' => Post()->content,
            'excerpt' => Post()->excerpt,
            'status' => Post()->status,
            'category_id' => Post()->category_id,
            'tags' => Post()->tags,
            'author_id' => $id 
        ]);

        echo $response;
    }

    public function delete_topic()
    {
        verify_post_method();
        if (!isset(Post()->id)) {
            return;
        }
        $res = $this->Posts->Delete(
            Post()->id
        );
        if ($res->status != "success") {
            $response = $this->Helpers::CreateResponse("error", "An error occurred while publishing...");
            echo TreatedJson($response);
            return;
        }
        $response = $this->Helpers::CreateResponse("success", "Published successfully!");
        echo TreatedJson($response);
        return;
    }
}
