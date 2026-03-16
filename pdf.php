<?php
require('fpdf/fpdf/fpdf.php'); // Make sure this path is correct
include_once('includes/config.php');

// Get product ID from URL
if (isset($_GET['pid']) && is_numeric($_GET['pid'])) {
    $pid = intval($_GET['pid']);

    // Fetch full product info
    $query = mysqli_query($con, "SELECT productName, category, productDescription, productInstruction FROM tblproducts WHERE ID = $pid");
    $row = mysqli_fetch_assoc($query);

    if ($row) {
        $productName = $row['productName'];
        $description = $row['productDescription'];
        $instruction = $row['productInstruction'];
        $categoryId = $row['category'];

        // Convert category ID to category name
        switch ($categoryId) {
            case 1:
                $categoryName = "Plant";
                break;
            case 2:
                $categoryName = "Flower";
                break;
            case 3:
                $categoryName = "Seeds";
                break;
            case 6:
                $categoryName = "Sugarcane";
                break;
            case 7:
                $categoryName = "Vegetable";
                break;
            default:
                $categoryName = "Unknown";
        }

        // Create PDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Header
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Plant Instruction Guide', 0, 1, 'C');

        // Product Name
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Product: ' . $productName, 0, 1);

        // Category Name
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Category: ' . $categoryName, 0, 1);

        // Description
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Description:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 10, $description);

        // Instruction
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Instruction:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 10, $instruction);

        // Output PDF
        $pdf->Output('D', $productName . '_Instructions.pdf');
        exit();
    } else {
        echo "Product not found.";
    }
} else {
    echo "Invalid product ID.";
}
?>
