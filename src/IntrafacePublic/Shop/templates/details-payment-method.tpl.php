
<?php if (count($payment_methods) == 1): ?>
    <input type="hidden" name="payment_method" value="<?php e($payment_methods[0]['identifier']); ?>" />
<?php else: ?>
    <fieldset id="details-payment-method" class="details-customer">
        <legend><?php e(t('Payment method')); ?></legend>
        <p><?php e(t('Select how you would like to pay for your order.')); ?></p>
        <ul>
            <?php foreach($payment_methods as $payment): ?>
                <li><input id="payment-<?php e(strtolower($payment['identifier'])); ?>" type="radio" name="payment_method" value="<?php e($payment['identifier']); ?>" <?php if (isset($payment_method) && is_array($payment_method) && isset($payment_method['identifier']) && $payment_method['identifier'] == $payment['identifier']) echo 'checked="checked"'; ?> /> <label for="payment-<?php e(strtolower($payment['identifier'])); ?>"><strong><?php e(t($payment['description'])); ?></strong> <?php if (isset($payment['text'])) e($payment['text']); ?></label></li>
            <?php endforeach; ?>
        </ul>
    </fieldset>
<?php endif; ?>