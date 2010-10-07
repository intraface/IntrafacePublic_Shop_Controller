<?php if (count($context->getErrors()) > 0): ?>
<ul class="errors">
<?php foreach ($context->getErrors() as $error): ?>
<li><?php e(t($error)); ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<fieldset id="details-customer-ean" class="details-customer">
    <legend><?php e(t('EAN number')); ?></legend>

    <p><?php e(t('If your organisation uses EAN number, please fill it in here.')); ?></p>
    <div class="row">
        <label for="customer_ean"><?php e(t('EAN number')); ?></label>
        <input type="text" name="customer_ean" id="customer_ean" value="<?php if (isset($customer_ean)) echo $customer_ean; ?>" />
    </div>
</fieldset>


