<?php if ($this->session->flashdata('message')) {
	echo '<p class="success">'.$this->session->flashdata('message').'</p>';
} ?>

<?php if ($blogs): foreach($blogs as $blog):?>
<div class="blog">
	<?php echo $blog ->title?>
	<br clear="all" />

	<hr />
</div><!-- Close blog -->
<?php endforeach; else: ?>
  <h2>No blogs yet</h2>
<?php endif; ?>