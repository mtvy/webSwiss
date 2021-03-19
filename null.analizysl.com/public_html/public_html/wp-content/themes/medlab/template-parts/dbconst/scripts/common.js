"use strict";
/* 
 *  common
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
jQuery(document).keydown(function(event) {
        // If Control or Command key is pressed and the S key is pressed
        // run save function. 83 is the key code for S.
        if((event.ctrlKey || event.metaKey) && event.which == 83) {
            // Save Function
            event.preventDefault();
            return false;
        }
    }
);
jQuery(window).keydown(function(e) {

      if (e.ctrlKey) {
//          console.log(event.location);
//         if (event.location == 1) console.log('left ctrl');
//         if (event.location == 2) console.log('right ctrl');
      }

});
jQuery(window).ready(function($){
//  $("*").keydown(function(e) {
//
//      if (e.ctrlKey) {
//          console.log(event.location);
//         if (event.location == 1) console.log('left ctrl');
//         if (event.location == 2) console.log('right ctrl');
//      }
//
//  });
});

jQuery(document).ready(function($) {

    /*
     * tabs
     */
    var ctrlkeydown = false;

    $('*').keydown(function(e) { // .nav-tab-button
        
//        $("#status").text("");

        if (e.ctrlKey) {
            if ( e.keyCode == 17) { // 'A' or 'a'
                 ctrlkeydown = true;
            }
        }
//        $("#status").text("This should not work if Ctrl + A is pressed");
    });

    $('*').keyup(function(e) { 
        if (e.ctrlKey) {
            if ( e.keyCode == 17) { // 'A' or 'a'
                 ctrlkeydown = true;
            }
        }
    });

    $('*').keyup(function(e) {
        if (e.ctrlKey) {
            if ( e.keyCode == 65 || e.keyCode == 97) { // 'A' or 'a'
            }
        }
    });
});