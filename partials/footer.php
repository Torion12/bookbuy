<div class="modal fade" id="add-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add <span id="modal-title-add-user-type"></span></h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="add-user-type">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="add_first_name">First Name</label>
              <input type="text" name="add_first_name" id="add_first_name" class="form-control"/>
            </div>

            <div class="form-group">
              <label for="add_last_name">Last Name</label>
              <input type="text" name="add_last_name" id="add_last_name" class="form-control"/>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="add_middle_name">Middle Name</label>
              <input type="text" name="add_middle_name" id="add_middle_name" class="form-control"/>
            </div>
            <div class="form-group">
              <label for="add_email">Email</label>
              <input type="email" name="add_email" id="add_email" class="form-control"/>
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label for="password">Address</label>
              <textarea class="form-control" name="add_address" id="add_address" cols="10" rows="5"></textarea>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="add_contact_number">Contact Number</label>
              <input type="text" name="add_contact_number" class="form-control" id="add_contact_number" autocomplete="off" />
            </div>

            <div class="form-group">
              <label for="add_id_number">ID Number</label>
              <input type="number" name="add_id_number" class="form-control" id="add_id_number" autocomplete="off" />
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="add_password">Password</label>
              <input type="password" name="add_password" class="form-control" id="add_password" autocomplete="off" />
            </div>

            <div class="form-group">
              <label for="add_cpassword">Confirm Password</label>
              <input type="password" name="add_cpassword" class="form-control" id="add_cpassword" autocomplete="off" />
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary add-user-save">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit <span id="modal-title-user-type"></span></h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit-user-id">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="first_name">First Name</label>
              <input type="text" name="first_name" id="first_name" class="form-control"/>
            </div>

            <div class="form-group">
              <label for="last_name">Last Name</label>
              <input type="text" name="last_name" id="last_name" class="form-control"/>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="middle_name">Middle Name</label>
              <input type="text" name="middle_name" id="middle_name" class="form-control"/>
            </div>
            <div class="form-group">
              <label for="name">Email</label>
              <input type="email" name="email" id="email" class="form-control"/>
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label for="password">Address</label>
              <textarea class="form-control" name="address" id="address" cols="10" rows="5"></textarea>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="username">Contact Number</label>
              <input type="text" name="contact_number" class="form-control" id="contact_number" autocomplete="off" />
            </div>

            <div class="form-group">
              <label for="username">ID Number</label>
              <input type="number" name="id_number" class="form-control" id="id_number" autocomplete="off" />
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary edit-user-save">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script src="/node_modules/jquery/dist/jquery.min.js"></script>
<script src="/node_modules/moment/min/moment.min.js"></script>
<script src="/node_modules/toastr/build/toastr.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/print.min.js"></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
  $('.open-add-user-modal').on('click', function() {
    $('#add-user-type').val($(this).data('user-type'))

    $('#modal-title-add-user-type').text($(this).data('user-type').toUpperCase())

    $('#add-user').modal('show')
  })

  $('.add-user-save').on('click', function() {
    if(confirm('Are you user to save new user?')) {
      let user_type = $('#add-user-type').val();
      
      if(!$('#add_id_number').val() || !$('#add_first_name').val()
      || !$('#add_middle_name').val() || !$('#add_last_name').val()
      || !$('#add_email').val() || !$('#add_address').val() || !$('#add_contact_number').val()) {
        toastr.error('Invalid', 'Please input all fields');
        return;
      }

      if(!$('#add_password').val() || !$('#add_cpassword').val()) {
        toastr.error('Invalid', 'Passwords empty');
        return;
      }

      if($('#add_password').val() != $('#add_cpassword').val()) {
        toastr.error('Invalid', 'Passwords doesnt matched');
        return;
      }

      addUser(user_type)
    }
  })

  function currencyFormat(price, sign = '₱') {
    const pieces = parseFloat(price).toFixed(2).split('')
    let ii = pieces.length - 3
    while ((ii -= 3) > 0) {
      pieces.splice(ii, 0, ',')
    }
    return sign + pieces.join('')
  }

  function getUsers(user_type, elem) {
    $.get('./api_users.php?role=' + user_type).then(function(response) {
      response = JSON.parse(response)

      if (response.results.length <= 0) {
        $(elem).html(`
          <tr>
            <td>-----</td>
            <td>-----</td>
            <td>-----</td>
            <td>-----</td>
            <td>-----</td>
            <td>-----</td>
          </tr>
      `)
      } else {
        $(elem).empty();
      }

      response.results.forEach(function(user) {
        $(elem).append(`
            <tr>
              <td>` + user.id_number + `</td>
              <td>` + user.first_name + ' ' + user.last_name + `</td>
              <td>` + user.address + `</td>
              <td>` + (user.contact_number ? user.contact_number : '----') + `</td>
              <td><span class="label label-success">Active</span></td>
              <td class="text-center">
                <button class="btn btn-info edit-user" data-id="` + user.id + `">Edit</button>
                <button class="btn btn-danger delete-user" data-id="` + user.id + `">&times; Delete</button>
              </td>
            </tr>
        `);
      })

      if (response.results.length > 0) {
        $(elem).append(`
          <tr>
            <td>-----</td>
            <td>-----</td>
            <td>-----</td>
            <td>-----</td>
            <td>-----</td>
            <td>-----</td>
          </tr>
      `)
      }
    });
  }

  function openEditUserModal(user_type, user_id) {
    $('#modal-title-user-type').text(user_type.toUpperCase());
    $('#edit-user-id').val(user_id);

    $.get('./api_users.php?id=' + user_id).then(function(response) {
      response = JSON.parse(response)
      console.log(response)
      
      $('#id_number').val(response.user.id_number);
      $('#first_name').val(response.user.first_name);
      $('#middle_name').val(response.user.middle_name);
      $('#last_name').val(response.user.last_name);
      $('#email').val(response.user.email);
      $('#address').val(response.user.address);
      $('#contact_number').val(response.user.contact_number);
    })
    $('#edit-user').modal('show');
  }

  function addUser(user_type) {
    let user = {
      'id_number': $('#add_id_number').val(),
      'first_name': $('#add_first_name').val(),
      'middle_name': $('#add_middle_name').val(),
      'last_name': $('#add_last_name').val(),
      'email': $('#add_email').val(),
      'address': $('#add_address').val(),
      'contact_number': $('#add_contact_number').val(),
      'role': user_type
    }

    $.post('./api_users.php', user).then(function(response) {
      response = JSON.parse(response)
      if(response.error) {
        toastr.error("Error", "Error create user!")
      } else {
        toastr.success("Created!", response.message)
        $('#add-user').modal('hide')

        getUsers(user_type, '#' + user_type + '-list')
      }
    })
  }

  function updateUser(user_type, id) {
    let user = {
      'id_number': $('#id_number').val(),
      'first_name': $('#first_name').val(),
      'middle_name': $('#middle_name').val(),
      'last_name': $('#last_name').val(),
      'email': $('#email').val(),
      'address': $('#address').val(),
      'contact_number': $('#contact_number').val(),
      'role': user_type
    }

    $.post('./api_users.php?id=' + id, user).then(function(response) {
      response = JSON.parse(response)
      if(response.error) {
        toastr.error("Error", "Error updating user!")
      } else {
        toastr.success("Updated!", response.message)
        $('#edit-user').modal('hide')
      }
    })
  }

  function deleteUser(user_type, id) {
    if(confirm('Are you user to delete '+user_type+'?')) {
      $.get('./api_users.php?id=' + id + '&action=delete').then(function(response) {
        response = JSON.parse(response)
        
        if(response.error) {
          toastr.error("Error", "Error deleting user!")
        } else {
          toastr.success("Deleted!", response.message)
          getUsers(user_type, '#' + user_type + '-list')
        }
      })
    }
  }

  <?php if (isset($user) && $user->isLoggedIn()) { ?>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('9793b4a9d2a3567cf558', {
      cluster: 'ap1'
    });

    var channel = pusher.subscribe('<?php echo $user->role() ?>');
    channel.bind('notification', function(data) {
      toastr.info('Notification', data.message)
    });

    $.get('../api_orders.php?status=pending').then(function(response) {
      response = JSON.parse(response)
      $('#pending-orders-count').text(response.results.length || 0)
    })

  <?php } ?>

  $('.notified').on('click', function() {
    console.log($(this).data('id'))


    $.get('../unread.php?id='+$(this).data('id')).then(function() {
      $('.notified').css('background', 'white')
    });
  })
</script>
</body>

</html>