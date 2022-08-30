<?php
$page = 'Bookbuy Add Request Order';
$path = $_SERVER['REQUEST_URI'];
include_once('./partials/header.php');


$instance = DB::getInstance();
$q = $instance->query('SELECT * FROM req_textbooks WHERE req_code IS NOT NULL');
$results = $q->results();

?>
<div class="container-fluid" id="printable">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <ol class="breadcrumb">
                <li><a href="./dashboard.php">Dashboard</a></li>
                <li class="active">Request Orders</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1>Request Orders</h1>
            <hr>
        </div>
        <div class="col-md-10 col-md-offset-1">
            <table class="table table-info table-bordered" id="list-of-orders">
                <tr class="info">
                    <th>Subject/Subject Code</th>
                    <th>Title of Textbook</th>
                    <th>Author</th>
                    <th>Edition</th>
                    <th>Price</th>
                    <th>Book store where available</td>
                    <th>Publisher</td>
                    <th>Number of copies needed</td>
                    <th>Dean</td>
                    <th>Received</td>
                </tr>
                <?php foreach ($results as $result) { ?>
                    <tr style="font-size:20px">
                        <td><?php echo $result->code; ?></td>
                        <td><?php echo $result->textbook_name; ?></td>
                        <td><?php echo $result->author; ?></td>
                        <td><?php echo $result->edition; ?></td>
                        <td><?php echo $result->price; ?></td>
                        <td><?php echo $result->book_store_available; ?></td>
                        <td><?php echo $result->publisher; ?></td>
                        <td><?php echo $result->num_copies; ?></td>
                        <td><?php echo $result->prepared_name; ?></td>
                        <?php if($user->isLoggedIn() && ($user->hasPermission('dean'))) { ?>
                        <td><?php echo $result->is_received == 1 ? 'YES' : 'NO'; ?></td>
                        <?php } else if($user->isLoggedIn() && ($user->hasPermission('staff'))) { ?>
                            <td><button data-id="<?php echo $result->id; ?>"
                                <?php echo $result->is_received == 1 ? 'disabled="true"' : ''; ?>
                                class="btn btn-success mark-received"><?php echo $result->is_received == 1 ? 'RECEIVED' : 'Mark Received'; ?></button></td>
                        <?php } ?>
                    </tr>
                <?php } ?>

            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="add_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <?php echo $_errors ?>
                <form method="POST" action="./request_order.php">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Subject Code/Subject</label>
                                <input type="text" class="form-control" name="subject" placeholder="Subject Code/Subject" required>
                            </div>
                            <div class="form-group">
                                <label for="title">Title of Textbook</label>
                                <input type="text" class="form-control" name="title" placeholder="Title of Textbook" required>
                            </div>
                            <div class="form-group">
                                <label for="author">Author</label>
                                <input type="text" class="form-control" name="author" placeholder="Author">
                            </div>
                            <div class="form-group">
                                <label for="edition">Edition</label>
                                <input type="text" class="form-control" name="edition" placeholder="Edition">
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="number" class="form-control" name="price" placeholder="Price">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="store_available">Book Store Available</label>
                                <input type="text" class="form-control" name="store_available" placeholder="Book Store Available">
                            </div>
                            <div class="form-group">
                                <label for="publisher">Publisher</label>
                                <input type="text" class="form-control" name="publisher" placeholder="Publisher">
                            </div>
                            <div class="form-group">
                                <label for="no_copies_needed">No. of copies needed</label>
                                <input type="number" class="form-control" name="no_copies_needed" placeholder="No. of copies needed" required>
                            </div>
                            <div class="form-group">
                                <label for="prepared_name">Prepared Name</label>
                                <input type="text" class="form-control" name="prepared_name" placeholder="Prepared Name" required>
                            </div>
                        </div>
                    </div>
                
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-secondary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="send_email" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">SEND REQUEST ORDER TO:</div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="supplier_email">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-secondary" id="send">Send</button>
            </div>
        </div>
    </div>
</div>

<?php include_once('./partials/footer.php'); ?>

<script>
    $('#printPage').on('click', function() {
        var newWindow = window.open('./request_order_print.php');
        newWindow.focus();
        newWindow.print();
    });


    $('.mark-received').on('click', function() {
        if(confirm('Confirm request order?')) {
            $(this).attr('disabled', true)
            $(this).text('RECEIVED')
            $.get('./send_req_order.php?id=' + $(this).data('id')).then(function(response) {
                toastr.success('Success!', 'Successfully notified Dean!.')
            });
        }
    })
</script>