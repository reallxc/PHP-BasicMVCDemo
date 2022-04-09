<?php
include 'lib/abstractView.php';

class ForumView extends AbstractView {
	private $action = '';
	private $postaction = '';
	private $content = '';
	private $showAdmin = FALSE;
	private $titleOnly = FALSE;
	private $crtUser;
	public function __construct($crtUser)
	{
		//Get Curretn logged in user's ID
		$this->crtUser = $crtUser;
	}
	public function prepare () {
		$posts=$this->getModel()->getPosts();
		$count = 1;
		$this->content .= '##action##';
		if (!empty($posts)) {
			foreach ($posts as $post) {
				$this->content .= '<div class="forumList"><table>';
				if ($this->titleOnly) {
					$this->content .= '<tr><td class="forumCount">#'.$count.'</td><td class="forumTitle">' . $post['title'] . '</td>';
					$this->content .= '<td class="forumDate">' . $post['date'] . '</td></tr>';
				} else {
					$this->content .= '<tr><td class="forumTitle">' . $post['title'] . '</td>';
					$this->content .= '<td class="forumUsername">' . $post['user'] . '</td></tr>';
					$this->content .= '<tr><td class="forumContent" colspan="2">' . $post['content'] . '</td></tr>';
					$this->content .= '<tr><td>';
					// ##postaction##
					if ($this->crtUser==$post['userID']||$this->showAdmin==TRUE) {
						$this->content .= '<form action="/forum?action=del" method="post"><input type="hidden" name="postID" value="' . $post['postID'] . '"><button type="submit" name="submit">Delete</button></form>';
						if ($this->crtUser==$post['userID']) {
							$this->content .= '<form action="/forum/edit" method="post"><input type="hidden" name="postID" value="' . $post['postID'] . '"><button type="submit" name="submit">Edit</button></form>';
						}
					}
					$this->content .= '</td><td class="forumDate">' . $post['date'] . '</td></tr>';
				}
				$this->content .= '</table></div>';
				$count += 1;
			}
		}

		$this->content .= $this->getModel()->getContent();
		$this->setTemplateField('errorMsg',$this->errorMsg);
		$this->setTemplateField('action',$this->action);
		$this->setTemplateField('content',$this->content);
	}
	public function setMemberaction()
	{
		$this->action .= '<a href="/forum/new"><button>New Post</button></a>';
		$this->action .= '<a href="/forum/list"><button>List View</button></a>';

	}
	public function setAdminaction()
	{
		$this->postaction .= '<form action="/forum" method="post"><input type="hidden" name="postID" value="' . '"><button type="submit" name="submit">Delete</button></form>';
	}
	public function enableAdmin()
	{
		$this->showAdmin=TRUE;
	}
	public function enableTitleonly()
	{
		$this->titleOnly=TRUE;
	}
}
