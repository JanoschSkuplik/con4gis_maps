<!-- indexer::stop --> 

<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<script type="text/javascript">
//<![CDATA[
C4GMaps(<?php echo json_encode($this->mapData) ?>);
//]]>
    </script>
  
  <?php if ($this->mapData['createDiv']): ?>
  <div id="<?php echo $this->mapData['div']; ?>" style="width:<?php echo $this->mapData['width']; ?>;height:<?php echo $this->mapData['height']; ?>;margin:0">
  </div>
  <?php endif; ?>

</div>    
<!-- indexer::continue -->