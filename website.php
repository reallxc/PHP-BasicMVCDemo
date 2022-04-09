<?php
	include 'lib/context.php';
	include 'models/user.php';
	$ini_array = parse_ini_file( 'config/config.ini', true);
	$website = $ini_array['website']['content'];
	define('WEBSITE_INI',$ini_array[$website]);
	$exp = time() + 3600;
	setcookie("sitename",WEBSITE_INI['title']);
	
	try {
		$context=Context::createFromConfigurationFile(WEBSITE_INI['dbconfig']);
		$user = new User($context);
		$context->setUser($user);

		$controller=getController($context->getURI());
		$controllerPath='controllers/'.strtolower($controller).'Controller.php';
		$controllerClass=$controller.'Controller';
		if (isset($controllerPath)) {
			require $controllerPath;
		}
	} catch (Exception $ex) {
		echo $ex->getMessage().'<br/>';
		exit;
	}

	try {
		$actor = new $controllerClass($context);
		$actor->process();
	} catch (Exception $ex) {
		echo $ex->getMessage().'<br/>';
	}


	// match the URI to a stub of the controller name
	function getController($uri) {
		$path=$uri->getPart();
		switch ($path) {
			case '':
				$uri->prependPart('home');
				return 'Static';
			case 'home':
				$uri->prependPart('home');
				return 'Static';
			case "leaderboard":
				$uri->prependPart('leaderboard');
				return "Leaderboard";
			case "account":
				$uri->prependPart('account');
				return "Account";
			case "forum":
				$uri->prependPart('forum');
				return "Forum";
			default:
				//throw new Exception ("No such page");
				throw new InvalidRequestException ("No such page");
		}
	}
