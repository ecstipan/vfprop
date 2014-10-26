<?php

class Main extends Controller {
	
	function index()
	{
		$this->loadPlugin('Auth');
		//see if we have edit rights
		
		$loggedIn = authIsLoggedIn();
		if ($loggedIn && authGetUser()->isAdmin()) {
			if (authIsBanned()) $this->redirect('login/banned');
			$this->redirect('admin/s/'.getHash());
		} else if ($loggedIn) {
			if (authIsBanned()) $this->redirect('login/banned');
			$this->redirect('live');
		} else {
			$this->redirect('login');
		}
	}
    
}

?>
