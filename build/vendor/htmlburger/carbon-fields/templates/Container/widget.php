<div class="carbon-container">
	<fieldset class="container-<?php 
namespace _PhpScoper9a3678ae6a12;

echo $this->get_id();
?>" data-json="<?php 
echo \urlencode(\json_encode($this->to_json(\false)));
?>"></fieldset>
	<?php 
if (!$this->has_fields()) {
    ?>
		<?php 
    _e('No options are available for this widget.', 'carbon-fields');
    ?>
	<?php 
}
?>
</div>
<?php 
