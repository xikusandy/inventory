<?php
$page_title = 'Manage Sales';
require_once('includes/load.php');
// Checking user permission level to view this page
page_require_level(2);

// Function to fetch sales data without customer name
function join_sales_table() {
    global $db;
    $sql  = "SELECT s.id, s.phone_number, s.quantity, s.price, s.total, s.date, s.location, s.building_type, ";
    $sql .= "p.name AS product_name ";
    $sql .= "FROM sales s ";
    $sql .= "LEFT JOIN products p ON s.product_id = p.id ";
    $result = $db->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch sales data
$sales = join_sales_table();

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
          <a href="add_sale.php" class="btn btn-primary">Add Sale</a>
        </div>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center">Phone Number</th>
              <th class="text-center">Product Name</th>
              <th class="text-center">Quantity</th>
              <th class="text-center">Price</th>
              <th class="text-center">Total</th>
              <th class="text-center">Date</th>
              <th class="text-center">Location</th>
              <th class="text-center">Building Type</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody id="sales_table_body">
            <?php foreach ($sales as $sale): ?>
              <tr>
                <td class="text-center"><?php echo remove_junk($sale['phone_number']); ?></td>
                <td class="text-center"><?php echo remove_junk($sale['product_name']); ?></td>
                <td class="text-center"><?php echo remove_junk($sale['quantity']); ?></td>
                <td class="text-center"><?php echo remove_junk($sale['price']); ?></td>
                <td class="text-center"><?php echo remove_junk($sale['total']); ?></td>
                <td class="text-center"><?php echo remove_junk($sale['date']); ?></td>
                <td class="text-center"><?php echo remove_junk($sale['location']); ?></td>
                <td class="text-center"><?php echo remove_junk($sale['building_type']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_sale.php?id=<?php echo urlencode(remove_junk($sale['id'])); ?>" class="btn btn-warning btn-xs" title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_sale.php?id=<?php echo urlencode(remove_junk($sale['id'])); ?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
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
            url: 'ajax_sales.php',
            method: 'POST',
            data: { 
                phone_number: phoneNumber, // Send phone number for searching
                action: 'find_sale'
            },
            dataType: 'json', // Expect JSON response
            success: function(response) {
                var html = '';
                if (response && response.length > 0) {
                    $.each(response, function(index, sale) {
                        html += '<tr>';
                        html += '<td class="text-center">' + sale.phone_number + '</td>';
                        html += '<td class="text-center">' + sale.product_name + '</td>';
                        html += '<td class="text-center">' + sale.quantity + '</td>';
                        html += '<td class="text-center">' + sale.price + '</td>';
                        html += '<td class="text-center">' + sale.total + '</td>';
                        html += '<td class="text-center">' + sale.date + '</td>';
                        html += '<td class="text-center">' + sale.location + '</td>';
                        html += '<td class="text-center">' + sale.building_type + '</td>';
                        html += '<td class="text-center">';
                        html += '<div class="btn-group">';
                        html += '<a href="edit_sale.php?id=' + encodeURIComponent(sale.id) + '" class="btn btn-warning btn-xs" title="Edit" data-toggle="tooltip">';
                        html += '<span class="glyphicon glyphicon-edit"></span>';
                        html += '</a>';
                        html += '<a href="delete_sale.php?id=' + encodeURIComponent(sale.id) + '" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">';
                        html += '<span class="glyphicon glyphicon-trash"></span>';
                        html += '</a>';
                        html += '</div>';
                        html += '</td>';
                        html += '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="9" class="text-center">No sales found for this phone number</td></tr>';
                }
                $('#sales_table_body').html(html); // Update table body with generated HTML
            }
        });
    });
});
</script>

