<?php
if (!isset($picture_size) || $picture_size == '') {
    $picture_size = 'thumbnail';
}

$only_show_products_with_pictures = 1;
?>

<?php if (is_array($products) AND count($products) > 0): ?>
<?php if (isset($headline) && $headline != ''): ?>
    <h3 style="clear: both;"><?php e($headline); ?></h3>
<?php endif; ?>

<?php foreach ($products AS $product): ?>
    <?php if (!empty($product['pictures'][0]) AND !array_key_exists(0, $product['pictures']) && isset($only_show_products_with_pictures) && $only_show_products_with_pictures == 1) continue; ?>

    <div class="product-feature">
        <?php if (isset($show_name) && $show_name == 1): ?>
            <p><a href="<?php e(url('product/' . $product['id'])); ?>"><?php e($product['name']); ?></a></p>
        <?php endif; ?>
        <?php if (!empty($product['pictures'][0])): ?>
        <a href="<?php e(url('product/' . $product['id'])); ?>">
            <img src="<?php e($product['pictures'][0][$picture_size]['file_uri']); ?>" alt="<?php e($product['name']); ?>" height="<?php e($product['pictures'][0][$picture_size]['height']); ?>" width="<?php e($product['pictures'][0][$picture_size]['width']); ?>" />
        </a><br />
        <?php else: ?>
            <?php e($product['name']); ?>
        <?php endif; ?>
        <?php e($currency); ?> <?php $price = new Ilib_Variable_Float($product['currency'][$currency]['price_incl_vat']); e($price->getAsLocal($context->document()->locale(), 2)); ?>
        <div class="buy">
            <?php if ($product['has_variation']): ?>
                <a class="details" href="<?php e(url('product/' .$product['id'])); ?>"><?php e(t('Details')); ?></a>
            <?php elseif ($product['stock'] == 0 || $product['stock_status']['for_sale'] > 0): ?>
                <form method="POST" class="buy" action="<?php e(url('product/' . $product['id'] . '/add')); ?>">
                    <input type="submit" class="buy" name="add_product" value="<?php e(t('Buy')); ?>" />
                </form>
            <?php else: ?>
                <?php e(t('Not in stock')); ?>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
<div style="clear: both;"></div>

<?php endif; ?>