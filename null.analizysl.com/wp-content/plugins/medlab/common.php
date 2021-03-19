<?php

/* 
 * 
<	less than	&lt;	&#60;
>	greater than	&gt;	&#62;
&	ampersand	&amp;	&#38;
"	double quotation mark	&quot;	&#34;
'	single quotation mark (apostrophe)	&apos;	&#39;
¢	cent	&cent;	&#162;
£	pound	&pound;	&#163;
¥	yen	&yen;	&#165;
€	euro	&euro;	&#8364;
©	copyright	&copy;	&#169;
®	registered trademark	&reg;	&#174;
 */

 function is_user_role( $role, $user_id = null ) {
    $user = is_numeric( $user_id ) ? get_userdata( $user_id ) : wp_get_current_user();

    if( ! $user )
        return false;

    return in_array( $role, (array) $user->roles );
}