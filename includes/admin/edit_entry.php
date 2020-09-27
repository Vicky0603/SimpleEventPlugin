<div class="container">
   <div class="row">
    <form method="post" enctype="multipart/form-data">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Edit Event</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            <?php
            if(isset($messageError))
            {
                echo "<div class='alert alert-danger'><strong>Error : ".$messageError."</strong></div>";
            }
            if(isset($messageSuccess))
            {
                echo "<div class='alert alert-success'><strong>".$messageSuccess."</strong></div>";
            }
            ?>
              <div class="box-body">
                <div class="form-group">
                  <label for="inputTitle">Title</label>
                  <input type="text" name="title" value="<?php echo $result->title ?>" class="form-control" id="inputTitle" >
                    <p class="help-block">Add Event Title</p>
                </div>
                
                <div class="form-group">
                  <label for="inputDescription">Event Description</label>
                  <textarea name="description" id="inputDescription" class="form-control" ><?php echo $result->description; ?></textarea>
                    <p class="help-block">Add Event Description</p>
                </div>
                 <div class="form-group">
                  <label for="inputImage">Image</label>
                  <input type="file" name="image" class="form-control" id="inputImage">
                     <p class="help-block">Upload Event Image (Not Required)</p>
                </div>
                <div class="form-group">
                  <label for="inputDate">Date</label>
                  <input type="date" name="date" value="<?php echo $result->date; ?>" class="form-control" id="inputDate" >
                    <p class="help-block">Add Event Date</p>
                
                </div> 
                <div class="form-group">
                  <label for="inputStartTime">Start Time</label>
                  <input type="time" name="start_time" value="<?php echo $result->start_time; ?>" class="form-control" id="inputStartTime" >
                    <p class="help-block">Example : 12:05 Am</p>
                    
                </div>  
                <div class="form-group">
                  <label for="inputEndTime">End Time</label>
                  <input type="time" name="end_time" value="<?php echo $result->end_time; ?>" class="form-control" id="inputEndTime" >
                    <p class="help-block">Example : 12:05 Am</p>
                </div>
                <div class="form-group">
                  <label for="inputCategory">Event Category</label>
                  <input type="text" name="event_category" value="<?php echo $result->event_category ?>" class="form-control" id="inputCategory" >
                    <p class="help-block">Add Event Category</p>
                </div>  
                <div class="form-group">
                  <label for="inputCategory">Tags</label>
                  <input type="text" name="tags" value="<?php echo $tagsString; ?>"  id="input-tags"  class="form-control">
                    <p class="help-block">Add Event Tags</p>
                </div>                
                   
              </div>
              <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" name="edit" class="btn btn-primary">Edit</button>
                </div>
          </div>
          <!-- /.box -->
    </div>
                    
    </form>    
</div>
</div>


<script type="text/javascript">
	$('#input-tags').tagsInput();
</script>