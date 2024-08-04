<?php
$page_title = 'Edit Sale';
require_once('includes/load.php');
// Check user permission level to view this page
page_require_level(3);

// Fetch the sale data based on the ID from the GET parameter
$sale = find_by_id('sales', (int)$_GET['id']);
if (!$sale) {
    $session->msg("d", "Sale not found.");
    redirect('sales.php');
}

// Fetch product information related to the sale
$product = find_by_id('products', $sale['product_id']);

// Process form submission for updating the sale
if (isset($_POST['update_sale'])) {
    $req_fields = array('title', 'quantity', 'price', 'total', 'date', 'location', 'building_type');
    validate_fields($req_fields);

    if (empty($errors)) {
        $p_id         = $db->escape((int)$product['id']);
        $s_qty        = $db->escape((int)$_POST['quantity']);
        $s_total      = $db->escape($_POST['total']);
        $date         = $db->escape($_POST['date']);
        $s_date       = date("Y-m-d", strtotime($date));
        $location     = $db->escape($_POST['location']);
        $building_type= $db->escape($_POST['building_type']);

        $sql  = "UPDATE sales SET ";
        $sql .= "product_id = '{$p_id}', ";
        $sql .= "phone_number = '{$sale['phone_number']}', "; // Retaining original phone_number
        $sql .= "quantity = {$s_qty}, ";
        $sql .= "price = '{$s_total}', ";
        $sql .= "location = '{$location}', ";
        $sql .= "date = '{$s_date}', ";
        $sql .= "building_type = '{$building_type}', ";
        $sql .= "total = '{$s_total}' ";
        $sql .= "WHERE id = '{$sale['id']}'";
        $result = $db->query($sql);

        if ($result && $db->affected_rows() === 1) {
            update_product_qty($s_qty, $p_id); // Update product quantity
            $session->msg('s', "Sale updated successfully.");
            redirect('edit_sale.php?id=' . $sale['id'], false);
        } else {
            $session->msg('d', 'Failed to update sale.');
            redirect('sales.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_sale.php?id=' . (int)$sale['id'], false);
    }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-6">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Edit Sale</span>
                </strong>
                <div class="pull-right">
                    <a href="sales.php" class="btn btn-primary">Show All Sales</a>
                </div>
            </div>
            <div class="panel-body">
                <form method="post" action="edit_sale.php?id=<?php echo (int)$sale['id']; ?>">
                    <table class="table table-bordered">
                        <thead>
                            <th>Product Title</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Building Type</th>
                            <th>Action</th>
                        </thead>
                        <tbody id="product_info">
                            <tr>
                                <td id="s_name">
                                    <input type="text" class="form-control" id="sug_input" name="title" value="<?php echo remove_junk($product['name']); ?>">
                                    <div id="result" class="list-group"></div>
                                </td>
                                <td id="s_qty">
                                    <input type="text" class="form-control" name="quantity" value="<?php echo (int)$sale['quantity']; ?>">
                                </td>
                                <td id="s_price">
                                    <input type="text" class="form-control" name="price" value="<?php echo remove_junk($sale['price']); ?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="total" value="<?php echo remove_junk($sale['total']); ?>">
                                </td>
                                <td id="s_date">
                                    <input type="date" class="form-control datepicker" name="date" value="<?php echo remove_junk($sale['date']); ?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="location" value="<?php echo remove_junk($sale['location']); ?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="building_type" value="<?php echo remove_junk($sale['building_type']); ?>">
                                </td>
                                <td>
                                    <button type="submit" name="update_sale" class="btn btn-primary">Update Sale</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
