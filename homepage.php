<?php 
include './main_sidebar.php';
?>
<span id="dashboard">
  <?php
  include './dashboard.php';
  ?>
</span>


<?php
include 'footer.php';
?>

<script type="text/javascript">
  setInterval(dashboard, 10000);

  function dashboard() {
    $.ajax({
      url: "./dashboard.php",
      type: 'GET',
      success: function(res) {
        $("#dashboard").html(res);
      }
    });
  }
// $(document).ready(function(){
//         var url=window.location.href.toString().split(window.location.host)[1];
//     if (url=="/roster/homepage.php") {
//     }
//       });
</script>