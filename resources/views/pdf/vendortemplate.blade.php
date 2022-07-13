<!DOCTYPE html>
<html lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
    .page-break {
            page-break-inside: avoid;       
    }
    @page {
        margin-top:50px;
        footer: page-footer;
        font-family: 'Arial Narrow', Arial, sans-serif;
        font-size: 14px;
    }
    .center{
        text-align: center;
    }
    .tablestyle{
        border-collapse: collapse;
        width: 100%;
    }
    .tablestyle, th, td{
        border: 1px solid black;
        padding: 4px;
    }
    .lesswidth{
        width: 35%;
    } 
    .left{
        text-align: left;
        font-weight: 100;
        font-size: 16px;
    }
    .justify{
        text-align: justify;
        font-weight: 100;
        font-size: 16px;
    }
    th{
        font-size: 14px;
    }
    </style>
     <script>
            
    </script>
       
</head>
<body>
    <div >
        
        <div class="center">
            <h2>Users Summary</h3>
        </div>
        
        <br>
        <table class="tablestyle" >
            
            <tr> 
                <th>Id</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Firm Data</th>
                <th>Address</th>
                
                
            </tr>
            @foreach($table_data as $ind => $data)
            <tr>
                
                <td class="center"> {{$data['id']}} </td>
                <td class="center"> {{$data['name']}} </td>
                <td class="center">
                    <p><b>Email:</b>{{$data['email']}}</p>  
                    <p><b>Mobile:</b>{{$data['phone']}}</p>  
                </td>
                <td class="center"> 
                    <p><b>Firm Name:</b>{{$data['firm_name']}}</p>  
                    <p><b>GST Number:</b>{{$data['gst_no']}}</p>  
                </td>
                <td class="center">
                    <p><b>Address:</b>{{$data['address']}}</p>  
                    <p><b>District:</b>{{$data['district']}}</p> 
                    <p><b>State:</b>{{$data['state']}}</p> 
                </td>
                
            </tr>
            @endforeach
        </table>
        
    </div>
 
</body>

</html>