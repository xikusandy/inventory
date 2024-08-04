<?php
$page_title = 'Add product returned';
require_once('includes/load.php');
require_once('includes/sql.php'); // Include the file with function definitions

// Check user permissions
page_require_level(3);

// Handle form submission
if(isset($_POST['add_products_returned'])){
    // Required fields
    $req_fields = array('sales_id', 'product_id', 'phone_number', 'quantity', 'date');
    validate_fields($req_fields);

    if(empty($errors)){
        // Escape and sanitize the input data
        $sales_id     = (int)$_POST['sales_id'];
        $product_id   = (int)$_POST['product_id'];
        $phone_number = $_POST['phone_number'];
        $quantity     = (int)$_POST['quantity'];
        $date         = $_POST['date'];

        $result = add_product_returned($sales_id, $product_id, $phone_number, $quantity, $date);
        echo json_encode($result);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => $errors]);
        exit;
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Add Return</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="returned.php" id="return-form">
                    <input type="hidden" name="add_products_returned" value="1">
                    <div class="form-group">
                        <label for="sales_id">Sales ID:</label>
                        <input type="text" name="sales_id" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="product_id">Product ID:</label>
                        <input type="text" name="product_id" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number:</label>
                        <input type="text" name="phone_number" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="text" name="quantity" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('return-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the form from submitting normally
    
    var formData = new FormData(this);
    
    fetch('returned.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Product returned successfully.');
            window.location.href = 'returned.php'; // Redirect to the returned.php page
        } else {
            alert('Failed to add product return. Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing your request.');
    });
});
</script>
<?php include_once('layouts/footer.php'); ?>
