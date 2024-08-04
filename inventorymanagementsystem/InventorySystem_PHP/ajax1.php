<?php
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) { redirect('index.php', false); }

// Auto suggestion for product search
$html = '';
if (isset($_POST['product_name']) && strlen($_POST['product_name'])) {
    $products = find_product_by_title($_POST['product_name']);
    if ($products) {
        foreach ($products as $product):
            $html .= "<li class=\"list-group-item suggestion-item\" onclick=\"fillProduct('{$product['id']}', '{$product['name']}', '{$product['sale_price']}');\">";
            $html .= $product['name'];
            $html .= "</li>";
        endforeach;
    } else {
        $html .= '<li class="list-group-item">Not found</li>';
    }
    echo json_encode($html);
}
?>
