<?php  
   function activate_menu($controller) {
     $CI = get_instance();
     $last = $CI->uri->total_segments();
     $seg = $CI->uri->segment($last);
     if(is_numeric($seg)) {
       $seg = $CI->uri->segment($last-1);
     }
     if (in_array($controller, array($seg))) {
         return 'active';
     } else {
         return '';   
     } 
   }
   if(!isset($this->session->userdata['session_data'])) {
     $url=base_url().'login';
     header("location: $url");
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="x-ua-compatible" content="ie=edge">
      <title><?php 
         $data = sitedata();
         $total_segments = $this->uri->total_segments(); 
         echo ucwords(str_replace('_', ' ', $this->uri->segment(1))).' | '.output($data['s_companyname']) ?></title>
      <!-- Font Awesome Icons -->
      <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/fontawesome-free/css/all.min.css">
      <link rel="stylesheet" href="<?= base_url(); ?>assets/dist/css/adminlte.css">
      <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/daterangepicker/daterangepicker.css">
      <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.min.css">
      <!-- Google Font: Source Sans Pro -->
      <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
      <script src="<?= base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
      <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/plugins/toast/toast.min.css" />
      <script src="<?= base_url(); ?>assets/plugins/toast/toast.min.js"></script>
   </head>
   <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
      <div class="wrapper">
      <!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-dark theme-navbar-primary">
         <!-- Left navbar links -->
         <ul class="navbar-nav">
            <li class="nav-item">
               <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
         </ul>
         <input type="hidden" id="base" value="<?php echo base_url(); ?>">
         <!-- Right navbar links -->
         <ul class="navbar-nav ml-auto">
            <!-- Messages Dropdown Menu -->
            <!-- Notifications Dropdown Menu -->




            <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>

      <li >
               <a href="<?= base_url(); ?>dashboard" class="nav-link <?php echo activate_menu('dashboard');?>">   
                  
                     Dashboard
                  
               </a>
            </li>
            <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Vehicles 
        </a>

               <div class="dropdown-menu" aria-labelledby="navbarDropdown">

               <a class="dropdown-item" href="<?= base_url(); ?>vehicle" class="nav-link ">
                        <i class="nav-icon fas faa-list"></i>
                        <p>Vehicle List</p>
                     </a>
             <a class="dropdown-item" href="<?= base_url(); ?>vehicle/addvehicle" class="nav-link ">
                        <i class="nav-icon fas faa-plus"></i>
                        <p>Add Vehicle</p>
                     </a>
              <a class="dropdown-item" href="<?= base_url(); ?>vehicle/vehiclegroup" class="nav-link ">
                        <i class="nav-icon fas faa-plus"></i>
                        <p>Vehicle Group</p>
                     </a>
              </li>
              <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            driver
        </a>

               <div class="dropdown-menu" aria-labelledby="navbarDropdown">

               <a class="dropdown-item" href="<?= base_url(); ?>vehicle" class="nav-link ">
                        <i class="nav-icon fas faa-list"></i>
                        <p>Vehicle List</p>
                     </a>
             <a class="dropdown-item" href="<?= base_url(); ?>vehicle/addvehicle" class="nav-link ">
                        <i class="nav-icon fas faa-plus"></i>
                        <p>Add Vehicle</p>
                     </a>
              <a class="dropdown-item" href="<?= base_url(); ?>vehicle/vehiclegroup" class="nav-link">
                        <i class="nav-icon fas faa-plus"></i>
                        <p>Vehicle Group</p>
                     </a>
              </li>
                 
                   
            
            <li class="nav-item">
               <a class="nav-link"  href="<?= base_url(); ?>login/logout">
               <i class="fas fa-sign-out-alt"></i></a>
            </li>
         </ul>
      </nav>
      <!-- /.navbar -->