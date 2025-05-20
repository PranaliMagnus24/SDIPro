@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Qurbani Days</h2>
        </div>
    </div>
</div>
<form action="{{ route('qurbani.days.store')}}" method="POST">
    @csrf
   <div class="row mb-3">
        <div class="col-md-6">
            <label for="" class="form-label"><strong>Day 1<span class="text-danger">*</span></strong></label>
            <input type="datetime-local" name="day_one" value="{{ \Carbon\Carbon::parse($getRecord->day_one)->format('Y-m-d\TH:i') }}" class="form-control">
            @error('day_one')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="" class="form-label"><strong>Day 2<span class="text-danger">*</span></strong></label>
            <input type="datetime-local" name="day_two" value="{{ \Carbon\Carbon::parse($getRecord->day_two)->format('Y-m-d\TH:i') }}" class="form-control">
            @error('day_two')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
    </div>
   <div class="col-md-12 text-center">
        <button type="submit" class="btn btn-primary btn-sm mb-3 mt-2">
            <i class="fa-solid fa-floppy-disk"></i> Submit
        </button>
    </div>

</form>
@endsection
