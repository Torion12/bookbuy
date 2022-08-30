<?php 

$page = 'Bookbuy Home';
$path = $_SERVER['REQUEST_URI'];
include_once('./partials/header.php');
$user = new User();
$db = DB::getInstance();

$books = $db->query('SELECT * FROM textbooks WHERE textbook_status = "active" ORDER BY id LIMIT 3');

?>
<style>
     body {
      background-image:url("assets/img/unsplash.jpg");
      background-repeat:no-repeat;
      background-position: center;
      background-size: cover;
     }
     .thumbnail{
        background-color: transparent;
     }
      h3 ,p {
      color: white;
     }
   </style>
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="row">
            <?php foreach($books->results() as $book) { ?>
            <div class="col-sm-8 col-md-4">
               <div class="thumbnail">
                  <img src="<?php echo $book->textbook_img; ?>" width="200" height="200"alt="Sample book">
                  <div class="caption">
                  <h3><?php echo $book->textbook_name; ?></h3>
                  <p><?php echo $book->textbook_desc; ?><br /> <b>₱ <?php echo $book->textbook_price; ?></b></p>
                  <?php if($user->isLoggedIn() && $user->hasPermission('student')) { ?>
                  <p><a href="add_order.php?add=<?php echo $book->id ?>" class="btn btn-primary" role="button">Buy</a></p>
                  <?php } ?>
                  </div>
               </div>
            </div>
            <?php } ?>
         </div>
      </div>
      <div class="col-md-12">
         <a href="./add_order.php" class="btn btn-success pull-right">Show Cart &gt;</a>
      </div>
   </div>
</div>

<?php include_once('./partials/footer.php') ?>