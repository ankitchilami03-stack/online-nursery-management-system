<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    var options = {
        "key": "YOUR_RAZORPAY_KEY",
        "amount": 50000, // amount in paise
        "currency": "INR",
        "name": "Plant Nursery",
        "description": "Order Payment",
        "handler": function (response) {
            // On success, redirect
            window.location.href = "payment-success.php?payment_id=" + response.razorpay_payment_id + "&order_id=" + orderId;
        },
        "prefill": {
            "email": "<?php echo $_SESSION['email']; ?>",
            "contact": "<?php echo $_SESSION['phone']; ?>"
        },
        "theme": {
            "color": "#3399cc"
        }
    };
    var rzp1 = new Razorpay(options);
    rzp1.open();
</script>
