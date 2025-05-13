@php
    $sms = DB::table('sms')->first();
@endphp

<form action="{{ route('update.sms.settings') }}" method="POST">
    @csrf
    <div class="row">
        <!-- API Key -->
        <div class="col-md-6">
            <label class="form-label">SMS API Key</label>
            <input type="text" class="form-control" name="apikey" value="{{ $sms->apikey ?? '' }}">
        </div>

        <!-- Status -->
        <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="active" {{ ($sms->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ ($sms->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-success mt-3">Save</button>
</form>
