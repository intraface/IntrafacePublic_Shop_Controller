<form action="<?php e(url('./')); ?>" method="post" class="forms" id="step1">

<?php echo $content; ?>

<div id="purchase-buttons">
    <?php if (isset($button_back_label) && $button_back_label != ''): ?>
        <p id="purchase-back"><a href="<?php echo $button_back_link; ?>"><?php echo $button_back_label; ?></a>
    <?php endif; ?>
    <?php if (isset($button_update_label) && $button_update_label != ''): ?>
        <p id="purchase-update"><input name="<?php echo $button_update_name; ?>" value="<?php echo $button_update_label; ?>" type="submit" /></p>
    <?php endif; ?>
    <?php if (isset($button_continue_label) && $button_continue_label != ''): ?>
         <p id="purchase-continue"><input name="<?php echo $button_continue_name; ?>" value="<?php echo $button_continue_label; ?>" type="submit" /></p>
    <?php endif; ?>
</div>

</form>
