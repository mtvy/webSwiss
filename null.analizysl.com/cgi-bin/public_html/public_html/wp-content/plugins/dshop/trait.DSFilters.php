<?php

/* 
 * trait.DSFilters
 */

trait DSFilters{
    public function admin_DShopOrderItem_info($pid,$orderItem){
        // использовать ли доставку и адрес
        $delivery_use = get_option('delivery_use', 0);
    
        $medLab = MedLab::_instance();

        $groups = $medLab->groups;
        $analyses = $medLab->analyses;
        $panels = $medLab->panels;
        $tests = $medLab->tests;
        $biomaterials = $medLab->biomaterials;
        $drugs = $medLab->drugs;
        $microorganisms = $medLab->microorganisms;
        $containers = $medLab->containers;
        $price = $medLab->price;
        $currency_short = get_option('currency_short','zl');
        
        $code = '';
        if(isset($analyses[$pid])){
            $code=$analyses[$pid]['Code'];
        }
        if(isset($panels[$pid])){
            $code=$panels[$pid]['Code'];
        }
//        news_source
//        
//        ds_adm_oi_code
//        dshop admin orderItem code
        /*
         * 
            <tr><td style="" colspan="">Код</td>
                <td colspan=""><input style="width: 100%" type="text"
                name="ds_adm_oi_code" value="<?=$code?>"></td></tr>
         */
        ?>
            
            <tr><td style="" colspan="">Код</td>
                <td colspan=""><?=$code?></td></tr>
        <?php
    }
}