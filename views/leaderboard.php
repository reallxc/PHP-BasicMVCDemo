<?php
include 'lib/abstractView.php';

class LeaderboardView extends AbstractView {
	private $action = '';
	private $content = '';
	private $countryselect = '';
	private $showAdmin = FALSE;

	public function prepare () {
		$scores = $this->getModel()->getScores();
		$rank = 1;
		$this->content .= '<div class="leaderboardOption">##action##';
		$this->content .= 'View Option:<a href="?orderby=ASC"><button>Low to High</button></a><a href="?orderby=DESC"><button>High to Low</button></a><select onChange="setDatalimit(this.options[this.selectedIndex].value)" name="datalimit"><option value="">-List Size-</option><option value="10">10</option><option value="20">20</option><option value="30">30</option><option value="">ALL</option></select><select onChange="setCoutry(this.options[this.selectedIndex].value)" name="countryspecific"><option value="">-Country-</option><option value="">ALL</option>##countryselect##</select></div>';
		if (!empty($scores)) {
			$this->content .= '<table class="leaderboard"><tr><th>Rank</th><th>Player</th><th>Score</th><th>Date</th><th>Country</th>';
			if ($this->showAdmin) {
				$this->content .= '<th></th></tr>';
			} else {
				$this->content .= '</tr>';
			}
			foreach ($scores as $score) {
				$this->content.="<tr><td>" . $rank . "</td><td>" . $score['user'] . "</td><td>" . $score['score'] . "</td><td>" . $score['date'] . "</td><td>" . $score['country'] . "</td>";
				if ($this->showAdmin) {
					$this->content .= '<td><form action="/leaderboard/managescore" method="post"><input type="hidden" name="scoreID" value="' . $score['scoreID'] . '"><button type="submit" name="submit">Delete</button></form></td></tr>';
				} else {
					$this->content .= '</tr>';
				}
				$rank += 1;
			}
			$this->content.="</table>";
		}
		//getHTML
		$this->content .= $this->getModel()->getContent();
		$this->setTemplateField('action',$this->action);
		$this->setTemplateField('errorMsg',$this->errorMsg);
		$this->setTemplateField('content',$this->content);
	}
	public function setMemberaction()
	{
		$this->action .= '<a href="/leaderboard/submitscore"><button>New Score</button></a>';
	}
	public function setAdminaction()
	{
		$this->action .= '<a href="/leaderboard/managescore"><button>Manage</button></a>';
	}
	public function makeCountrylist($countries)
	{
		foreach ($countries as $country) {
			$this->countryselect .= '<option value="'. $country['countrycode'] .'">'. $country['countryname'] .'</option>';
		}
		$this->setTemplateField('countryselect',$this->countryselect);
	}
	public function enableAdmin()
	{
		$this->showAdmin=TRUE;
	}
}
?>
