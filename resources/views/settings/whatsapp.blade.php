@php
    $whatsapp = DB::table('whatsapp')->first();
@endphp

<form action="{{ route('update.whatsapp.settings') }}" method="POST">
    @csrf
    <div class="row">
        <!-- API Key -->
        <div class="col-md-6">
            <label class="form-label">WhatsApp API Key</label>
            <input type="text" class="form-control" name="apikey" value="{{ $whatsapp->apikey ?? '' }}">
        </div>

        <!-- Status -->
        <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="active" {{ ($whatsapp->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ ($whatsapp->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-success mt-3">Save</button>
</form>
