<?php

/* 
 * class.DShop.php
 */
include 'class.DShopInit.php';
////include 'class.AllegroAuth.php';
//include 'class.AllegroAdmin.php';
//include 'trait.AllegroHtml.php';
////include 'trait.AllegroCatalog.php';
////include 'trait.AllegroCrumbs.php';
////include 'trait.AllegroProducts.php';
////include 'trait.AllegroProduct.php';
include_once 'trait.DShopDB.php';
include 'trait.DShopShortcodes.php';
include 'trait.DSGet.php';
include 'trait.DSAjax.php';
include 'trait.DSProcess.php';
include 'trait.DSFilters.php';
include_once 'trait.DShopHtml.php';


class DShop extends DShopInit{
    public $_debug = false;

    use
//            AllegroAdmin,
//            AllegroHtml,
////            AllegroCatalog,
////            AllegroCrumbs,
////            AllegroProducts,
////            AllegroProduct,
            DShopDB,
            DShopHtml,
            DSGet,
            DSAjax,
            DSProcess,
            DSFilters,
            DShopShortcodes
            ;
    
    private static $instance = null;
	private static $initiated = false;
    
    public function __construct() {
        parent::__construct();
        
        add_filter('ds_dsproduct_data_placeholders', [$this,'ds_dsproduct_data_placeholders'], 0, 2 );
        add_filter('ds_dsproduct_data_placeholders_second', [$this,'ds_dsproduct_data_placeholders_second'], 0, 2 );
        add_filter('ds_dsproduct__tpl_name', [$this,'ds_dsproduct__tpl_name'], 0, 2 );
    }
    
	public static function init() {
		if ( ! self::$initiated ) {
			self::_init_hooks();
		}

//		if ( isset( $_POST['action'] ) && $_POST['action'] == 'enter-key' ) {
//			self::enter_api_key();
//		}
//
//		if ( ! empty( $_GET['akismet_comment_form_privacy_notice'] ) && empty( $_GET['settings-updated']) ) {
//			self::set_form_privacy_notice_option( $_GET['akismet_comment_form_privacy_notice'] );
//		}
	}

