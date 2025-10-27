<div class="modal-header">
    <div>
        <h5 class="modal-title pt-3 mb-3">Order Details</h5>
    </div>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <!-- Customer Details -->
    {{-- <div class="customer-details mb-4">
        <h6>Customer Information</h6>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Name:</strong> {{ $order['customer']['name'] ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $order['customer']['email'] ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Phone:</strong> {{ $order['customer']['phone'] ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Payment Details -->
    <div class="payment-details mb-4">
        <h6>Payment Information</h6>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Transaction No:</strong> {{ $order['payment']['transaction_no'] ?? 'N/A' }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst($order['payment']['payment_method'] ?? 'N/A') }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Payment Status:</strong> {{ ucfirst($order['payment']['payment_status'] ?? 'N/A') }}</p>
                <p><strong>Payment Amount:</strong> {{ number_format($order['payment']['payment_amount'] ?? 0, 2) }} TK</p>
            </div>
        </div>
    </div> --}}


    <div class="row">
        <!-- Customer Details -->
        <div class="col-md-6">
            <div class="customer-details mb-2">
                <h6 class="mb-3">Customer Information</h6>
                <p class="mb-1"><strong>Name:</strong> {{ $order['customer']['name'] ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $order['customer']['email'] ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $order['customer']['phone'] ?? 'N/A' }}</p>
            </div>
        </div>
    
        <!-- Payment Details -->
        <div class="col-md-6">
            <div class="payment-details mb-2">
                <h6 class="mb-3">Payment Information</h6>
                <p class="mb-1"><strong>Transaction No:</strong> {{ $order['payment']['transaction_no'] ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Payment Method:</strong> {{ ucfirst($order['payment']['payment_method'] ?? 'N/A') }}</p>
                <p class="mb-1"><strong>Payment Status:</strong> {{ ucfirst($order['payment']['payment_status'] ?? 'N/A') }}</p>
                <p class="mb-1"><strong>Payment Amount:</strong> {{ number_format($order['payment']['payment_amount'] ?? 0, 2) }} TK</p>
            </div>
        </div>
    </div>


    <div class="row">
        <!-- Customer Details -->
        <div class="col-md-6">
            <div class="customer-details mb-2">
                <h6 class="mb-3">Order Information</h6>
                <p class="mb-1" ><strong>Order No:</strong> {{ $order['order_no'] ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Total Amount:</strong> {{ number_format($order['total'] ?? 0, 2) }} TK</p>
                <p class="mb-1"><strong>Delivery Charge:</strong> {{ $order['delivery_charge'] ?? 'N/A' }} TK</p>
                <p class="mb-1"><strong>Status:</strong> {{ ucfirst($order['status'] ?? 'N/A') }}</p>
                <p class="mb-1"><strong>Location:</strong> {{ $order['location'] ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
    


    <div>

        <div class="mt-3 text-right">
            
            {{-- <a href="" class="btn btn-secondary">
                Download ZIP
            </a> --}}
            <a href="{{ route('download.zip', ['order_id' => $order['id']]) }}" class="btn btn-secondary">
                Download ZIP
            </a>
        </div>

        <table class="table table-bordered table-striped mt-3">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Album</th>
                    <th>Frame</th>
                    <th>Size</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($order['orderDetails']))
                    @foreach ($order['orderDetails'] as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if (!empty($detail['documents']))
                                        @foreach ($detail['documents'] as $doc)
                                            <a href="{{ asset($doc['file_name'])}}" target="_blank">
                                                <img src="{{ url($doc['file_name']) }}" 
                                                    alt="Document Image" 
                                                    class="img-fluid" 
                                                    style="max-width: 100px;">
                                            </a>
                                        @endforeach
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ number_format($detail['price'] ?? 0, 2) }} TK</td>
                            <td>
                                @if (!empty($detail['album']['image']))
                                    <img src="{{ url($detail['album']['image']) }}" 
                                         alt="Album Image" 
                                         class="img-fluid" 
                                         style="max-width: 100px;">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if (!empty($detail['frame']['image']))
                                    <img src="{{ url($detail['frame']['image']) }}" 
                                         alt="Frame Image" 
                                         class="img-fluid" 
                                         style="max-width: 100px;">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                {{ $detail['size']['printType']['name'] ?? 'N/A' }} <br>
                                {{ $detail['size']['name'] ?? 'N/A' }} <br>
                                {{ $detail['size']['dimention'] ?? 'N/A' }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">No order details available</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
