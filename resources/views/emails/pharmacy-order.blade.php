<div style="font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #1a5276;">💊 PharmLocate</h2>
    <h3 style="color: #e67e22;">New Order Received!</h3>
    <p>Hello <strong>{{ $order->pharmacy->name }}</strong>,</p>
    <p>You have received a new order from <strong>{{ $order->customer->name }}</strong>.</p>
    
    <div style="background: #f4f7fb; padding: 15px; border-radius: 8px;">
        <p style="font-weight: bold; color: #1a5276;">Order Details</p>
        <table style="width: 100%;">
            <tr><td style="padding: 5px 0; color: #888;">Customer</td><td style="font-weight: bold;">{{ $order->customer->name }}</td></tr>
            <tr><td style="padding: 5px 0; color: #888;">Medicine</td><td style="font-weight: bold;">{{ $order->items->first()->medicine->name }}</td></tr>
            <tr><td style="padding: 5px 0; color: #888;">Quantity</td><td style="font-weight: bold;">{{ $order->items->first()->quantity }}</td></tr>
            <tr><td style="padding: 5px 0; color: #888;">Total Amount</td><td style="font-weight: bold; color: #2980b9;">LKR {{ number_format($order->total_amount, 2) }}</td></tr>
        </table>
    </div>
    <p style="color: #888; font-size: 13px;">Log in to your dashboard to process this order.</p>
</div>