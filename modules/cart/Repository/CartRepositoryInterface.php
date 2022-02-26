<?php


namespace Modules\cart\Repository;


interface CartRepositoryInterface
{
    public function remove($where);

    public function change_cart_product_type($price_variation_id,$type,$where_type);

    public function update_product_final_count($priceVariation_id,$product_count);
}
