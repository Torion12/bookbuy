<?php

$page = 'Bookbuy Register';
$path = $_SERVER['REQUEST_URI'];
include_once('./partials/header.php');

$_errors = '';
if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'id_number' => array(
                'required' => true,
                'min' => 8,
                'max' => 20,
                'unique' => 'users'
            ),
            'password' => array(
                'required' => true,    
                'min' => 6
            ),
            'password_again' => array(
                'required' => true,
                'matches' => 'password'
            ),
            'first_name' => array(
                'required' => true,
                'min' => 4,
                'max' => 50
            ),
            'middle_name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            ), 'last_name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            ),
            'email' => array(
                'required' => true,
                'min' => 5,
                'max' => 150
            )
        ));
        
        if($validation->passed()) {
            // echo 'Passed';
            $user = new User();

            try {
                $user->create(array(
                    'id_number'	    => Input::get('id_number'),
                    'password'	    => password_hash(Input::get('password'), PASSWORD_DEFAULT),
                    'first_name'    => Input::get('first_name'),
                    'last_name'     => Input::get('last_name'),
                    'middle_name'   => Input::get('middle_name'),
                    'email'         => Input::get('email'),
                    'address'       => Input::get('address'),
                    'created_at'	=> date('Y-m-d H:i:s'),
                    'role_id'       => 4
                ));

                Redirect::to('index.php');
            } catch(Exception $e) {
                die($e->getMessage());
            }
        } else {
            foreach($validation->errors() as $error) {
                $_errors .= '<div class="alert alert-warning">' . ucfirst($error) . '</div>';
            }
        }
    } else {
       $_errors .= '<div class="alert alert-warning">Invalid token. Please refresh the page and try again.</div>';
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
   </style>

<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-info">
				<div class="panel-heading">
					<div class="panel-title">Register</div>
				</div>
				<div class="panel-body">
					<?php echo !empty($_errors) ? $_errors : '' ?>
                    <form action="" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo escape(Input::get('first_name')); ?>" />
                                </div>

                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo escape(Input::get('last_name')); ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" name="middle_name" id="middle_name" class="form-control" value="<?php echo escape(Input::get('middle_name')); ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="name">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" value="<?php echo escape(Input::get('email')); ?>" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="password">Address</label>
                                    <textarea class="form-control" name="address" id="" cols="10" rows="5"></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">ID Number</label>
                                    <input type="number" name="id_number" class="form-control" id="id_number" value="<?php echo escape(Input::get('id_number')); ?>" autocomplete="off" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Choose a password</label>
                                    <input type="password" id="password" class="form-control" name="password" />
                                </div>
                                
                                <div class="form-group">
                                    <label for="password_again">Enter your password again</label>
                                    <input type="password" id="password_again" class="form-control" name="password_again" />
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                        <input type="submit" value="Register" class="btn btn-info" />
                        
                    </form>
				</div>
			</div>
		</div>
	</div>
</div>