	public static function _init_hooks() {
        $DShop = new DShop();
		self::$instance = $DShop;
		self::$initiated = true;
        $DShop->initDB();
//        add_action( 'after_setup_theme', 'lend_setup' );
        add_filter('the_content', [ 'DShop', '_content']);
        // safe code info of product for create order item
//        $added = $this->addProdItem($pid,$added);
        
        // safe code info of product for create order item
//        add_action('ds__add_to_cart', [ 'DShop', '_addXmlAnaliseProdItem'],10,2);
        add_filter('ds__add_to_cart', [ 'DShop', '_addXmlAnaliseProdItem'],10,2);
        add_filter('ds_admin_DShopOrderItem_info', [ 'DShop', '_admin_DShopOrderItem_info'],10,2); // 
//        $added = apply_filters('ds__add_to_cart',$pid,$added);
//        add_action( 'widgets_init', ['Allegro','register_wgts_area'],1  );
        add_filter('dso_create_order_meta__status', [$DShop,'dso_create_order_meta__status'], 5, 2 ); // defoult status after creating
	}
    public function dso_create_order_meta__status($status='',$dsobject=null){
        $status='created';
        $status='checked';
        return $status;
    }
    public function __init(){
    }
    /*
     * регистрирует область вывода
     * сайдбар фильтра, категорий
     */
    public static function register_wgts_area(){
//        static $i = 0;
        register_sidebar( array(
//            'name'          => sprintf(__('Sidebar %d'), $i ),
            'name'          => 'Шапка, Кнопка корзины',
            'id'            => "sbar-right-cart-btn",
            'description'   => 'Кнопка корзины',
            'class'         => 'dshop_cart_btn_area',
            'before_widget' => '<div class="row"><div id="%1$s" class="widget %2$s col-md-12">',
            'after_widget'  => "</div></div>\n",
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => "</h2>\n",
//            'before_widget' => '<div class="row"><div id="%1$s" class="widget %2$s col-md-12">',
//            'after_widget'  => "</div></div>\n",
//            'before_title'  => '<h2 class="widgettitle">',
//            'after_title'   => "</h2>\n",
        ) );
//        $i++;
    }
    public function wgt_cart_btn($echo=false){
        
        $prod_debug=get_option('prod_parser_debug','');
        if(current_user_can('manage_options'))// manage_options - права администратора
            $this->prod_debug = $prod_debug;
        
        $cat = '';
//        $catId = $this->get('categ');
//        $this->cat=$this->get('categ',$this->cat); // ?
//        $this->getCategories($this->cat);
//        $this->getCategories($catId);
//        $cat = $this->getCat() ;
        ob_start();
        $name = 'template-parts/widget/tpl.dshop';
        $slag = 'cart.btn';
        get_template_part( $name,$slag);
        $tpl = ob_get_clean();
        $cat = $tpl;
        if($echo)
            echo $cat;
        return $cat;
    }
    public static function _content($content=''){
        if(strpos($content,'__dshop_content__')>0)
            return self::$instance->content($content);
        else
            return $content;
	}
    public $poduct='';
    public function getCI($content=''){
        return $content;
    }
    public function content($content=''){
//        return $content;
        
        $_debug=get_option('dshop_debug','');
        if(current_user_can('manage_options'))// manage_options - права администратора
            $this->_debug = $_debug;
        
        $r=[];
        global $post;
        $r['__content__']='go';
        $r['__dshop_content__']='';
        
        $data = [];
//        $this->poduct=$this->get('poduct',$this->poduct);
        $this->poduct=$this->get('pid',$this->poduct);
        $prodId=$this->poduct;
        $r['__dshop_content__']=$prodId;
        $r['__dshop_content__']='';
        if($prodId){
    
            $ds_item_add_count_def = get_option('ds_item_add_count_def',1);
            $ds_item_add_min = get_option('ds_item_add_min',1);
            $ds_item_add_max = get_option('ds_item_add_max',1);
            $ds_cart_item_max = get_option('ds_cart_item_max',1);
            $ds_cart_items_max = get_option('ds_cart_items_max',1000);
    
            $data['[__prod_ID__]'] = $prodId ;
            
            $fn=0;
            $data['[__prod_title__]'] = $this->getCI(++$fn) ;
                $data['[__prod_cost__]'] = $this->getCI(++$fn) ;
                $data['[__prod_ouner__]'] = $this->getCI(++$fn) ;
                $data['[__prod_gall__]'] = $this->getCI(++$fn) ;
                $data['[__prod_properties__]'] = $this->tag($this->getCI(++$fn),'','ul') ;
                $data['[__prod_desc__]'] = $this->getCI(++$fn) ;
                $data['[__prod_cost_delivery__]'] = $this->getCI(++$fn) ;
                $data['[__prod_numer__]'] = $this->getCI(++$fn) ;
                $data['[__prod_quantity__]'] = $this->getCI(++$fn) ;

                $data['[__prod_rating__]'] = $this->getCI(++$fn) ;
                $data['[__prod_cost2__]'] = $this->getCI(++$fn) ;

                $max = 1000;
                $min = 1;
                $max = $ds_item_add_max;
                $min = $ds_item_add_min;
                $data['[__prod_count_def__]'] = $ds_item_add_count_def ;
                $data['[__prod_count_max__]'] = $max ;
                $data['[__prod_count_min__]'] = $min ;
                $name = trim(strip_tags($data['[__prod_title__]']));
//                $url = $this->page_url;
                $cost = $data['[__prod_cost__]'];
                $deliv = $data['[__prod_cost_delivery__]'];
                
                $cost = strtr($cost,',','.');
                $deliv = strtr($deliv,',','.');
                $perc = get_option('delivery_percent',0);
                $cours = get_option('course_zl_rub',1);
                $currency_short = get_option('currency_short','zl');
                
                $cost = strtr($cost,',','.');
                $deliv = strtr($deliv,',','.');
                $cost = $cost * $cours;
                $deliv = $deliv * $cours;
                $deliv2 = ($deliv/100) * $perc;
                
                $data['[__prod_cost__]'] = $cost;
                $data['[__prod_cost_delivery__]'] = $deliv;
                $data['[__prod_cost_delivery_2__]'] = $deliv2;
                
        $data['product-exists']=false;
                
    $oargs = [
//    	'ID' => $oid,
//        'author'  => $user->ID,
    	'numberposts' => 1000,
    	'offset'    => 0,
    //	'numberposts' => $count,
    //	'offset'    => $offset,
    //	'category'    => 0,
        'orderby'     => 'date',
        'order'       => 'DESC',
//    	'include'     => [$oid],
    //	'exclude'     => array(),
        'meta_key'    => 'dsp_pid',
        'meta_value'  => $prodId,
        'post_type'   => 'dsproduct',
        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
    ];
$query = new WP_Query( $oargs );
$count =  $query->found_posts;
if($count>0 ){
    ob_start();
    $posts = get_posts( $oargs );
    $err = ob_get_clean();
    wp_reset_query();
    
    $DShopProduct = new DShopProduct();
    $fls = $DShopProduct->meta_fields;
        
        $nm = 'dsp_';
        $fields=[];
        $fields[$nm.'title']='Название товара';
        $fields[$nm.'short']='Краткое описание';
        $fields[$nm.'desc']='Расширенное описание';
        $fields[$nm.'cost']='стоимость';
        $fields[$nm.'count']='количество';
        $fields[$nm.'max']='Максимум в заказе';
        $fields[$nm.'min']='Минимум в заказе';
        $fields[$nm.'pid']='Id товара';
        $fields[$nm.'code']='Код товара';
    foreach( $posts as $item ){
        
        $data['product-exists']=1;
        $data['post_id']=$item->ID;
        $data['[__prod_title__]'] =  get_post_meta( $item->ID, $nm.'title', true );
        $data['[__prod_short__]'] =  get_post_meta( $item->ID, $nm.'short', true );
        $data['[__prod_desc__]'] =  get_post_meta( $item->ID, $nm.'desc', true );
        $data['[__prod_cost__]'] =  get_post_meta( $item->ID, $nm.'cost', true );
        $data['[__prod_quantity__]'] =  get_post_meta( $item->ID, $nm.'count', true );
        $data['[__prod_count_max__]'] =  get_post_meta( $item->ID, $nm.'max', true );
        $data['[__prod_count_min__]'] =  get_post_meta( $item->ID, $nm.'min', true );
        $data['[__prod_code__]'] =  get_post_meta( $item->ID, $nm.'code', true );
        $data['[__prod_id__]'] =  get_post_meta( $item->ID, $nm.'pid', true );
        $data['[__prod_properties__]'] = '';
        $data['[__prod_properties__]'] = apply_filters('ds_dsproduct_data_properties', $data['[__prod_properties__]'], $item->ID);
        
        
//        $opts[] = get_post_meta( $item->ID, $nm.'opt_'.$opt_num++, true );
        
//        $dsoi_orderId_ =  get_post_meta( $item->ID, 'dsp_orderId', true );
//        $dsoi_orderId_ =  get_post_meta( $item->ID, 'dsp_orderId', true );
//        $dsoi_orderId_ =  get_post_meta( $item->ID, 'dsp_orderId', true );
    }
}
//        $data['product-exists']=1;
//        $data['[__prod_desc__]'] = print_r($data,1);
                
                if($ds_cart_item_max < $data['[__prod_count_max__]'] ) $data['[__prod_count_max__]'] = $ds_cart_item_max;
                if($data['[__prod_count_max__]'] < $data['[__prod_count_def__]'] ) $data['[__prod_count_def__]'] = $data['[__prod_count_max__]'];
                if($data['[__prod_count_min__]'] > $data['[__prod_count_def__]'] ) $data['[__prod_count_def__]'] = $data['[__prod_count_min__]'];
                
                $data = apply_filters('ds_dsproduct_data_placeholders', $data, $prodId);
        
                if(strlen( $data['[__prod_cost__]'])>0)
                     $data['[__prod_cost__]'] = 'Цена: '. $data['[__prod_cost__]'].' '.$currency_short ;
                
                if(strlen( $data['[__prod_cost_delivery__]'])>0
                        && ceil($data['[__prod_cost_delivery__]'])>0)
                     $data['[__prod_cost_delivery__]'] = 'Доставка: '. $data['[__prod_cost_delivery__]'].' '.$currency_short ;
                else
                     $data['[__prod_cost_delivery__]'] = 'Доставка: Требует уточнения.' ;
                
                if(strlen( $data['[__prod_cost_delivery_2__]'])>0
                        && ceil($data['[__prod_cost_delivery_2__]'])>0)
                     $data['[__prod_cost_delivery_2__]'] = 'Доставка на склад: '. $data['[__prod_cost_delivery_2__]'].' '.$currency_short ;
                else
                     $data['[__prod_cost_delivery_2__]'] = 'Доставка на склад: Требует уточнения.' ;
                
                $data['[__prod_quantity__]'] = apply_filters('ds_dsproduct_quantity', $data['[__prod_quantity__]'], $prodId);
                if(strlen( $data['[__prod_quantity__]'])>0)
                     $data['[__prod_quantity__]'] = 'Количество: '. $data['[__prod_quantity__]'] ;
                
                
                
                $data = apply_filters('ds_dsproduct_data_placeholders_second', $data, $prodId);
                $product_tpl_name  = 'shop-product';
                $product_tpl_name = apply_filters('ds_dsproduct__tpl_name', $product_tpl_name, $prodId);
                
                $pbsn = 'Добавить в корзину';
                $data['[__prod_bnt_addtocart_name__]'] = apply_filters('ds_dsproduct_btn_addtocart_name', $pbsn);
                $pbsn = 'Купить сейчас';
                $data['[__prod_bnt_submit_name__]'] = apply_filters('ds_dsproduct_btn_subm_name', $pbsn);
                
                $data['[__addtocart_disable__]'] = '';
                $data['[__submit_disable__]'] = '';
//                $data['[__prod_count_def__]'] = 1;
                
        //        $tpl_cart_item = dshop::_get_tpl('template-parts/page/tpl.dshop-cart','item',$rep);
        //        $tpl_cart_item_noedit = dshop::_get_tpl('template-parts/page/tpl.dshop-cart','item-no-edit',$rep);
        
                $product_count_edit = '';
                if($data['[__prod_count_min__]'] == $data['[__prod_count_max__]'] )
                    $product_count_edit = 'noedit';
                $data['[__item_count__]']=$this->get_tpl('template-parts/dshop/tpl.page-shop-product-count', $product_count_edit,$data);
                
                $no_change = false;
                if(isset( $_SESSION['ds_cart'][$prodId] ) && $_SESSION['ds_cart'][$prodId] >= $ds_cart_item_max)
                    $no_change = true;
                if($no_change){
                    $data['[__prod_count_def__]'] = 0;
                    $data['[__addtocart_disable__]'] = 'disabled';
                    $data['[__submit_disable__]'] = 'disabled';
                    $data['[__item_count__]'] = '';
                }
        
                
                
                
                $data['[__admin_options__]'] = '';
                if ( current_user_can( 'manage_options' ) ) {
                    $data['[__admin_options__]'] = '';
                    $adm_opts = [];
//                    http://analizysl.com/wp-admin/post-new.php?post_type=dsproduct
//                    $adm_opts[] = $this->a('Создать','/shop/',['pid'=>$prodId],['class'=>'btn btn-primary ml-1'],'#create');
                    
                    
                    if( $data['product-exists']){
//                        $data['post_id']
                        $url = get_edit_post_link($data['post_id']);
                        $q =[];
                        $q['redirect_to']=urlencode(get_the_permalink( get_option('ds_id_page_item') ).'?pid='.$prodId);
//                        add_log($q);
                        $adm_opts[] = $this->a('Изменить',$url,$q,['class'=>'btn btn-primary ml-1 text-white']);
                    }else{
                        $adm_opts[] = $this->a('Создать','/wp-admin/post-new.php',['post_type'=>'dsproduct','pid'=>$prodId],['class'=>'btn btn-primary ml-1 text-white'],'');
                    }
                    
                    $data['[__admin_options__]'] = implode('',$adm_opts);
                    $data['[__admin_options__]']=$this->get_tpl('template-parts/dshop/tpl.page', $product_tpl_name.'-adm-opts',$data);
                }
                
//            $data['[__product__]']=$this->get_tpl('template-parts/page/tpl.page', 'shop-product',$data);
//            $r['__dshop_content__']=$this->get_tpl('template-parts/page/tpl.page', 'shop',$data);
//        $states = apply_filters('ds_dsorder_post_display_meta_box_order__out_status', $states, $dsorder, $_this=null);
            $r['__dshop_content__']=$this->get_tpl('template-parts/dshop/tpl.page', $product_tpl_name,$data);
        }
        $content = strtr($content,$r);
        $out='';
//        if($this->_debug)
//            $content.=$this->debug_content();
        $out = $out.$content;
        $out = do_shortcode( $out );
        return $out;
//        return $form.$content;
//        return $content;
    }
    public function ds_get_postid_by_pid($prodId=0){
        
        $oargs = [
    //    	'ID' => $oid,
    //        'author'  => $user->ID,
            'numberposts' => 1000,
            'offset'    => 0,
        //	'numberposts' => $count,
        //	'offset'    => $offset,
        //	'category'    => 0,
            'orderby'     => 'date',
            'order'       => 'DESC',
    //    	'include'     => [$oid],
        //	'exclude'     => array(),
            'meta_key'    => 'dsp_pid',
            'meta_value'  => $prodId,
            'post_type'   => 'dsproduct',
            'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
        ];
        $query = new WP_Query( $oargs );
        $count =  $query->found_posts;
        if($count>0 ){
            ob_start();
            $posts = get_posts( $oargs );
            $err = ob_get_clean();
            wp_reset_query();

//            $DShopProduct = new DShopProduct();
//            $fls = $DShopProduct->meta_fields;

//                $nm = 'dsp_';
//                $fields=[];
//                $fields[$nm.'title']='Название товара';
//                $fields[$nm.'short']='Краткое описание';
//                $fields[$nm.'desc']='Расширенное описание';
//                $fields[$nm.'cost']='стоимость';
//                $fields[$nm.'count']='количество';
//                $fields[$nm.'max']='Максимум в заказе';
//                $fields[$nm.'min']='Минимум в заказе';
//                $fields[$nm.'pid']='Id товара';
//                $fields[$nm.'code']='Код товара';
            foreach( $posts as $item ){
                return $item->ID;
//                $data['product-exists']=1;
//                $data['post_id']=$item->ID;
            }
        }
        return false;
    }
    
