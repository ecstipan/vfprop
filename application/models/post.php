<?php
require_once(APP_DIR . 'models/user.php');
require_once(APP_DIR . 'models/show.php');
require_once(APP_DIR . 'plugins/fb.php');
global $config, $facebook;

class Post extends Model {
	public $id = -1;
	public $addedBy = '';
	public $show = '';
	public $dateAdded = 0;
	public $text = "";
	public $lat = 1;
	public $lon = 1;
	public $seen = 0;
	
	public function getByID($id)
	{
		$id = intval($this->escapeString($id));
		$result = $this->query('SELECT * FROM posts WHERE id="'. $id .'"');

		if (!isset($result[0])) return false;

		//update this user object with our shit
		$this->id = $result[0]->id;
		$this->addedBy = intval($result[0]->posted_by);
		$this->show = intval($result[0]->show_id);
		$this->text = stripslashes($result[0]->text);
		$this->dateAdded = intval($result[0]->date_posted);
		$this->lat = $result[0]->lat;
		$this->lon = $result[0]->lon;
		$this->seen = intval($result[0]->seen) == 1;
		$us = new User;
		$sh = new Show;
		$this->addedBy = $us->getByID($this->addedBy);
		$this->show = $sh->getByID($this->show);
		
		return $this;
	}

	public function filterText($text = false){
		if (!$text || $text == '') return false;
		$this->text = $this->escapeString(substr($text, 0, 30));
		return true;
	}

	public function setPosition($lat = false, $lon = false) {
		if (!$lat || !$lon) return false;
		$this->lat = $this->escapeString($lat);
		$this->lon = $this->escapeString($lon);
		return true;
	}
	
	public function create()
	{
		//actually update the post
		$result = $this->query('INSERT INTO posts
		(`posted_by`,
		`show_id`,
		`text`,
		`lat`,
		`lon`
		) VALUES (
		"'.$this->escapeString($this->addedBy->id).'",
		"'.trim($this->escapeString($this->show->id)).'",
		"'.trim($this->escapeString($this->text)).'",
		"'.trim($this->escapeString($this->lat)).'",
		"'.trim($this->escapeString($this->lon)).'");', false);

		return $this;
	}
	
	public function ban()
	{
		return $this->addedBy->ban();
	}

	public function usePost()
	{
		$this->query('UPDATE `posts` SET `posts`.`seen` = \'2\' WHERE `posts`.`id`=\''. $this->id .'\';', false);
	}

	public function showUsed() {
		$this->query('UPDATE `posts` SET `posts`.`seen` = \'3\' WHERE `posts`.`id`=\''. $this->id .'\';', false);
	}
	
	public function delete($grouped = false)
	{
		//actually delete
		if ($grouped)
			$this->query('DELETE FROM posts WHERE LOWER(text) = "'. trim($this->escapeString(strtolower($this->text))) .'";', false);
		else
			$this->query('DELETE FROM posts WHERE id="'. $this->id .'"', false);
		return true;
	}
	
	public function markSeen($grouped = false)
	{
		//set our seen flag to 1
		if ($grouped === true)
			$this->query('UPDATE `posts` SET `posts`.`seen` = \'1\' WHERE LOWER(`posts`.`text`) = \''. trim($this->escapeString(strtolower($this->text))) .'\';', false);
		else
			$this->query('UPDATE `posts` SET `posts`.`seen` = \'1\' WHERE `posts`.`id`=\''. $this->id .'\';', false);
		return $this;
	}
	
	public function getTotalCount()
	{
		$result = $this->query('SELECT COUNT(id) AS sCount FROM posts;');
		if (!isset($result[0])) return 0;
		return intval($result[0]->sCount);
	}

	public function getNewest($show, $grouped = false){
		if (!$show) return false;

		//see if we have any new shows?
		$where = '';
		if ($grouped) {
			$where = 'GROUP BY LOWER(text)';
		}
		$result = $this->query('SELECT * FROM posts WHERE posts.seen = "0" AND posts.show_id = "'. $show->id .'" '.$where.' ORDER BY date_posted DESC LIMIT 0, 1;');

		if (!isset($result[0])) return false;

		//update this user object with our shit
		$this->id = $result[0]->id;
		$this->addedBy = intval($result[0]->posted_by);
		$this->show = intval($result[0]->show_id);
		$this->text = stripslashes($result[0]->text);
		$this->dateAdded = intval($result[0]->date_posted);
		$this->lat = $result[0]->lat;
		$this->lon = $result[0]->lon;
		$this->seen = intval($result[0]->seen) == 1;
		$us = new User;
		$sh = new Show;
		$this->addedBy = $us->getByID($this->addedBy);
		$this->show = $sh->getByID($this->show);

		$this->markSeen(false);
		return $this;
	}

	public function getAllNewest($show, $grouped = false){
		if (!$show) return false;

		//see if we have any new shows?
		$where = '';
		if ($grouped) {
			$where = 'GROUP BY LOWER(text)';
		}
		$result = $this->query('SELECT * FROM posts WHERE posts.seen = "0" AND posts.show_id = "'. $show->id .'" '.$where.' ORDER BY date_posted ASC;');
		$this->query('UPDATE posts SET posts.seen = "1" WHERE posts.seen = "0" AND posts.show_id = "'. $show->id .'"', false);

		if (!isset($result[0])) return false;

		$showA = array();
		foreach ($result as $res) {
			$showA[] = array(
				'id' => $res->id,
				'text' => stripslashes($res->text)
			);
		}
		return $showA;
	}
}

?>
