
        @php
            $payment = DB::table('payment')->first();
        @endphp

        <form action="{{ route('update.payment.settings') }}" method="POST">
            @csrf
            <div class="row">
                <!-- API Key -->
                <div class="col-md-6">
                    <label class="form-label">API Key</label>
                    <input type="text" class="form-control" name="apikey" value="{{ $payment->apikey ?? '' }}">
                </div>

                <!-- Secret Key -->
                <div class="col-md-6">
                    <label class="form-label">Secret Key</label>
                    <input type="text" class="form-control" name="secretkey" value="{{ $payment->secretkey ?? '' }}">
                </div>
            </div>

            <div class="row mt-3">
                <!-- Payment Option -->
                <div class="col-md-6">
                    <label class="form-label">Payment Option</label>
                    <select name="payment_option" class="form-control">
                        @php
                            $gateways = ['paypal', 'stripe', 'paytm', 'razorpay', 'instamojo', 'paystack', 'flutterwave', 'mobilepayme'];
                        @endphp
                        @foreach ($gateways as $gateway)
                            <option value="{{ $gateway }}" {{ ($payment->payment_option ?? 'razorpay') == $gateway ? 'selected' : '' }}>
                                {{ ucfirst($gateway) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="active" {{ ($payment->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ ($payment->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-success mt-3">Save</button>
        </form>
  