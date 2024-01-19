

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="../assets/bower_components/DataTables-1.13.8/js/datatables.min.js"></script>

<script src="../assets/bower_components/DataTables-1.13.8/js/pdfmake.min.js"></script>
<script src="../assets/bower_components/DataTables-1.13.8/js/vfs_fonts.js"></script>



<!-- <script src="../assets/bower_components/DataTables-1.13.8/js/dataTables.bootstrap.min.js"></script> -->
<script src="../assets/dist/js/adminlte.min.js"></script>
<script src="../assets/dist/js/demo.js"></script>

<script src="../js/popper.js"></script>

<script src="../js/main.js"></script>

<script>
$(document).ready(function() {
  var table = $('#example1').DataTable({
    "aaSorting": [[0, 'desc']],
    buttons: ['copy', 'excel', 'print']
  });

  new $.fn.dataTable.Buttons(table, {
    buttons: ['copy',  'excel',  'print']
  });

  table.buttons().container()
    .appendTo('#example1_wrapper .col-md-6:eq(0)');
});

</script>


<style>
.data_table{
  padding: 5px;
}

.data_table .btn{
 
  margin: 5px 3px 5px 3px;
}

</style>



















<!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="../assets/bower_components/DataTables-1.13.8/js/datatables.min.js"></script>

<script src="../assets/bower_components/DataTables-1.13.8/js/pdfmake.min.js"></script>
<script src="../assets/bower_components/DataTables-1.13.8/js/vfs_fonts.js"></script>



<!-- <script src="../assets/bower_components/DataTables-1.13.8/js/dataTables.bootstrap.min.js"></script> -->
<!-- <script src="../assets/dist/js/adminlte.min.js"></script>
<script src="../assets/dist/js/demo.js"></script>

<script>
$(document).ready(function() {
  var table = $('#example1').DataTable({
    "aaSorting": [[0, 'desc']],
    buttons: ['copy', 'excel', 'print']
  });

  new $.fn.dataTable.Buttons(table, {
    buttons: ['copy',  'excel',  'print']
  });

  table.buttons().container()
    .appendTo('#example1_wrapper .col-md-6:eq(0)');
});

</script>


<style>
.data_table{
  padding: 5px;
}

.data_table .btn{
 
  margin: 5px 3px 5px 3px;
} -->

<!-- </style> -->