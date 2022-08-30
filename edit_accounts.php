<?php

$page = 'Bookbuy Edit Acounts';
$path = $_SERVER['REQUEST_URI'];
include_once('./partials/header.php');

$_errors = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
        'id_number' => array(
            'required' => true,
            'min' => 2,
            'max' => 20
        ),
        'password' => array(
            'required' => true,    
            'min' => 6
        ),
        'first_name' => array(
            'required' => true,
            'min' => 2,
            'max' => 50
        ),
        'middle_name' => array(
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
        $user = new User();

        try {
            $user->update(array(
                'id_number'	    => Input::get('id_number'),
                'password'	    => password_hash(Input::get('password'), PASSWORD_DEFAULT),
                'first_name'    => Input::get('first_name'),
                'last_name'     => Input::get('last_name'),
                'middle_name'   => Input::get('middle_name'),
                'email'         => Input::get('email'),
                'address'       => Input::get('address'),
                'created_at'	=> date('Y-m-d H:i:s'),
            ));

            $_errors = '<div class="alert alert-success">Update Success</div>';
        } catch(Exception $e) {
            die($e->getMessage());
        }
    } else {
        foreach($validation->errors() as $error) {
            $_errors .= '<div class="alert alert-warning">' . ucfirst($error) . '</div>';
        }
    }
}


?>
<style>

</style>
<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-info">
				<div class="panel-heading">
					<div class="panel-title">Personal Information</div>
				</div>
				<div class="panel-body">
					<?php echo !empty($_errors) ? $_errors : '' ?>
                    <form action="./edit_accounts.php" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo $user->data()->first_name; ?>" />
                                </div>

                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo $user->data()->last_name; ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" name="middle_name" id="middle_name" class="form-control" value="<?php echo $user->data()->middle_name; ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="name">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" value="<?php echo $user->data()->email; ?>" />
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
                                    <input type="number" name="id_number" class="form-control" id="id_number" value="<?php echo $user->data()->id_number; ?>" autocomplete="off" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" class="form-control" id="password" autocomplete="off" />
                                </div>
                            </div>

                            
                        </div>
                        <input type="submit" value="Update" class="btn btn-success" />
                        
                    </form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include_once('./partials/footer.php') ?>