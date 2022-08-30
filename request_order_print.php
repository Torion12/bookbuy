<?php
$page = 'Bookbuy Add Request Order';
$path = $_SERVER['REQUEST_URI'];
include_once('./partials/header.php');
?>
<div class="container-fluid" id="printable">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1>Request Order</h1>
            <hr>
        </div>
        <div class="col-md-10 col-md-offset-1">
            <table class="table table-info table-bordered">
                <tr class="info">
                    <th>Subject/Subject Code</th>
                    <th>Title of Textbook</th>
                    <th>Author</th>
                    <th>Edition</th>
                    <th>Price</th>
                    <th>Book store where available</td>
                    <th>Publisher</td>
                    <th>Number of copies needed</td>
                </tr>
                <tr style="font-size:20px">
                    <td>PE 1</td>
                    <td>Fundamentals of Programming I</td>
                    <td>Neil John Diola</td>
                    <td>---</td>
                    <td>---</td>
                    <td>---</td>
                    <td>---</td>
                    <td>1,000</td>
                </tr>
            </table>
        </div>
        <div class="col-md-10 col-md-offset-1" style="margin-top: 50px;">
            <table>
                <tr>
                    <td>Prepared By: </td>
                    <td class="text-center">&nbsp;<b>Ms. Moma Ortega</b></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-center">&nbsp;Dean/Chairman</td>
                </tr>
            </table>
        </div>
    </div>
</div>
<?php include_once('./partials/footer.php'); ?>