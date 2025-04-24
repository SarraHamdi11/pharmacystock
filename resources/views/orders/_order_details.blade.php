<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Order #{{ $order->id }}</h5>
        <small>{{ $order->created_at->format('M d, Y') }}</small>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $orderTotal = 0; @endphp
                    @foreach($order->products as $product)
                        @php
                            $lineTotal = $product->pivot->quantity * $product->pivot->price;
                            $orderTotal += $lineTotal;
                        @endphp
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>${{ number_format($product->pivot->price, 2) }}</td>
                            <td>{{ $product->pivot->quantity }}</td>
                            <td class="text-end">${{ number_format($lineTotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Order Total:</strong></td>
                        <td class="text-end"><strong>${{ number_format($orderTotal, 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>