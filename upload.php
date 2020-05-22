<?php 
    require_once("includes/header.php"); 
    require_once("includes/classes/VideoDetailsFormProvider.php");
?>

<div class="column">
    <?php 
        // Call a instance of VideoDetailsFormProvider to create a new Upload Form
        $formProvider = new VideoDetailsFormProvider($con);
        echo $formProvider->createUploadForm();
    ?>
</div>

<script>
$("form").submit(() => {
    $("#loadingModal").modal("show");
});
</script>

<!-- Modal -->
<!-- data-backdrop=static attribute prevents modal closing when the user clicks outside -->
<!-- data-keyboard=false attribute prevents modal closing when the user makes a keystroke  -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        Please wait this may take a while
        <img src="assets/images/icons/loading-spinner.gif" alt="">
      </div>
    </div>
  </div>
</div>

<?php require_once("includes/footer.php"); 