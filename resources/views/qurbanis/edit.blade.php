@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Qurbani</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="{{ route('qurbanis.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('qurbanis.update',$qurbani->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                <input type="text" name="contact_name" class="form-control" value="{{ $qurbani->contact_name }}" placeholder="Name" required>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Mobile:</strong>
                <input type="number" name="mobile" maxlength="10" value="{{ $qurbani->mobile }}"class="form-control" placeholder="mobile" required>
            </div>
        </div>

        <div class="container pt-4">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        
                        <th class="text-center">
                              Name
                          </th>
                        <th class="text-center">
                              Aqiqah
                          </th>
                          <th class="text-center">
                              Remove
                          </th>
                    </tr>
                </thead>
                <tbody id="tbody">
                        @foreach ($qurbanihisse as $hisse)
                            
                        
                            <tr class="rowClass"> 
                                <td class="row-index text-center"> 
                                    <input type="text" name="name[1]" class="form-control hisse" placeholder="Name" value="{{$hisse->name}}" required> 
                                </td>
                                <td class="row-index text-center"> 
                                    <input type="checkbox" name="aqiqah[1]" placeholder="aqiqah"  value="1" @if ($hisse->aqiqah==1)
                                        checked="checked"
                                    @endif> 
                                   
                                </td>  
                                <td class="text-center"> 
                                    <button class="btn btn-danger remove"
                                        type="button">Remove
                                    </button> 
                                </td> 
                        </tr>
                        @endforeach    
                </tbody>
            </table>
        </div>
        <button class="btn btn-md btn-primary"
                id="addBtn" type="button">
            Add New Row
        </button>
    </div>  

        <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6 hide">
                        <div class="form-group">
                            <strong>Total Hisse :</strong>
                            <span id="txthisse">{{ count($qurbanihisse) }}</span>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-6 hide">
                        <div class="form-group">
                            <strong>Amount :</strong>
                            &#8377; <span id="txtamount">{{ count($qurbanihisse)*1500 }}</span>
                        </div>
                    </div>
                
            
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Payment Method:</strong> {{$qurbani->payment_type}}
                <select name="payment_type" onChange="onlinepaymentdetails(this);" required>
                    <option value="">Payment Method</option>
                    <option value="Cash">Cash</option>
                    <option value="GPay">UPI Payment</option>
                    <!-- <option value="Not Paid">Not Paid</option> -->
                </select>
            </div>
        </div>

        <div class="row"  id="txndetails">
                <div class="col-xs-6 col-sm-6 col-md-6 hide" >
                    <img src="/gpay-qrcode.jpg" class="img-responsive" style="width: 100%; height:80%;" alt="UPI Payment QR Code">
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 hide">
                    <div class="form-group">
                        <strong>Transaction Id:</strong>
                            <input type="text" name="transaction_number" class="form-control" placeholder="Transaction Number"  value="{{$qurbani->transaction_number}}"  onblur="funcSubmitbutton(this);">
                    </div>
                </div>
               
        </div>
        

        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary btn-sm mb-3 mt-2" id="btnsubmit"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
        </div>
    </div>
</form>

<!-- Bootstrap JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"         integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"       crossorigin="anonymous">
    </script>
<!-- jQuery CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="  crossorigin="anonymous" referrerpolicy="no-referrer">
      </script>
<script>

function submitForm() {
    var formData = $('#myForm').serialize(); // Serialize form data
    $.ajax({
        type: 'POST',
        url: '/submit-form', // Change this to your route URL
        data: formData,
        success: function(response) {
            // Handle successful response
            console.log(response);
        },
        error: function(xhr, status, error) {
            // Handle error
            console.error(xhr.responseText);
        }
    });
}

    function onlinepaymentdetails(val){
       // alert(val.value);
        if(val.value=='GPay'){
            $('#txndetails').show();
            //$('#btnsubmit').disabled();
            $('#btnsubmit').attr('disabled','disabled');
            
        }else{
            $('#txndetails').hide();
            $('#btnsubmit').removeAttr('disabled');
        }

        // $('.hisse').on('input', function() {
        //     var count = $('.hisse').filter(function() {
        //         return this.value.trim() !== ''; // Check if value is not empty
        //     }).length;
        //     console.log("Count: " + count);
        //     $("#txtamount").html(count*1500);
        // });

        //alert($('input[class="hisse"]').length);
    }

    function funcSubmitbutton(val){
        //alert(val.value);
        if(val.value.length>0){
            $('#btnsubmit').removeAttr('disabled');
        }else{
            $('#btnsubmit').attr('disabled','disabled');
        }
    }


    $(document).ready(() => {
        $('#txndetails').hide();

        //$("#txtamount").html($("#txtamount").text()*1500);


        let count=2;

        
        //alert($('input[class="hisse"]').length);
        
        // Adding row on click to Add New Row button
        $('#addBtn').click(function () {

            //$('#txthisse').html((count));

            let dynamicRowHTML = `
            <tr class="rowClass""> 
                <td class="row-index text-center"> 
                    <input type="text" name="name[${count}]" class="form-control hisse" placeholder="Name"> 
                </td>
                <td class="row-index text-center"> 
                    <input type="checkbox" name="aqiqah[${count}]" placeholder="aqiqah" value="1"> 
                </td>  
                <td class="text-center"> 
                    <button class="btn btn-danger remove"
                        type="button">Remove
                    </button> 
                </td> 
            </tr>`;
            $('#tbody').append(dynamicRowHTML);
            count++;
            
            
        });
 
        // Removing Row on click to Remove button
        $('#tbody').on('click', '.remove', function () {
           // $('#txthisse').html(($("#txthisse").text()-1));
            $(this).parent('td.text-center').parent('tr.rowClass').remove(); 
        });

        
    })
</script>
@endsection
