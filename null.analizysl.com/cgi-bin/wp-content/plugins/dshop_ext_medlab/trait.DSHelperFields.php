<?php

/* 
 * trait.DSHelperFields.php
 */

trait DSHelperFields{

    public function dshf_textarea( $val ){
        $cols = 70;
        $rows = 3;
        if(isset($val['cols']))$cols = $val['cols'];
        if(isset($val['rows']))$rows = $val['rows'];
        $id = $val['id'];
        $option_name = $val['option_name'];
//        $val = get_option($option_name) ;
        $pid = $val['post_id'];
        $val = get_post_meta( $pid, $option_name, true );
        $cou = count( explode("\n",$val));//esc_attr
        if($cou>=$rows)$rows = $cou+1;
        ob_start();
        ?>
<textarea cols="<?=$cols?>" rows="<?=$rows?>"
        name="<?= $option_name ?>" 
        id="<?= $id ?>" ><?php
        echo esc_attr( $val )
        ?></textarea>
    <?php
        return ob_get_clean();
    }

    public function dshf_text( $val ){
        $id = $val['id'];
        $option_name = $val['option_name'];
        $pid = $val['post_id'];
        $val = get_post_meta( $pid, $option_name, true );
        
        ob_start();
        // esc_attr( get_option($option_name) )
        ?>
        <input 
            type="text" 
            name="<?= $option_name ?>" 
            id="<?= $id ?>" 
            value="<?= $val ?>" 
        /> 
        <?php
        return ob_get_clean();
    }

    public function dshf_input( $val ){
        $id = $val['id'];
        $option_name = $val['option_name'];
        $type = $val['type'];
        $pid = $val['post_id'];
        $val = get_post_meta( $pid, $option_name, true );
        
        ob_start();
        ?>
        <input 
            type="<?= $type ?>" 
            name="<?= $option_name ?>" 
            id="<?= $id ?>" 
            value="<?= $val ?>" 
        /> 
        <?php
        return ob_get_clean();
    }

    public function dshf_number( $val ){
        $id = $val['id'];
        $option_name = $val['option_name'];
        $pid = $val['post_id'];
        $val = get_post_meta( $pid, $option_name, true );
        
        ob_start();
        ?>
        <input 
            type="number" 
            name="<?= $option_name ?>" 
            id="<?= $id ?>" 
            value="<?= $val ?>" 
        /> 
        <?php
        return ob_get_clean();
    }
    public function dshf_select( $val ){
        $pid = $val['post_id'];
        $option_name = $val['option_name'];
        $v_ = get_post_meta( $pid, $option_name, true );
        
        $val['res'] = $v_;
        return $this->dshf_select_free( $val );
    }
    public function dshf_select_free( $val ){
        $id = $val['id'];
        $option_name = $val['option_name'];
        $class = '';
        if(isset($val['class']))$class=$val['class'];
        $tpl_o=<<<t
            <option value="_v_" _s_>_n_</option>
t;
//        $pid = $val['post_id'];
        $v_ = $val['res'];
        $r=[];
//        $v_=get_option($option_name,'');
        foreach($val['items'] as $v=>$n){
            $r['_n_']=$n;
            $r['_v_']=$v;
            $r['_s_']=$v_==$v?'selected="selected"':'';
            $val['items'][$v] = strtr($tpl_o,$r);
        }
        $o=implode('',$val['items']);
        ob_start();
        ?>
    <select name="<?= $option_name ?>" class="<?= $class ?>"
            id="<?= $id ?>" ><?= $o?></select>
        <?php
        return ob_get_clean();
    }
    public function field_info_dl_links_callback( $val ){
        $id = $val['id'];
        $option_name = $val['option_name'];
        $pid = $val['post_id'];
        $val = get_post_meta( $pid, $option_name, true );
        $tpl_o=<<<t
            <dt><b>_v_</b></dt>
            <dd><a href="_l_" target="_blank">_n_</a></dd>
t;
        $r=[];
        $v_=get_option($option_name,'');
        foreach($val['items'] as $v=>$n){
            $r['_n_']=$n;
            $r['_v_']=$v;
            $r['_l_']=$n;
//            $r['_s_']=$v_==$v?'selected="selected"':'';
            $val['items'][$v] = strtr($tpl_o,$r);
        }
        $o=implode('',$val['items']);
        ?><dl><?= $o?></dl>
        <?php
    }
    public function field_info_text_callback( $val ){
        $id = $val['id'];
        $option_name = $val['option_name'];
        $pid = $val['post_id'];
        $val = get_post_meta( $pid, $option_name, true );
        $tpl_o=<<<t
            <b>_v_</b>
            <p>_n_</p>
t;
        $tpl_o=<<<t
            <p>_n_</p>
t;
        $r=[];
        $v_=get_option($option_name,'');
        foreach($val['items'] as $v=>$n){
            $r['_n_']=$n;
//            $r['_v_']=$v;
//            $r['_s_']=$v_==$v?'selected="selected"':'';
            $val['items'][$v] = strtr($tpl_o,$r);
        }
        $o=implode('',$val['items']);
        ?><?= $o?>
        <?php
    }

    public function myprefix_setting_callback_function( $val ){
        $id = $val['id'];
        $option_name = $val['option_name'];
        $pid = $val['post_id'];
        $val = get_post_meta( $pid, $option_name, true );
        ?>
<textarea cols="70" rows="5"
        name="<?= $option_name ?>" 
        id="<?=  $id ?>" ><?
        echo esc_attr( get_option($option_name) )
        ?></textarea>
    <?php /*
        ?>
        <input 
            type="text" 
            name="<?= $option_name ?>" 
            id="<?= $id ?>" 
            value="<?= esc_attr( get_option($option_name) ) ?>" 
        /> 
        <?php*/
    }
        public function lending_change_link_way_function( $val ){
        $id = $val['id'];
        $option_name = $val['option_name'];
        $pid = $val['post_id'];
        $val = get_post_meta( $pid, $option_name, true );
        $tpl_o=<<<t
            <option value="_v_" _s_>_n_</option>
t;
        $r=[];
        $v_=get_option($option_name,'desctop');
        foreach($val['items'] as $v=>$n){
            $r['_n_']=$n;
            $r['_v_']=$v;
            $r['_s_']=$v_==$v?'selected="selected"':'';
            $val['items'][$v] = strtr($tpl_o,$r);
        }
        $o=implode('',$val['items']);
        ?>
    <select name="<?= $option_name ?>" 
            id="<?= $id ?>" ><?= $o?></select>
        <?php /*
        ?>
        <input 
            type="text" 
            name="<?= $option_name ?>" 
            id="<?= $id ?>" 
            value="<?= esc_attr( get_option($option_name) ) ?>" 
        /> 
        <?php*/
    }

/*=======================*/
    
}