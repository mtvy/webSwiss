/* 
 * prod_getting.js
 */

/*
 * test js
 * 
function abc(){
    console.log(abc);
    console.log(this);
	this.style['background-color'] = '#f00';
  
};
function abc2(){
    console.log(abc2);
    console.log(this);
	this.style['background-color'] = '#f00';
  
};
function abc3(){
    console.log(abc3);
    console.log(this);
	this.style['background-color'] = '#f00';
  
};
let p = document.querySelectorAll('p.abc-p');
console.log('used');
console.log(p.lenght);
p.forEach((el)=>{
    console.log(this);
    el.addEventListener('click',abc,1);
    el.addEventListener('click',abc2,1);
    el.addEventListener('click',abc3,1);
    el.addEventListener('click',abc,1);
});

 document.querySelector('p.abc-p').onclick = abc
 p = document.querySelector('p.abc-p');
p.onclick = abc
 p = document.querySelector('p.abc-p');
p.addEventListener('click',abc,false);

 document.querySelector('p.abc-p').onclick = abc
 p = document.querySelector('p.abc-p');
p.onclick = abc
 p = document.querySelector('p.abc-p');
p.addEventListener('click',abc,false);
/**/

jQuery(function($){

    function addToCart() {
//                    console.log('addToCart')
//		alert('Если это работает, уже неплохо');
//		alert(ajax_url);

//        var id=this.id;
//        var act=$(this).data('act');
        var act='add_c';
        
//        var target=$(this).data('target');
//        var offset=$(this).data('offset');
//        var pid=$(this).data('pid');
//        var count=parseInt($(this).data('count'));


        var bt = document.querySelector('#add-to-cart-button');
        if(bt){
            var pid=$('#pr_pid').val();
            var count=parseInt($('#pr_cou').val());
            
        }else{
            var pid=$(this).data('id');
            var count=1;
        }
        
        var data = {
            action:'dscart',
            isajax: 1,
            act: act,
            pid: pid,
            count: count
        };
        if(ajax_url){
            $.ajax({
                url: ajax_url,//'/wp-admin/admin-ajax.php',
                type: 'POST',
                data: data, // можно также передать в виде объекта
                beforeSend: function( xhr ) {
//                    $('#'+id).text('Загрузка, 5 сек...');	
                },
                success: function( data ) {
                    console.log(data)
                    $('#count-in-cart').text(data.count);
                    if (data.act !== undefined) {
                        switch(data.act){
                            case 'block_button':
                                var btadd = document.querySelector('#add-to-cart-button');
                                var bts = document.querySelector('#add-to-cart-submit-button');
                                if(btadd){
                                    bts.setAttribute("disabled", "disabled");
                                }
                                if(btadd){
                                    btadd.setAttribute("disabled", "disabled");
//                                    var pid=$('#pr_pid').val();
//                                    var count=parseInt($('#pr_cou').val());

                                }else{
                                    var pid=$(this).data('id');
                                    var count=1;
                                }
                                break;
                            case 'no':
                                break;
                        }
                    }

                    
//                    $('#'+id).text('Ещё');
////                    $('#'+id).insertBefore(data);
////                    $('#'+id).before(data.data);
//                    console.log('#'+id,'id');
//                    console.log('#'+target,'target');
//                    $('#'+target).before(data.data);
//                    var newoffset = (parseInt(data.offset) * count)+count;
//                    var newoffset = (parseInt(data.offset) )+count;
//                    console.log(newoffset,'newoffset');
//                    $('#'+id).data('offset',newoffset);
//                    if(data.limit_end==1)$('#'+id).remove();
////                    alert( data );
//                    $('a.link-to-users').on('click', function (e) {
//                        e.preventDefault()
//                        $('#users-tab-btn').tab('show')
//                        return false;
//                    })
                },
                error: function( data ) {
                    console.log(data)
//                    $('#'+id).text('Ещё');
//                    alert( data );
                }
            });
            // если элемент – ссылка, то не забываем:
            // return false;
        }
        return false;
    };
var bt = document.querySelector('#add-to-cart-button');
if(bt){
//    bt.onclick = addToCart ;
}
var bts = document.querySelectorAll('.add-to-cart-button');
//console.log(bts);
for (var i = 0; i < bts.length; i++) {
//  alert( bts[i].innerHTML ); // "тест", "пройден"
//    bts[i].onclick = addToCart ;
}
//    $('.btn_cou_minus_cart').on('click', function (e) {
//        e.preventDefault()
//        var pid=$(this).data('pid');
//        var min=$(this).data('min');
//        var cou = parseInt($('.pr_cou-'+pid).val());
//        var min = parseInt(min);
//        cou=cou-1;
//        if(cou<min)cou=min
//        $('.pr_cou-'+pid).val(cou)
//        return false;
//    })
//    $('#order_field_email').blur(function(){
//        var em = $(this).val();
//        var lgd = $(this).data('logged');
//        console.log(em);
//        console.log(lgd);
////        var o = order.b;
//        if(lgd == 0 && em.length>6)
//            order.check_email(em);
//    });
    
    var mn_find = document.querySelectorAll('.mn-find');
    //console.log(bts);
    for (var i = 0; i < mn_find.length; i++) {
    //  alert( bts[i].innerHTML ); // "тест", "пройден"
        mn_find[i].onkeyup = materialFind ;
    }
    function materialFind(){
    //    var name = document.getElementById('mnf-name').value;
    //    var categ = document.getElementById('mnf-categ').value;
    //    var code = document.getElementById('mnf-code').value;
    //    var manuf = document.getElementById('mnf-manuf').value;
    //    console.log(name,categ,code,manuf);
        order.find_material_names();
    }
    
    var whm_create = document.querySelector('#whm-create');
    if(whm_create){
        whm_create.onclick = createMaterial ;
    }
    function createMaterial(){
        order.create_material();
        return false;
    }

    var order = {
    //class order {
        action: 'ml_warehouse', // method
        ajax_url: ajax_url,
        act:null, // whot to do
        data: {}, // args
        r_succ:null, // whot to do
        r_err:null, // whot to do
        r_beforeSend: null,

    //    constructor (name) {
    //      this.name = name;
    //    },
        init: function(){

        },
        create_material: function(em){
            this.act='add_material'; // find_material_names
            var name = document.querySelector('.name_tool:checked');
            if(name)name = name.value;
//            var name = document.getElementById('mnf-name').value;
            var categ = document.getElementById('mnf-categ').value;
            var code = document.getElementById('mnf-code').value;
            var manuf = document.getElementById('mnf-manuf').value;

            console.log( 'create_material', 'name', name );
//        SET
//    `house_id` = '0',
//    `group_id` = '0',
//    `name_id` = '0',
//    `delivery_date` = '',
//    `write_off` = '0',
//    `expiry_date` = '',
//    `open_expiry_date` = '',
//    `measurement` = '0',
//    `measurement_item` = '0',
//    `mesurement_pack` = '0',
//    `measurement_box` = '0',
//    `count` = '0',
//    `count_item` = '0',
//    `count_pack` = '0',
//    `count_box` = '0',
//    `cost_item` = '0',
//    `cost_pack` = '0',
//    `stillage` = '0',
//    `board` = '',
//    `pack_width` = '0',
//    `pack_height` = '0',
//    `pach_length` = '0'

            var data_item_names = [];
            data_item_names[data_item_names.length] = 'house_id';
            data_item_names[data_item_names.length] = 'group_id';
//            data_item_names[data_item_names.length] = 'name_id';
            data_item_names[data_item_names.length] = 'expiry_date';
            data_item_names[data_item_names.length] = 'open_expiry_date';
            data_item_names[data_item_names.length] = 'measurement';
            data_item_names[data_item_names.length] = 'measurement_item';
            data_item_names[data_item_names.length] = 'mesurement_pack';
            data_item_names[data_item_names.length] = 'measurement_box';
            data_item_names[data_item_names.length] = 'count';
            data_item_names[data_item_names.length] = 'count_item';
            data_item_names[data_item_names.length] = 'count_pack';
            data_item_names[data_item_names.length] = 'count_box';
            data_item_names[data_item_names.length] = 'cost_item';
            data_item_names[data_item_names.length] = 'cost_pack';
            data_item_names[data_item_names.length] = 'stillage';
            data_item_names[data_item_names.length] = 'board';
            data_item_names[data_item_names.length] = 'pack_width';
            data_item_names[data_item_names.length] = 'pack_height';
            data_item_names[data_item_names.length] = 'pach_length';

            var data = {
                action: this.action,
                isajax: 1,
                act: this.act,
                name_id: name
            };
            
            var item_name;
            var item_val;
            for (var i = 0; i < data_item_names.length; i++) {
                item_name = data_item_names[i];
//                item_val = document.querySelector('whm-'+item_name).value;
                item_val = document.getElementById('whm-'+item_name);
                if(item_val)data[item_name] = item_val.value;
            }

            this.data = data;
            this.ajax_url = ajax_url;
            this.r_succ = this.create_material_success;
            this.r_err = this.error;
            this.r_beforeSend = this.beforeSend;
    //        console.log( 'ajax_url',ajax_url );
            this.get();
        },
        create_material_success: function(data){
            console.log( data );
//            var found = document.getElementById('mnf-found');
//            order.show_res.bind(order,data.res)();
            order.show_mess.bind(order,data.mess)();
    //        order.show_mess.bind(order,data.mess)();
        },
        find_material_names: function(em){
            this.act='find_material_names'; // find_material_names
            var name = document.getElementById('mnf-name').value;
            var categ = document.getElementById('mnf-categ').value;
            var code = document.getElementById('mnf-code').value;
            var manuf = document.getElementById('mnf-manuf').value;

            var data = {
                action: this.action,
                isajax: 1,
                act: this.act,
                name: name,
                categ: categ,
                code: code,
                manuf: manuf
            };

            this.data = data;
            this.ajax_url = ajax_url;
            this.r_succ = this.find_material_names_success;
            this.r_err = this.error;
            this.r_beforeSend = this.beforeSend;
    //        console.log( 'ajax_url',ajax_url );
            this.get();
        },
        find_material_names_success: function(data){
            console.log( data );
            var found = document.getElementById('mnf-found');
            order.show_res.bind(order,data.res)();
            order.show_mess.bind(order,data.mess)();
    //        order.show_mess.bind(order,data.mess)();
        },
        check_email: function(em){
            this.act='order_ch_mail_ex'; // check email exists

            var data = {
                action: this.action,
                isajax: 1,
                act: this.act,
                email: em
            };

            this.data = data;
            this.ajax_url = ajax_url;
            this.r_succ = this.check_email_success;
            this.r_err = this.error;
            this.r_beforeSend = this.beforeSend;
            console.log( 'ajax_url',ajax_url );
            this.get();
        },
        check_email_success: function(data){
            console.log( data );
            order.show_mess.bind(order,data.mess)();
        },
    //    mess_wrapp_id: 'info_messages_wrap',
        mess_wrapp_id: 'info_messages_wrap_found',
        res_wrapp_id: 'mnf-found',
        show_res: function (mess){
    //        console.log( mess );
            $('#'+this.res_wrapp_id).html('');
            for(var i = 0; i<mess.length;i++){
                this.build_res(mess[i]);
            }
            this.init_name_tools();
        },
        init_name_tools: function(){
            var name_tools = document.querySelectorAll('.name_tool');
            //console.log(bts);
            for (var i = 0; i < name_tools.length; i++) {
                var e = name_tools[i];
    //            console.log(e);
            //  alert( bts[i].innerHTML ); // "тест", "пройден"
                e.onchange = this.init_name_tool.bind(this,e) ;
            }
        },
        init_name_tool: function(el){
            var name_tools = document.querySelectorAll('.name_wrupp');
            var e;
    //        console.log(el);
            for (var i = 0; i < name_tools.length; i++) {
            //  alert( bts[i].innerHTML ); // "тест", "пройден"
                e = name_tools[i];

    //            var reg = /\bbg-danger\b/g;
                var reg = new RegExp('\\b'+'text-white bg-danger'+'\\b','g');
                e.className = e.className.replace(reg, "");
            }
    //        el.closest('div').closest('div').closest('label').className += ' bg-danger';
            el.closest('.row').className += ' text-white bg-danger';
        },
        classToogle: function (el,classname) {
            if (el.classList) { 
                el.classList.toggle(classname);
            } else {
                // For IE9
                var classes = el.className.split(" ");
                var i = classes.indexOf(classname);

                if (i >= 0) 
                    classes.splice(i, 1);
                else 
                    classes.push(classname);
                el.className = classes.join(" "); 
            }
        },
        classRemove: function (el,classname) {
            var reg = new RegExp('\\b'+classname+'\\b','g');
            el.className = el.className.replace(reg, "");
            el.className = el.className.replace('  ', ' ');
        },
        classAdd: function (el,classname) {
            var  arr;
            arr = el.className.split(" ");
            if (arr.indexOf(classname) == -1) {
              el.className += " " + classname;
            }
        },

    //    res_names: ['catalog','category','factory','title'],
        res_names: ['title','category','catalog','factory'],
        res_names_class: {'id':'col-1','title':'col-3','category':'col-3','catalog':'col-3','factory':'col-3'},
        build_res: function (mess){
            var mw= document.createElement('label')
            mw.className='row mb-0 name_wrupp';

    //            var name = 'id';
    //            var mw2= document.createElement('div')
    //            mw2.className=this.res_names_class[name];
    //            var m = document.createTextNode(mess[name]);
    //            mw2.appendChild(m);
    //            mw.appendChild(mw2);

            var input = false;
            for(var i = 0; i<this.res_names.length;i++){
                name = this.res_names[i];
                input = false;
    //        console.log( mess[this.res_names[i]] );
                var mw2= document.createElement('div')
                mw2.className=this.res_names_class[name];
                var m = document.createTextNode(mess[name]);
                if(name == 'title'){
                    var label = document.createElement('div');
                    label.className = '-form-control';

                    input = document.createElement('input');
                    input.name = 'name_id';
                    input.type = 'radio';
                    input.className = 'm-2 name_tool';
                    input.value = mess['id'];
                    label.appendChild(input);

                    var t = document.createTextNode(' [' + mess['id'] + '] ');
                    label.appendChild(t);

                    label.appendChild(m);

                    m = label;
                }
                mw2.appendChild(m);
                mw.appendChild(mw2);
            }
    //        var w = document.getElementById(this.mess_wrapp_id);
    //        w.appendChild(mw);
            $('#'+this.res_wrapp_id).append(mw);
    //        this.remove_mess(mw);
        },
        show_mess: function (mess){
            $('#'+this.mess_wrapp_id).html('');
            for(var i = 0; i<mess.length;i++){
                this.build_mess(mess[i]);
            }
        },
        build_mess: function (mess){
            var mw= document.createElement('div')
            mw.className='log_mess alert alert-primary';
//            var m = document.createTextNode(mess);
//            mw.appendChild(m);
            var m= document.createElement('div')
            m.innerHTML = mess;
//            m.innerText(mess);
            mw.appendChild(m);
                        console.log( m );
            var w = document.getElementById(this.mess_wrapp_id);
    //        w.appendChild(mw);
            $('#'+this.mess_wrapp_id).append(mw);
            this.remove_mess(mw);
        },
        messages: [],
        rem_mess_delay: 60000,
        remove_mess:function(mess){
            setTimeout(mess.remove.bind(mess),this.rem_mess_delay);
        },
        clear_mess:function(){
            this.remove();
        },
        get: function(){
            $.ajax({
                url: this.ajax_url,//'/wp-admin/admin-ajax.php',
                type: 'POST',
                data: this.data, // можно также передать в виде объекта
                beforeSend: this.r_beforeSend,
                success: this.r_succ,
                error: this.r_err
            });
        },
        beforeSend: function( xhr ) {
    //                    $('#'+id).text('Загрузка, 5 сек...');	
        },
        error: function( data ) {
    //                    $('#'+id).text('Ещё');
    //                    console.log( data );
        }
    }
});