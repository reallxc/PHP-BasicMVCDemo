<?php
include 'lib/abstractModel.php';
include 'models/post.php';
class ForumModel extends AbstractModel {

	private $context;
	private $content;
	private $posts;

	public function __construct($db, $context, $path) {
		parent::__construct($db);
		$this->context=$context;
		$this->content='no content';
		$this->loadHtml($path);
	}

	private function loadHtml($path) {
		$filename=WEBSITE_INI['folder'].'/'.$path.'.html';
		$this->content=file_get_contents($filename);
	}

	private function loadPosts()
	{
		$sql="SELECT postID, userID, title, content, date FROM Forum ORDER BY postID DESC";
		$rows=$this->getDB()->query($sql);
		foreach ($rows as $row){
			$postID=$row['postID'];
			$userID=$row['userID'];
			$title=$row['title'];
			$content=$row['content'];
			$date=$row['date'];
			$post = new PostModel($this->getDB(), $postID, $userID, $title, $content, $date);
			$this->posts[]=$post->getPost();
		}
	}

	public function preparePosts()
	{
		$this->loadPosts();
	}
	public function getPosts()
	{
		return $this->posts;
	}
	public function getContent() {
		return $this->content;
	}

}
