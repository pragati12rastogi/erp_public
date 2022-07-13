@extends('layouts.master')
@section('title', 'Lead Import')

@push('style')

@endpush

@push('custom-scripts')
    <script>
        $(function() {
           
        });
        
        
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12 ">
        
            @include('flash-msg')
        
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="border-bottom mb-3 row">
                        <div class="col-md-10">
                            <h4 class="card-title">Leads Import</h4>
                        </div>
                    </div>
                    <a href="{{ url('excel/leads_excel.xlsx') }}" class="btn btn-md btn-success"> Download Example For xls/csv File</a>
			        <hr>
                    <form action="" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                    
                        <div class="row">
                            <div class="form-group col-md-6">
                                <b class="form-control-plaintext" for="file">Choose your xls/csv File :</b>
                                <input required="" type="file" name="file" class="form-control">
                                @if ($errors->has('file'))
                                    <span class="invalid-feedback text-danger" role="alert">
                                        <strong>{{ $errors->first('file') }}</strong>
                                    </span>
                                @endif
                                <p></p>
                                <button type="submit" class="btn btn-md btn-dark">Import</button>
                            </div>
                        </div>
                        
                    </form>
                    <hr>
                    <h4>Instructions</h4>
                    <p><b>Follow the instructions carefully before importing the file.</b></p>
			        <p>The columns of the file should be in the following order.</p>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Column No</th>
                                <th>Column Name</th>
                                <th>Description</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><b>Name</b> (Required)</td>
                                <td>Name of Lead</td>

                            </tr>
                            <tr>
                                <td>2</td>
                                <td><b>Status</b> (Required)</td>
                                <td>Name of your status (Must created before importing).</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td><b>Source</b> (Required)</td>
                                <td>Name of your source (Must created before importing).</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td><b>Email</b> (Required)</td>
                                <td>Email Id of lead</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td><b>Mobile</b> (Required)</td>
                                <td>Mobile number of lead</td>
                            </tr>

                            <tr>
                                <td>6</td>
                                <td><b>Assigned To</b> (Required)</td>
                                <td>User Email whom lead will be assigned.</td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td><b>Zipcode</b></td>
                                <td>Pincode of lead location </td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td><b>City</b> </td>
                                <td>City of lead location</td>
                            </tr>

                            <tr>
                                <td>9</td>
                                <td><b>State</b> </td>
                                <td>State of lead location</td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td><b>Country</b> </td>
                                <td>Country of lead location</td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td><b>Address</b> </td>
                                <td>Address of lead location</td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td><b>Lead Value</b> </td>
                                <td>Value of lead</td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td><b>Company</b> </td>
                                <td>Company of lead</td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td><b>Description</b> </td>
                                <td>Description of lead</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection