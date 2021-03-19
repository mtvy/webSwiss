/* 
 * prod_shipment.js
 */



    var mywindow;
    function bp(e){
    mywindow.print();
//    el.width=el;
//    mywindow.close();
    }
    function barcode_print(code)
    {
    //    var elem = this.id;
        var elem = 'ml_bar_'+code;

        console.log(elem);
        console.log(code);
        // var
        mywindow = window.open('', 'PRINT', 'height=700,width=1000');
    //    mywindow = window.open('', 'PRINT', 'height=100%,width=100%');

    //    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
        mywindow.document.write('<html><head><title>' + code  + '</title>');
        mywindow.document.write('</head><body >');
    //    mywindow.document.write('<h1>' + document.title  + '</h1>');
        var el = document.getElementById(elem)
//        var size = el.width;
//        el.style.width='100%';
    //    mywindow.document.write(document.getElementById(elem).parentElement.innerHTML);
        mywindow.document.write(el.outerHTML);
        mywindow.document.write('</body></html>');
        var el = mywindow.document.getElementById(elem)
//        var size = el.width;
        el.style.width='100%';

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

    //sleep(1);
    //var ms = 1000;
    //ms += new Date().getTime();
    //while (new Date() < ms){}

    //    mywindow.print();
    //    el.width=el;
    //    mywindow.close();

    setTimeout(bp, 3000);

        return true;
    }
