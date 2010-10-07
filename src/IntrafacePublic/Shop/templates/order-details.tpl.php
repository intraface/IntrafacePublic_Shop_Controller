<table id="order-address-details">
    <caption><?php e(t('Address information')); ?></caption>

    <tr>
        <th><?php e(t('Name')); ?></th>
        <td><?php if (isset($value['name'])) e($value['name']); ?></td>
    </tr>
    <?php if (!empty($value['contactperson'])): ?>
    <tr>
        <th><?php e(t('Contact person')); ?></th>
        <td><?php if (isset($value['contactperson'])) e($value['contactperson']); ?></td>
    </tr>
    <?php endif; ?>
    <tr>
        <th><?php e(t('Address')); ?></th>
        <td><?php if (isset($value['address'])) nl2br(e($value['address'])); ?></td>
    </tr>
    <tr>
        <th><?php e(t('Zip code and city')); ?></th>
        <td><?php if (isset($value['postcode'])) e($value['postcode']); ?> <?php if (isset($value['city'])) e($value['city']); ?></td>
    </tr>

    <tr>
        <th><?php e(t('Country')); ?></th>
        <td><?php if (isset($value['country'])) e($value['country']); ?></td>
    </tr>

    <tr>
        <th><?php e(t('Email')); ?></th>
        <td><?php if (isset($value['email'])) e($value['email']); ?></td>
    </tr>
    <tr>
        <th><?php e(t('Phone')); ?></th>
        <td><?php if (isset($value['phone'])) e($value['phone']); ?></td>
    </tr>
    <?php if (isset($value['customer_ean']) && $value['customer_ean'] != ''): ?>
        <tr>
            <th><?php e(t('EAN number')); ?></th>
            <td><?php if (isset($value['customer_ean'])) e($value['customer_ean']); ?></td>
        </tr>
    <?php endif; ?>


    <?php if (isset($value['customer_coupon']) && $value['customer_coupon'] != ''): ?>
        <tr>
            <th><?php e(t('Customer coupon')); ?></th>
            <td><?php if (isset($value['customer_coupon'])) e($value['customer_coupon']); ?></td>
        </tr>
    <?php endif; ?>

    <?php if (isset($value['customer_comment']) && $value['customer_comment'] != ''): ?>
        <tr>
            <th><?php e(t('Comment')); ?></th>
            <td><?php if (isset($value['customer_comment'])) e(nl2br($value['customer_comment'])); ?></td>
        </tr>
    <?php endif; ?>
    
    <?php if (isset($value['payment_method']) && is_array($value['payment_method']) && isset($value['payment_method']['identifier'])): ?>
        <tr>
            <th><?php e(t('Payment method')); ?></th>
            <td><?php e(t($value['payment_method']['description'])); ?></td>
        </tr>
    <?php endif; ?>

</table>
