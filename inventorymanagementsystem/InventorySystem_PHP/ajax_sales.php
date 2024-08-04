<?php
require_once('includes/load.php');

// Function to handle sales search by phone number
if (isset($_POST['phone_number']) && isset($_POST['action']) && $_POST['action'] == 'find_sale') {
    $phone_number = $db->escape($_POST['phone_number']);
    $sql  = "SELECT s.id, s.phone_number, s.quantity, s.price, s.total, s.date, s.location, s.building_type,
                    p.name AS product_name
             FROM sales s 
             LEFT JOIN products p ON s.product_id = p.id
             WHERE s.phone_number LIKE '%$phone_number%'";
    
    $result = $db->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $sales = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($sales);
    } else {
        echo json_encode(array('message' => 'No sales found for this phone number'));
    }
    
    exit;
}

// If no valid action or incorrect data is posted, return an empty response
echo json_encode(array('message' => 'Invalid request'));
?>
