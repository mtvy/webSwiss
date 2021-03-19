<?php

/* 
 * class.DShopWidgetCartBtn
 */

class DShopWidgetCartBtn extends WP_Widget{
    
    // – функция конструктора
    public function __construct()
    {

        parent::__construct(
        // widget ID
        'ds_cartbtn_widget',
        // widget name
        __('Виджет Кнопка карзины', ' dshop'),
        // widget description
        array( 'description' => __( 'Кнопка карзины',
                'dshop' ), )
        );
    }
    // – содержит вывод виджета
    public function widget($args, $instance)
    {
//        $title = apply_filters( 'widget_title', $instance['title'] );
//        echo $args['before_widget'];
        
        //if title is present
//        if ( ! empty( $title ) )
//        echo $args['before_title'] . $title . $args['after_title'];
        
        //output
//        echo __( 'Содержимое Виджета Категорий Товаров', 'dshop' );
//dshop
        $alleg = new DShop();
        $alleg->wgt_cart_btn(1);
        
//        echo $args['after_widget'];
    }
    // –  определяет настройки виджета в панели управления WordPress
    public function form($instance)
    {
        if ( isset( $instance[ 'title' ] ) )
        $title = $instance[ 'title' ];
        else
        $title = __( 'Корзина', 'dshop' );
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' );
        ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat"
               id="<?php echo $this->get_field_id( 'title' ); ?>"
               name="<?php echo $this->get_field_name( 'title' ); ?>"
               type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }
    // – обновляет настройки виджета
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = 
                ( ! empty( $new_instance['title'] ) )
                ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
}

/*  ================  */

//function agro_register_widget() {
//    register_widget( 'AllegroWidgetCategories' );
//}
//add_action( 'widgets_init', 'agro_register_widget' ); // widget
