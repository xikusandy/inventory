<?php
$page_title = 'Edit Customer';
require_once('includes/load.php');
// Check user's permission level to view this page
page_require_level(2); // Adjust permission level as needed

// Retrieve customer details based on the phone number from URL parameter
$phone_number = $db->escape($_GET['phone_number']);
$customer = find_by_column('customers', 'phone_number', $phone_number);

// If customer not found, redirect to customers list page
if (!$customer) {
    $session->msg("d", "Customer not found.");
    redirect('add_customer.php');
}

if (isset($_POST['update_customer'])) {
    $req_fields = array('customer_name', 'email');
    validate_fields($req_fields);

    if (empty($errors)) {
        $customer_name = $db->escape($_POST['customer_name']);
        $email = $db->escape($_POST['email']);

        // Update customer details in the database
        $query = "UPDATE customers SET";
        $query .= " customer_name='{$customer_name}', email='{$email}'";
        $query .= " WHERE phone_number='{$phone_number}'";
        
        $result = $db->query($query);
        
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', 'Customer details updated.');
            redirect("edit_customer.php?phone_number={$phone_number}", false);
        } else {
            $session->msg('d', 'Failed to update customer details.');
            redirect('edit_customer.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect("edit_customer.php?phone_number={$phone_number}", false);
    }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-user"></span>
                    Edit Customer: <?php echo remove_junk($customer['customer_name']); ?>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="edit_customer.php?phone_number=<?php echo urlencode($phone_number); ?>">
                    <div class="form-group">
                        <label for="customer_name">Customer Name</label>
                        <input type="text" class="form-control" name="customer_name" value="<?php echo remove_junk($customer['customer_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo remove_junk($customer['email']); ?>" required>
                    </div>
                    <button type="submit" name="update_customer" class="btn btn-primary">Update Customer</button>
                    <a href="customers.php" class="btn btn-default">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