jQuery(function () {
    jQuery('[data-toggle="popover"]').popover({
      trigger: 'hover'
    })
})
jQuery(function($){
    
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
        order.find_materials();
    }
    
    var whm_create = document.querySelector('#whm-create');
    if(whm_create){
        whm_create.onclick = createMaterial ;
    }
    function createMaterial(){
        order.create_material();
        return false;
    }

    
    var whm_create = document.querySelector('#whwb-add-material');
    if(whm_create){
        whm_create.onclick = addMaterialToWeybill ;
    }
    function addMaterialToWeybill(){
        order.add_material_to_waybill();
        return false;
    }

    var whm_create = document.querySelector('#whwb-clear');
    if(whm_create){
        whm_create.onclick = clearWeybill ;
    }
    function clearWeybill(){
        order.clear_weybill();
        return false;
    }

    var whm_create = document.querySelector('#whwb-create');
    if(whm_create){
        whm_create.onclick = createWeybill ;
    }
    function createWeybill(){
        order.create_weybill();
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
        init_weybill: function(){
            this.act='get_weybill_items'; // find_materials

            var data = {
                action: this.action,
                isajax: 1,
                act: this.act
            };

            this.data = data;
            this.ajax_url = ajax_url;
            this.r_succ = this.get_weybill_items_success;
            this.r_err = this.error;
            this.r_beforeSend = this.beforeSend;
    //        console.log( 'ajax_url',ajax_url );
            this.get();
            return false;
        },
        clear_weybill: function(el){
            this.act='clear_weybill'; // find_materials

            var data = {
                action: this.action,
                isajax: 1,
                act: this.act,
                conferm: 'ok'
            };

            this.data = data;
            this.ajax_url = ajax_url;
            this.r_succ = this.clear_weybill_success;
            this.r_err = this.error;
            this.r_beforeSend = this.beforeSend;
    //        console.log( 'ajax_url',ajax_url );
            this.get();
            return false;
        },
        clear_weybill_item: function(id){
            this.act='clear_weybill_item'; // find_materials

            var data = {
                action: this.action,
                isajax: 1,
                act: this.act,
                conferm: 'ok',
                id: id
            };

            this.data = data;
            this.ajax_url = ajax_url;
            this.r_succ = this.clear_weybill_item_success;
            this.r_err = this.error;
            this.r_beforeSend = this.beforeSend;
    //        console.log( 'ajax_url',ajax_url );
            this.get();
            return false;
        },
        create_weybill: function(){
            this.act='create_weybill'; // find_materials
            var house_id = document.getElementById('whwb-house_id').value;
            var group_id = document.getElementById('whwb-group_id').value;
            var status = document.getElementById('whwb-status').value;
            var comment = document.getElementById('whwb-comment').value;

            var data = {
                action: this.action,
                isajax: 1,
                act: this.act,
                conferm: 'ok',
                house_id: house_id,
                group_id: group_id,
                status: status,
                comment: comment
            };

            this.data = data;
            this.ajax_url = ajax_url;
            this.r_succ = this.create_weybill_success;
            this.r_err = this.error;
            this.r_beforeSend = this.beforeSend;
    //        console.log( 'ajax_url',ajax_url );
            this.get();
            return false;
        },
        add_material_to_waybill: function(em){
            var name_tools = document.querySelectorAll('.name_tool:checked');
            var waybill_items = {};
//            console.log(name_tools);
            for (var i = 0; i < name_tools.length; i++) {
                var e = name_tools[i].value;
//                console.log(e);
                waybill_items[e]=e;
            //  alert( bts[i].innerHTML ); // "тест", "пройден"
//                e.onchange = this.init_name_tool_radio.bind(this,e) ;
//                e.onchange = this.init_name_tool_check.bind(this,e) ;
            }
//            console.log(waybill_items);
            var size = 0, key;
            for (key in waybill_items) {
                if (waybill_items.hasOwnProperty(key)) size++;
            }
            if(size){
                this.act='add_to_weybill'; // find_materials
                
                var data = {
                    action: this.action,
                    isajax: 1,
                    act: this.act,
                    waybill_items: waybill_items
                };

                this.data = data;
                this.ajax_url = ajax_url;
                this.r_succ = this.add_to_weybill_success;
                this.r_err = this.error;
                this.r_beforeSend = this.beforeSend;
        //        console.log( 'ajax_url',ajax_url );
                this.get();
            }
            return false;
        },
        create_material: function(em){
            this.act='add_material'; // find_materials
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
        get_weybill_items_success: function(data){
//            console.log( data );
//            var found = document.getElementById('mnf-found');
//            order.show_res.bind(order,data.res)();
            order.show_res_weybill.bind(order,data.res)();
            order.show_mess.bind(order,data.mess)();
//            order.show_mess.bind(order,data.mess)();
        },
        add_to_weybill_success: function(data){
//            console.log( data );
//            var found = document.getElementById('mnf-found');
//            order.show_res.bind(order,data.res)();
            order.show_res_weybill.bind(order,data.res)();
            order.show_mess.bind(order,data.mess)();
//            order.show_mess.bind(order,data.mess)();
        },
        clear_weybill_success: function(data){
//            console.log( data );
//            var found = document.getElementById('mnf-found');
//            order.show_res.bind(order,data.res)();
            order.show_res_weybill.bind(order,data.res)();
            order.show_mess.bind(order,data.mess)();
//            order.show_mess.bind(order,data.mess)();
        },
        clear_weybill_item_success: function(data){
//            console.log( data );
//            var found = document.getElementById('mnf-found');
//            order.show_res.bind(order,data.res)();
            order.show_res_weybill.bind(order,data.res)();
            order.show_mess.bind(order,data.mess)();
//            order.show_mess.bind(order,data.mess)();
        },
        create_weybill_success: function(data){
//            console.log( data );
//            var found = document.getElementById('mnf-found');
//            order.show_res.bind(order,data.res)();
            order.show_mess.bind(order,data.mess)();
    //        order.show_mess.bind(order,data.mess)();
        },
        create_material_success: function(data){
//            console.log( data );
//            var found = document.getElementById('mnf-found');
//            order.show_res.bind(order,data.res)();
            order.show_mess.bind(order,data.mess)();
    //        order.show_mess.bind(order,data.mess)();
        },
        find_materials: function(em){
            this.act='find_material_items'; // find_materials
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
            this.r_succ = this.find_materials_success;
            this.r_err = this.error;
            this.r_beforeSend = this.beforeSend;
    //        console.log( 'ajax_url',ajax_url );
            this.get();
        },
        find_materials_success: function(data){
//            console.log( data );
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
//            console.log( data );
            order.show_mess.bind(order,data.mess)();
        },
    //    mess_wrapp_id: 'info_messages_wrap',
        weybillitems_wrapp_id: 'weybillitems_added',
        mess_wrapp_id: 'info_messages_wrap_found',
        res_wrapp_id: 'mnf-found',
        show_res_weybill: function (mess){
//            console.log( mess );
//            console.log( mess.length );
            $('#'+this.weybillitems_wrapp_id).html('');
            if(mess)
            for(var i = 0; i<mess.length;i++){
                this.build_weybill_res(mess[i]);
            }
            this.init_weybillitems_tools();
        },
        show_res: function (mess){
    //        console.log( mess );
            $('#'+this.res_wrapp_id).html('');
            if(mess)
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
//                e.onchange = this.init_name_tool_radio.bind(this,e) ;
                e.onchange = this.init_name_tool_check.bind(this,e) ;
            }
        },
        init_weybillitems_tools: function(){
            var name_tools = document.querySelectorAll('.wbi_remove_tool');
            //console.log(bts);
            for (var i = 0; i < name_tools.length; i++) {
                var e = name_tools[i];
                var id = e.dataset.id;
    //            console.log(e);
            //  alert( bts[i].innerHTML ); // "тест", "пройден"
//                e.onchange = this.init_name_tool_radio.bind(this,e) ;
                e.onclick = this.clear_weybill_item.bind(this,id) ;
            }
        },
        init_name_tool_check: function(el){
            var name_tools = document.querySelectorAll('.name_wrupp');
            var e;
    //        console.log(el);
            for (var i = 0; i < name_tools.length; i++) {
            //  alert( bts[i].innerHTML ); // "тест", "пройден"
                e = name_tools[i];

    //            var reg = /\bbg-danger\b/g;
//                var reg = new RegExp('\\b'+'text-white bg-danger'+'\\b','g');
//                e.className = e.className.replace(reg, "");
            }
    //        el.closest('div').closest('div').closest('label').className += ' bg-danger';
            if(el.checked)
                el.closest('.row').className += ' text-white bg-danger';
            else{
                var reg = new RegExp('\\b'+'text-white bg-danger'+'\\b','g');
                el.closest('.row').className = e.className.replace(reg, "");
            }
        },
        init_name_tool_radio: function(el){
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
        res_wbi_names: ['title','category','number','stillage','board'],
        res_wbi_names_class: {'id':'col-1','title':'col-3','category':'col-3','number':'col-2','catalog':'col-2',
            'stillage':'col-2','board':'col-2','factory':'col-3'},
        build_weybill_res: function (mess){
            var mw= document.createElement('label')
            mw.className='row mb-0 name_wrupp';

    //            var name = 'id';
    //            var mw2= document.createElement('div')
    //            mw2.className=this.res_names_class[name];
    //            var m = document.createTextNode(mess[name]);
    //            mw2.appendChild(m);
    //            mw.appendChild(mw2);

            var input = false;
            for(var i = 0; i<this.res_wbi_names.length;i++){
                name = this.res_wbi_names[i];
                input = false;
    //        console.log( mess[this.res_names[i]] );
                var mw2= document.createElement('div')
                mw2.className=this.res_wbi_names_class[name];
                var m = document.createTextNode(mess[name]);
                if(name == 'title'){
                    var label = document.createElement('div');
                    label.className = '-form-control';

                    input = document.createElement('button');
//                    input.name = 'name_id';
                    input.type = 'button';
//                    input.type = 'checkbox';
                    input.className = 'm-2 wbi_remove_tool btn btn-danger btn-delete';
                    input.value = mess['id'];
                    input.dataset.id = mess['id'];
                    var wbi_remove_tool = document.createTextNode('X');
                    input.appendChild(wbi_remove_tool);
                    
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
            $('#'+this.weybillitems_wrapp_id).append(mw);
    //        this.remove_mess(mw);
        },
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
                    input.type = 'checkbox';
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
//                        console.log( m );
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
    if(document.getElementById(order.mess_wrapp_id)) order.init_weybill();
});