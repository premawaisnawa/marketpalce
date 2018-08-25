<section class="content-header">
  <div class="btn-group btn-breadcrumb">
    <a href="#" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-home"></i></a>
    <a  class="btn btn-default  btn-xs active">Member List</a>
  </div>
</section>
<style>
  .dataTables_wrapper .dataTables_filter {
  float: right;
  text-align: right;
  visibility: hidden;
  }
</style>
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Member List</h3>
          <!-- <a style="float:right"  href="<?php ?>" class="btn btn-primary">
            <i class="glyphicon glyphicon-saved"></i>
            Add Supplier
          </a> -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="table-responsive">


          <!--  -->
          <style>

                .dropdown.dropdown-lg .dropdown-menu {
                    margin-top: -1px;
                    padding: 6px 20px;
                }
                .input-group-btn .btn-group {
                    display: flex !important;
                }
                .btn-group .btn {
                    border-radius: 0;
                    margin-left: -1px;
                }
                .btn-group .btn:last-child {
                    border-top-right-radius: 4px;
                    border-bottom-right-radius: 4px;
                }
                .btn-group .form-horizontal .btn[type="submit"] {
                  border-top-left-radius: 4px;
                  border-bottom-left-radius: 4px;
                }
                .form-horizontal .form-group {
                    margin-left: 0;
                    margin-right: 0;
                }
                .form-group .form-control:last-child {
                    border-top-left-radius: 4px;
                    border-bottom-left-radius: 4px;
                }
            @media screen and (min-width: 768px) {
                  #adv-search {
                      width: 275px;
                      margin: 0 auto;
                  }
                  .dropdown.dropdown-lg {
                      position: static !important;
                  }
                  .dropdown.dropdown-lg .dropdown-menu {
                      min-width: 275px;
                  }
              }
          </style>
          <div class="input-group" style="float:right" id="adv-search">
                <input type="text" class="form-control" placeholder="Search for company name" />
                <div class="input-group-btn">
                    <div class="btn-group" role="group">
                        <div class="dropdown dropdown-lg">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>
                            <div class="dropdown-menu dropdown-menu-right form-horizontal" role="menu">
                                <form class="form-horizontal" method="GET"  role="form" action="<?php echo site_url('User/member_view'); ?>">
                                  <div class="form-group">
                                    <label for="filter">Filter by member category</label>
                                    <select class="form-control" name="user_level" id="user_level">
                                      <?php if ($user_level == 1){ ?>
                                        <option value="-1" >All Category</option>
                                        <option value="1" selected>Supplier</option>
                                        <option value="2" >Buyer</option>
                                        <option value="3">Both</option>
                                      <?php }elseif($user_level == 2){ ?>
                                        <option value="-1" >All Category</option>
                                        <option value="1" >Supplier</option>
                                        <option value="2" selected>Buyer</option>
                                        <option value="3">Both</option>
                                      <?php }elseif($user_level == 3){ ?>
                                        <option value="-1" >All Category</option>
                                        <option value="1" >Supplier</option>
                                        <option value="2" >Buyer</option>
                                        <option value="3" selected>Both</option>
                                      <?php }elseif($user_level == -1){ ?>
                                        <option value="-1" selected>All Category</option>
                                        <option value="1" >Supplier</option>
                                        <option value="2" >Buyer</option>
                                        <option value="3">Both</option>
                                      <?php }else{ ?>
                                        <option value="-1" selected>All Category</option>
                                        <option value="1" >Supplier</option>
                                        <option value="2" >Buyer</option>
                                        <option value="3">Both</option>
                                       <?php } ?>
                                    </select>
                                  </div>
                                  <button type="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                  <!-- <div class="form-group">
                                    <label for="contain">State</label>
                                    <input class="form-control" type="text" />
                                  </div>
                                  <div class="form-group">
                                    <label for="contain">Province</label>
                                    <input class="form-control" type="text" />
                                  </div> -->
                                </form>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                    </div>
                </div>
            </div>
          <!--  -->

            <table id="example1" class="table table-bordered table-striped">
              <thead class="text-center">
                <tr>
                  <th>Company Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>City</th>
                  <th>State</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody class="text-center">
              <?php foreach($member as $m){?>
              <tr  class='odd gradeX context'>
                <td><?php echo $m->CompanyName?></td>
                <td><?php echo $m->Email?></td>
                <td><?php echo $m->Phone?></td>
                <td><?php echo $m->City?></td>
                <td><?php echo $m->State?></td>
                <td><?php echo $m->Province?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->



  <script type="text/javascript">
    // function search(e){
    //   e.preventDefault();
    //   // ambil url pada atribute form action
    //   //var url = $('#Simpan').attr('action');
    //   // ambil inputannya
    //   var data = {
    //       'user_level'              : $('input[name=user_level]').val()
    //   }
    //   lakukan proses ajax
    //   $.ajax({
    //       type        : 'GET',
    //       url         : <?php //echo site_url('User/member_view'); ?>,
    //       data        :  data,
    //       success: function(response) {
    //         //  $('#Simpan').find('input').val();
    //       }

    //   });

    //   return false;
    // }
  </script>


<script>
$(function () {
  $("#example1").DataTable();
  $('#example2').DataTable({
    "paging": true,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "info": true,
    "autoWidth": false
  });
});
</script>
<script type="text/javascript">

// var save_method; //for save method string
// var table;

// $(document).ready(function() {
//   //datatables
//   table = $('#example1').DataTable({
//     "processing": true, //Feature control the processing indicator.
//     "serverSide": false, //Feature control DataTables' server-side processing mode.
//     "paging": true,
//     "lengthChange": true,
//     "searching": false,
//     "ordering": true,
//     "info": true,
//     "autoWidth": false,
//     "order": [], //Initial no order.
//     // Load data for the table's content from an Ajax source
//     "ajax": {
//       "url": '<?php// echo site_url('User/get_member_json'); ?>',
//       "type": "POST"
//     },
//     //Set column definition initialisation properties.
//     "columns": [
//       {"data": "CompanyName"},
//       {"data": "Email"},
//       {"data": "Phone"},
//       {"data": "City"},
//       {"data": "State"},
//       {"data": "DetailButton"}
//     ],

//   });

// });
</script>