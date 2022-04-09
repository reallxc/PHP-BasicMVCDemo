<?php
include 'lib/abstractController.php';
include 'models/leaderboard.php';
include 'views/leaderboard.php';

class LeaderboardController extends AbstractController {
	private $path;
	public function __construct($context) {
		parent::__construct($context);
		$this->path=$context->getURI()->getRemainingParts();
	}

	protected function getView($isPostback) {
		$db=$this->getDB();
		$model=new LeaderboardModel($db, $this->getContext(), $this->path);
		$view=new LeaderboardView();
	  $view->setModel($model);
	  $view->setTemplate((WEBSITE_INI['folder'] . '/masterPage.html'));
	  $path=explode("/",$this->path);
	  $menubar = $this->getMenubar();
	  $errorMsg = '';
		if (isset($_GET["error"])) {
			$errorMsg = $this->getError($_GET["error"]);
			$view->setErrorMSG($errorMsg);
		}
	  $view->setTemplateField('menubar',$menubar);
		$view->makeCountrylist($this->getCountrylist());
		//check URL
		switch ($this->path) {
			case 'leaderboard/submitscore':
				//submit new score page
				if ($isPostback) {
					$this->submitScore($model,$_POST);
				}
				break;
			case 'leaderboard/managescore':
				//manage score score page
				if ($isPostback) {
					$this->deleteScore($model,$_POST);
				} else {
					$parameters = $this->processLeaderboardParameters($_GET); //transfer GET to array, filter needed values
					$model->prepareScore($parameters['orderby'],$parameters['datalimit'],$parameters['countryspecific']);
					$view->enableAdmin();
				}

				break;
			default:
				//default view, load leaderboard
				$parameters = $this->processLeaderboardParameters($_GET);
				$model->prepareScore($parameters['orderby'],$parameters['datalimit'],$parameters['countryspecific']);
				if ($this->getContext()->getUser()->isMember()) {
					$view->setMemberaction();
					if ($this->getContext()->getUser()->isAdmin()) {
						$view->setAdminaction();
					}
				}
				break;
		}
		return $view;
}
		//filtering values from GET method
		private function processLeaderboardParameters($getvalues)
		{
			$result = array('orderby' => NULL, 'datalimit' => NULL, 'countryspecific' => NULL);
			if (isset($getvalues["orderby"])) {
				$result['orderby'] = $getvalues["orderby"];
			}
			if (isset($getvalues["datalimit"])) {
				$result['datalimit'] = $getvalues["datalimit"];
			}
			if (isset($getvalues["countryspecific"])) {
				$result['countryspecific'] = $getvalues["countryspecific"];
			}
			return $result;
		}

		public function getCountrylist()
		{
			$country = new CountryModel($this->getContext()->getDB());
			return $country->getCountries();
		}
		public function submitScore($model,$postArray)
		{
			$userID = $this->getContext()->getUser()->getUserID();
			$score = $postArray['score'];
			$countrycode = $postArray['country'];
			$result=$model->addScore($userID,$score,$countrycode);
			if ($result) {
				header("location: /leaderboard?error=submitScoreSuc");
			} else {
				header("location: /leaderboard?error=error");
			}
		}
		public function deleteScore($model,$postArray)
		{
			$scoreID = $postArray['scoreID'];
			$result = $model->dropScore($scoreID);
			if ($result) {
				header("location: /leaderboard/managescore?error=deleteScoreSuc");
			} else {
				header("location: /leaderboard/managescore?error=error");
			}
		}

}
?>