    public function ds_dsproduct_data_placeholders($data, $prodId){
        return $data;
    }
    public function ds_dsproduct_data_placeholders_second($data, $prodId){
//        $data['product-exists']=false;
        return $data;
    }
    public function ds_dsproduct__tpl_name($product_tpl_name, $prodId){
        return $product_tpl_name;
    }
    public static function _get_tpl($name,$slag='',$rep=[]){
        self::init();
        return self::$instance->get_tpl($name,$slag,$rep);
    }
    /*
    public $tpls=[];
    public function get_tpl($name,$slag='',$rep=[]){
        if(isset($this->tpls[$name])&&isset($this->tpls[$name][$slag])){
            $tpl = $this->tpls[$name][$slag];
        }else{
            ob_start();
                get_template_part( $name,$slag);
            $tpl = ob_get_clean();
//            echo $tpl;
            $this->tpls[$name][$slag] = $tpl;
        }
//        echo $this->pre($rep);
//        echo $this->pre($this->tpls);
        return strtr($tpl,$rep);
    }/**/
    
    public function getpost($method,$f=false,$d='',$type=FILTER_SANITIZE_STRING){
        global $inputs ;
        $inputs = [];
        
        if($f===false)return $d;
//        $out=$d;
//        if(isset($_GET[$f]))$out=$_GET[$f];
//        return $out;
        
        if (!filter_has_var($method, $f)) return $d;
        $opt = array('default' => $d);
        $inputs_[$f] = array('filter'=>$type, 'options' => $opt);
//        echo $this->pre($inputs_);
        $inputs = filter_input_array($method,$inputs_);
        if($inputs[$f] === null)$inputs[$f]=$d;
        if($inputs[$f] === false)$inputs[$f]=$d;
        if(strlen($inputs[$f])==0)$inputs[$f]=$d;
        return $inputs[$f];
    }
    public function get($f=false,$d='',$type=FILTER_SANITIZE_STRING){
        return $this->getpost(INPUT_GET,$f,$d,$type);
    }
    public function post($f=false,$d='',$type=FILTER_SANITIZE_STRING){
        return $this->getpost(INPUT_POST,$f,$d,$type);
    }
    function lend_form_process(){
        global $def_args,$def_country;
        global $inputs ;
        $inputs = [];
    //$def_country='MX';

        $opt = array('default' => NULL);
        $opt = array('default' => '');
        $opt_slag = array('default' => $def_country);
        $inputs_['form-type'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);
        $inputs_['form-slag'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt_slag);
        $inputs_['f_name'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);
    //    $inputs_['org_email'] = array('filter'=>FILTER_VALIDATE_EMAIL, 'options' => $opt);
        $inputs_['f_phone'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);
        $inputs_['country_code'] = array('filter'=>FILTER_SANITIZE_STRING, 'options' => $opt);

        $inputs = filter_input_array(INPUT_POST,$inputs_);
        if(strlen($inputs['form-type'])>0){
            switch($inputs['form-type']){
                case 'sample-page':
                case 'front':
                case 'blog1':
                case 'blog2':
                case 'mobile':
                case 'article1':
                case 'pay-request':
    //                add_log('lend_form_process');
                    $res = code_send($inputs);
                    if($res){
                        wp_redirect(esc_url(home_url('/success/')));
                        exit();
                    }
                break;
            }
        }
    }
}