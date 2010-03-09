<h1><?php e(__(ucfirst($headline))); ?></h1>

<?php if(isset($this->document->purchase_steps) && is_array($this->document->purchase_steps)) { ?>
    <ol id="purchase-steps">
    <?php foreach($this->document->purchase_steps AS $step) { ?>
        <li>
        <?php if($step == $this->document->current_step) { ?>
            <strong><?php e(ucfirst(__($step))); ?></strong>
        <?php } else { ?>
            <?php e(ucfirst(__($step))); ?>
        <?php } ?>
        </li>
    <?php } ?>
    </ol>
    <div id="purchase-steps-after"></div>
<?php } ?>

<?php echo $error; ?>

<?php echo $content; ?>