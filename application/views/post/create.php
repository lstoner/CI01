<?php if (validation_errors()) {
	echo validation_errors('<p class="error">','</p>');
} ?>
      
<?php if ($this->session->flashdata('message')) {
	echo '<p class="success">'.$this->session->flashdata('message').'</p>';
} ?>

<?php echo form_open_multipart('post/save', 'class="crud"');?>
<p>
	<strong>Title</strong>:<br /> <input type="text" name="title" size="60" />
</p>
<br clear="all" />

<p>
	<strong>Body</strong>: (HTML mode)
</p>
<textarea rows="6" cols="80%" name="body" style="resize: none;"></textarea>
<br clear="all" />

<p>
  <strong>Image</strong>:
</p>
<input type="file" name="image" size="45" />		 
<br clear="all" />

<p>
	<input type="submit" value="Submit" />
</p>
<?php echo form_close(); ?>
