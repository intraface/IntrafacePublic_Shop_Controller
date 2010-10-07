<h1><?php e(t(ucfirst($headline))); ?></h1>

<?php if (is_array($context->document()->purchaseSteps())) { ?>
    <ol id="purchase-steps">
    <?php foreach($context->document()->purchaseSteps() AS $step) { ?>
        <li>
        <?php if ($step == $context->document()->currentStep()) { ?>
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