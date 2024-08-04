<?php
$page_title = 'All Customers';
require_once('includes/load.php');
// Checking user permission level to view this page
page_require_level(3);
$customers = join_customer_table();
include_once('layouts/header.php');
?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="pull-left">
          <input type="text" id="search_bar" class="form-control" placeholder="Search by Phone Number" style="width: 300px; display: inline-block;">
        </div>
        <div class="pull-right">
          <a href="add_customer.php" class="btn btn-primary">Add New</a>
        </div>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center">Phone Number</th>
              <th class="text-center">Customer Name</th>
              <th class="text-center">Email</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody id="customer_table_body">
            <?php foreach ($customers as $customer): ?>
              <tr>
                <td class="text-center"><?php echo remove_junk($customer['phone_number']); ?></td>
                <td class="text-center"><?php echo remove_junk($customer['customer_name']); ?></td>
                <td class="text-center"><?php echo remove_junk($customer['email']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_customer.php?phone_number=<?php echo urlencode(remove_junk($customer['phone_number'])); ?>" class="btn btn-warning btn-xs" title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_customer.php?phone_number=<?php echo urlencode(remove_junk($customer['phone_number'])); ?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>

<script>
$(document).ready(function(){
    $('#search_bar').on('input', function(){
        var phoneNumber = $(this).val();
        
        $.ajax({
            url: 'ajax2.php',
            method: 'POST',
            data: { 
                phone_number: phoneNumber, // Send phone number for searching
                action: 'find'
            },
            success: function(response) {
                $('#customer_table_body').html(response); // Update table body with response
            }
        });
    });
});
</script>
