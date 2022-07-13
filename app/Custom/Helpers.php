<?php

  use App\Models\Role;
  use App\Custom\Constants;
  use App\Models\User;
  use App\Models\DistributionOrder;
  use App\Models\UserStockDistributionOrder;
  use App\Models\InvoiceSetting;

  // For add'active' class for activated route nav-item
  function active_class($path, $active = 'active') {
    return call_user_func_array('Request::is', (array)$path) ? $active : '';
  }

  // For checking activated route
  function is_active_route($path) {
    return call_user_func_array('Request::is', (array)$path) ? 'true' : 'false';
  }

  // For add 'show' class for activated route collapse
  function show_class($path) {
    return call_user_func_array('Request::is', (array)$path) ? 'show' : '';
  }

  function is_admin($role_id){
    $role = Role::where('id',$role_id)->where('name',Constants::ROLE_ADMIN)->first();
    if(empty($role)){
      return false;
    }else{
      return true;
    }
  }

  function generate_invoice_no(){
    $inv = InvoiceSetting::where('user_id',Auth::id())->first();
    $distribution = (int) filter_var($inv['suffix_number_length'], FILTER_SANITIZE_NUMBER_INT);
    $suffix_length = strlen($inv['suffix_number_length']);
    
    if(empty($distribution)){
      $make_inv_no = str_pad(1,$suffix_length,"0",STR_PAD_LEFT);
      
    }else{
      $increment = (int)$distribution+1;
      $make_inv_no = str_pad($increment,$suffix_length,"0",STR_PAD_LEFT);
    }
    
    $inv->update(['suffix_number_length' => $make_inv_no]);
    
    return $inv['prefix'].$make_inv_no;
  }

  function getInvoiceNo($distribution_id = 0){
    $inv = InvoiceSetting::where('user_id',Auth::id());
    $distribution = DistributionOrder::where('id',$distribution_id)->first();

    return $inv['prefix'].$distribution['invoice_no'];
  }


  function getLocalInvoiceNo($distribution_id = 0){
    $inv = InvoiceSetting::where('user_id',Auth::id());
    $distribution = UserStockDistributionOrder::where('id',$distribution_id)->first();

    return $inv['prefix'].$distribution['invoice_no'];
  }

  function getUsers($role = ''){
    $user = User::whereHas('role', function($query) use($role){
      return $query->where('name',$role);
    })->get();

    return $user;
  }

  function bytesToSize($path, $filesize = '')
  {
    
    if (!is_numeric($filesize)) {
        $bytes = sprintf('%u', filesize($path));
    } else {
        $bytes = $filesize;
    }
    
    if ($bytes > 0) {
        $unit  = intval(log($bytes, 1024));
        $units = [
            'B',
            'KB',
            'MB',
            'GB',
        ];
        if (array_key_exists($unit, $units) === true) {
            return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
        }
    }

    return $bytes;
  }