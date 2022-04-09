<?php
include 'lib/abstractController.php';
include 'models/forum.php';
include 'views/forum.php';

class ForumController extends AbstractController {
	private $path;
	public function __construct($context) {
		parent::__construct($context);
		$this->path=$context->getURI()->getRemainingParts();
	}

	protected function getView($isPostback) {
		$db=$this->getDB();
		$model = new ForumModel($db, $this->getContext(), $this->path);
		$view = new ForumView($this->getContext()->getUser()->getUserID());
	  $view->setModel($model);
	  $view->setTemplate((WEBSITE_INI['folder'] . '/masterPage.html'));
		if ($this->getContext()->getUser()->isMember()) {
			if ($this->getContext()->getUser()->isAdmin()) {
				$view->enableAdmin();
			}
		}
		$path=explode("/",$this->path);
	  $menubar = $this->getMenubar();
	  $errorMsg = '';
		if (isset($_GET["error"])) {
			$errorMsg = $this->getError($_GET["error"]);
			$view->setErrorMSG($errorMsg);
		}
	  $view->setTemplateField('menubar',$menubar);

		//check URL
		switch ($this->path) {
			case 'forum/new':
				//submit new score page
				if ($isPostback) {
					$this->submitPost($_POST);
				}
				break;
			case 'forum/edit':
				//manage score score page
				if ($isPostback) {
					if (isset($_GET['action'])) {
						if ($_GET['action']=='submit') {
							$this->editPost($_POST);
						}
					} else {
						$post = PostModel::getPostByID($this->getDB(),$_POST['postID']);
						$view->setTemplateField('postID',$post['postID']);
						$view->setTemplateField('postTitle',$post['title']);
						$view->setTemplateField('postContent',$post['content']);
					}
				}

				break;
			case 'forum/list':
					$view->enableTitleonly();
					$model->preparePosts();
					break;
			default:
					if ($isPostback) {
						if ($_GET['action']=='del') {
							$this->deletePost($_POST['postID']);
						}
					} else {
						$model->preparePosts();
						if ($this->getContext()->getUser()->isMember()) {
							$view->setMemberaction();
							if ($this->getContext()->getUser()->isAdmin()) {
								$view->setAdminaction();
							}
						}
					}

				break;
		}
		return $view;
}
	private function deletePost($postID)
	{
		$result = PostModel::delPost($this->getDB(),$postID);
		if ($result) {
			header("location: /forum?error=deleteScoreSuc");
		} else {
			header("location: /forum?error=error");
		}
	}
	private function submitPost($postvalue)
	{
		//SQL injection security check point
		$userID = $this->getContext()->getUser()->getUserID();
		$title = $postvalue['title'];
		$content = $postvalue['content'];
		$result = PostModel::addPost($this->getDB(),$userID,$title,$content);
		if ($result) {
			header("location: /forum?error=addScoreSuc");
		} else {
			header("location: /forum?error=error");
		}
	}
	private function editPost($postvalue)
	{
		//SQL injection security check point
		$postID = $postvalue['postID'];
		$title = $postvalue['title'];
		$content = $postvalue['content'];
		$result = PostModel::updatePost($this->getDB(),$postID,$title,$content);
		if ($result) {
			header("location: /forum?error=updateSuc");
		} else {
			header("location: /forum?error=error");
		}
	}


}
?>
