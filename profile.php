<?php

require_once 'core/init.php';

if(!$username = Input::get('user')) {
	Redirect::to('index.php');
} else {
	$user = new User($username);
	if(!$user->exists()) {
		Redirect::to(404);
	} else {
		// user exists
		$data = $user->data();
	}
	?>
	
	<h3><?php echo escape($data->id_number); ?></h3>
	<p>Full name: <?php echo escape($data->first_name); ?></p>

	<?php 
}
?>