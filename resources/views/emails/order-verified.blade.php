<!DOCTYPE html>
<html>
<head>
    <title>Prescription Verified</title>
</head>
<body>
    <div style="font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #1a5276;">💊 PharmLocate</h2>
        <h4 style="color: #e67e22;">Prescription Verified</h4>
        <p>Hello <strong>{{ $order->customer->name }}</strong>,</p>
        <p>Your prescription for <strong>{{ $order->items->first()->medicine->name ?? 'N/A' }}</strong> has been successfully verified by the pharmacy.</p>
        <p>Your order status is now: <strong style="color: #2980b9;">Processing</strong>.</p>
        <p>We will dispatch your order soon. You can track the status in your dashboard.</p>
        <br>
        <p style="color: #888; font-size: 13px;">Thank you for choosing PharmLocate!</p>
    </div>
</body>
</html>