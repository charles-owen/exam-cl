<?php
/**
 * View class for automatic exam creation.
 */

namespace CL\Exam;


use CL\Site\Site;
use CL\Site\System\Server;
use CL\Course\Member;

/**
 * View class for automatic exam creation.
 */
class ExamView extends \CL\Course\View {
	/**
	 * ExamView constructor.
	 * @param Site $site Site object
	 * @param array $get $_GET
	 * @param array $options Options to pass to startup
	 * @param Server|null $server Server object
	 */
	public function __construct(Site $site, array $get, array $options = [], Server $server=null) {
		if($server === null) {
			$server = new Server();
		}

		$options['at-least'] = Member::TA;
		parent::__construct($site, $options);

		$this->addCSS('vendor/cl/exam/exam.css');

		$this->key = !empty($get['key']);
		$this->exam = isset($get['exam']) ? strip_tags($get['exam']) : 'A';
		$this->seed = isset($get['seed']) ? strip_tags($get['seed']) : mt_rand(1, 999999);
		if(isset($get['reseed'])) {
			$this->seed = mt_rand(1, 999999);
			$keystr = $this->key ? "&key" : "";
			$server->redirect("?exam=$this->exam&seed=$this->seed$keystr");
		}

		$this->seed();
	}

	/**
	 * Property get magic method
	 *
	 * <b>Properties</b>
	 * Property | Type | Description
	 * -------- | ---- | -----------
	 *
	 * @param string $property Property name
	 * @return mixed
	 */
	public function __get($property) {
		switch($property) {
			case 'key':
				return $this->key;

			case 'exam':
				return $this->exam;

			default:
				return parent::__get($property);
		}
	}

	/**
	 * Property set magic method
	 *
	 * <b>Properties</b>
	 * Property | Type | Description
	 * -------- | ---- | -----------
	 *
	 * @param string $property Property name
	 * @param mixed $value Value to set
	 */
	public function __set($property, $value) {
		switch($property) {
			case 'title':
				$this->title = $value;

				$value .= $this->exam;
				if($this->key) {
					$value .= ' Key';
				}
				$this->setTitle($value);
				break;

			default:
				parent::__set($property, $value);
				break;
		}
	}


	/**
	 * Generate the page header.
	 * @param bool $contentDiv Ignored
	 * @param string $nav Ignored
	 * @return string HTML
	 */
	public function header($contentDiv = true, $nav = '') {
		$html = '';

		$keyCheck = $this->key ? " checked" : '';
		$title = $this->site->siteName . ' ' . $this->title;
		$keyStr = $this->key ? " Key-" . $this->exam : "&nbsp;";
		$season = $this->section->season;
		$year = $this->section->year;

		$html = <<<HTML
<div class="cl-exam">

	<form class="cl-exam-form" method="get">
		<p>Exam: <input class="cl-exam-name" name="exam" type="text" value="$this->exam">
			Key: <input type="checkbox" name="key"$keyCheck>
			Seed: <input class="cl-exam-seed" name="seed" type="text" value="$this->seed"> <input type="submit" name="reseed" value="New">
			<input type="submit">
		</p>
	</form>

	<header>
		<div class="left"><h1>$title</h1>
			<p><em>$season, $year exam $this->seed</em></p></div>
		<div class="right"><h2 class="name">Name: <span class="blank">$keyStr</span><br>
				PID: <span class="blank">&nbsp;</span></h2></div>
	</header>
HTML;

		return $html;
	}

	/**
	 * Generate the page footer
	 * @param bool $contentDiv ignored
	 * @return string HTML
	 */
	public function footer($contentDiv = true) {
		return '';
	}

	/**
	 * Create a page break
	 * @return string HTML
	 */
	public function page() {
		return '<div class="break"></div>';
	}

	/**
	 * Seed the random number generators for reproducible questions.
	 */
	public function seed() {
		mt_srand($this->seed);
		srand($this->seed);
	}

	private $title = 'Exam';

	private $key;
	private $exam;
	private $seed;

}