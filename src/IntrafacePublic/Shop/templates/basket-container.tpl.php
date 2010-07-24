<h1><?php e(t(ucfirst($headline))); ?></h1>

<?php if(isset($this->document->purchase_steps) && is_array($this->document->purchase_steps)) { ?>
    <ol id="purchase-steps">
    <?php foreach($this->document->purchase_steps AS $step) { ?>
        <li>
        <?php if($step == $this->document->current_step) { ?>
            <strong><?php e(ucfirst(t($step))); ?></strong>
        <?php } else { ?>
            <?php e(ucfirst(t($step))); ?>
        <?php } ?>
        </li>
    <?php } ?>
    </ol>
    <div id="purchase-steps-after"></div>
<?php } ?>

<?php echo $error; ?>

<?php echo $content; ?>