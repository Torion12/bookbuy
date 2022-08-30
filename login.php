<?php

$page = 'Bookbuy Login';
$path = $_SERVER['REQUEST_URI'];
include_once('./partials/header.php');

$_errors = '';
if(Input::exists()) {
	if(Token::check(Input::get('token'))) {

		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'id_number' => array('required' => true),
			'password' => array('required' => true)
		));

		if($validation->passed()) {
			// Login user
			$user = new User();

			$remember = (Input::get('remember') === 'on') ? true : false;
			$login = $user->login(Input::get('id_number'), Input::get('password'), $remember);

			if($login) {
				if($user->hasPermission('student')) {
					Redirect::to('index.php');
				} else {
					Redirect::to('dashboard.php');
				}
			} else {
				$_errors = '<div class="alert alert-warning">Wrong Username/password</div>';
			}

		} else {
			foreach($validation->errors() as $error) {
				 $_errors .= '<div class="alert alert-warning">' . ucfirst($error) . '</div>';
			}
		}

	}
}
?>
<style>
     body {
      background-image:url("assets/img/unsplash.jpg");
      background-repeat:no-repeat;
      background-position: center;
      background-size: cover;
     }
	 
	 p{
		 color: #fff;
	 }
   </style>
	<center><img id="logo" src="./assets/img/logo.jpg"><p>BOOK<b>BUY</b></p></center>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="panel panel-info">
				<div class="panel-heading">
					<div class="panel-title">Login</div>
				</div>
				<div class="panel-body">
					<?php echo !empty($_errors) ? $_errors : '' ?>

					<form action="" method="post">
						<div class="form-group">
							<label for="username">Email/ID Number</label>
							<input type="text" name="id_number" class="form-control" id="id_number" autocomplete="off" value="<?php echo Input::get('id_number') ?? ''; ?>" />
						</div>
						<div class="form-group">
							<label for="password">Password</label>
							<input type="password" name="password" class="form-control"  autocomplete="off" />
						</div>
						<div class="form-group">
							<label for="remember">
								<input type="checkbox" name="remember" id="remember" />
								Remember me
							</label>
						</div>
						<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
						<input type="submit" value="login" class="btn btn-info"/>
						<a href="register.php" role="button" class="btn btn-success">Register</a>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include_once('./partials/footer.php') ?>