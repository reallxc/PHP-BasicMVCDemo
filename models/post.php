<?php
include 'models/player.php';

class PostModel extends AbstractModel
{
  private $db;
	private $postID;
	private $userID;
	private $title;
	private $content;
  private $date;

  public function __construct($db, $postID, $userID, $title, $content, $date)
  {
    parent::__construct($db);
    $this->postID = $postID;
		$this->userID = $userID;
		$this->title = $title;
    $this->content = $content;
		$this->date = $date;
  }

  public function getPost()
  {
    $player = new PlayerModel($this->getDB(), $this->userID);
    return array('postID' => $this->postID, 'userID' => $this->userID, 'user' => $player->getusername(), 'title' => $this->title, 'content' => $this->content, 'date' => $this->date );
  }
  public static function addPost($db,$userID,$title,$content)
  {
    $sql="INSERT INTO Forum (userID, title, content, date) VALUES ('$userID', '$title', '$content', NOW())";
		$result=$db->execute($sql);
		return $result;
  }
  public static function delPost($db,$postID)
  {
    $sql="DELETE FROM Forum WHERE postID = $postID";
		$result=$db->execute($sql);
		return $result;
  }
  public static function updatePost($db,$postID,$title,$content)
  {
    $sql="UPDATE Forum SET title = '$title', content = '$content' WHERE postID = $postID";
		$result=$db->execute($sql);
		return $result;
  }
  public static function getPostByID($db,$postID)
  {
    $sql="SELECT postID, userID, title, content, date FROM Forum WHERE postID=" . $postID;
    $result=$db->query($sql);
    if (count($result)==1) {
      return $result[0];
    } else {
      return NULL;
    }

  }
}
