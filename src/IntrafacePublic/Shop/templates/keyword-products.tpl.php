<h1 id="keyword-name"><?php e(t('Keyword')); ?></h1>

<?php if (is_array($products) AND count($products) > 0): ?>

<table summary="" id="products">
    <?php foreach ($products AS $product): ?>
    <tr>
        <td class="picture">
            <?php if (array_key_exists(0, $product['pictures'])): ?>
                <img src="<?php e($product['pictures'][0]['thumbnail']['file_uri']); ?>" alt="<?php e($product['name']); ?>" height="<?php e($product['pictures'][0]['thumbnail']['height']); ?>" width="<?php e($product['pictures'][0]['thumbnail']['width']); ?>" />
            <?php endif; ?>
        </td>
        <td class="name"><a href="<?php e($this->urlToProductId($product['id'])); ?>"><?php e($product['name']); ?></a></td>
        <td class="price" nowrap="nowrap"><?php e($currency); ?> <?php $price_incl_vat = new Ilib_Variable_Float($product['currency'][$currency]['price_incl_vat']);  e($price_incl_vat->getAsLocal($this->document->locale, 2)); ?></td>
        <td  nowrap="nowrap" class="buy">
            <?php if ($product['has_variation']): ?>
                <a class="details" href="<?php e($this->urlToProductId($product['id'])); ?>"><?php e(t('Details')); ?></a>
            <?php elseif ($product['stock'] == 0 || $product['stock_status']['for_sale'] > 0): ?>
                <form method="POST" class="buy" action="<?php e($this->urlToProductId($product['id'] . '/add')); ?>">
                    <input type="submit" class="buy" name="add_product" value="<?php e(t('Buy')); ?>" />
                </form>
            <?php else: ?>
                <?php e(t('Not in stock')); ?>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php endif; ?>