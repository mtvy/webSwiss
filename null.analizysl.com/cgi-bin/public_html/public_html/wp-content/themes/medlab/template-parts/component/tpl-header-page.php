<?php

/* 
 * tpl-header-page
 */

?>
<div id="page" class="siter">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'medlab' ); ?></a>

	<header id="masthead" class="site-header container">
        <div class="top">
            
            <div class="container">
          <div class="logo">
            <a href="#"><img src="http://analizy.uz/img/logo.svg" alt=""></a>
          </div>
          <div class="soc">
            <a href="https://www.facebook.com/AnalizySwissLab"><img src="http://analizy.uz/img/fb.svg" alt=""></a>
            <a href="https://www.instagram.com/analizyswisslab"><img src="http://analizy.uz/img/inst.svg" alt=""></a>
            <a href="https://t.me/swisslabuz"><img src="http://analizy.uz/img/tg.svg" alt=""></a>
          </div>
          <div class="location">
          <p><img src="http://analizy.uz/loc.svg" alt=""><span>г.Ташкент, Алмазарский р-н, ул Широк, д.100</span></p>
          </div>
          <div class="contacts">
            <a href="tel:+998712076556"><img src="http://hanalizy.uz/img/phone.svg" alt=""><span>+998 71 207 6556</span></a>
            <a href="mailto:info@analizy.uz" class="email">info@analizy.uz</a>
          </div>

          <a href="#" class="mobile-button"><img src="analizy.uz/img/menu.svg" alt=""></a>
          <div class="bottom-mobile">
            <a href="#" class="mobile-button-close"><img src="http://analizy.uz/img/close.svg" alt=""></a>

            
            <div class="cont">
              <a href="tel:+998712076556" class="phone"><img src="http://analizy.uz/img/phone.svg" alt=""> +998 71 207 6556</a>
              <a href="mailto:info@analizy.uz" class="email">info@analizy.uz</a>
            </div>
            <div class="address">
              <p>Наш адрес:</p>
              <p class="adr">г.Ташкент, Алмазарский р-н, ул Широк, д.100</p>
            </div>
            <div class="soc">
              <a href="https://www.facebook.com/AnalizySwissLab"><img src="http://analizy.uz/img/ffb.svg" alt=""></a>
              <a href="https://www.instagram.com/analizyswisslab"><img src="http://analizy.uz/img/finst.svg" alt=""></a>
              <a href="https://t.me/AnalizySwissLabbot"><img src="http://analizy.uz/img/ftg.svg" alt=""></a>
            </div>
            <div class="user_button_lk">
              <a class="lk_button" href="#"><img src="http://analizy.uz/img/user.svg" alt=""><span>Личный кабинет</span></a>
            </div>
          </div>
        </div>

    <style>
@charset "UTF-8";
@import url("https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i&display=swap&subset=cyrillic");
body header div.top {
  padding: 15px 0;
}

body header div.top .container {
  position: relative;
  display: flex;
  justify-content: space-between;
}

body header div.top .container .bottom-mobile {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: #29344C;
  z-index: 1;
  padding: 28px;
}

body header div.top .container .bottom-mobile .mobile-button-close {
  position: absolute;
  top: 27px;
  right: 34px;
}

body header div.top .container .bottom-mobile .p1 {
  text-align: center;
  font-weight: 600;
  font-size: 18px;
  line-height: 23px;
  text-align: center;
  text-transform: uppercase;
  color: #FFFFFF;
}

body header div.top .container .bottom-mobile .menu {
  margin-top: 31px;
  margin-top: 31px;
}

body header div.top .container .bottom-mobile .menu a {
  font-weight: 600;
  font-size: 14px;
  line-height: 18px;
  text-align: center;
  color: #FFFFFF;
  display: block;
  margin-bottom: 12px;
}

body header div.top .container .bottom-mobile .cont {
  margin-top: 39px;
  text-align: center;
}

body header div.top .container .bottom-mobile .cont a.phone {
  display: block;
  font-weight: 600;
  font-size: 20px;
  line-height: 25px;
  color: #FFFFFF;
}

body header div.top .container .bottom-mobile .cont a.email {
  font-size: 14px;
  line-height: 18px;
  text-decoration-line: underline;
  color: #FFFFFF;
}

body header div.top .container .bottom-mobile .address {
  text-align: center;
  margin-top: 23px;
}

body header div.top .container .bottom-mobile .address p {
  font-size: 13px;
  line-height: 16px;
  color: #FFFFFF;
  margin-bottom: 3px;
}

body header div.top .container .bottom-mobile .address a {
  font-weight: 600;
  font-size: 15px;
  line-height: 19px;
  text-align: center;
  color: #FFFFFF;
}

body header div.top .container .bottom-mobile .user_button_lk {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100vw;
}

body header div.top .container .bottom-mobile .user_button_lk a {
  background: #D85252;
  display: block;
  padding: 21px;
  text-align: center;
}

body header div.top .container .bottom-mobile .user_button_lk a span {
  font-weight: 600;
  font-size: 14px;
  line-height: 18px;
  color: #FFFFFF;
  margin-left: 5px;
}

body header div.top .container .soc, body header div.top .container .location {
  display: flex;
}

body header div.top .container .soc a, body header div.top .container .location a {
  margin: auto;
}

body header div.top .container .soc a:not(:last-child), body header div.top .container .location a:not(:last-child) {
  margin-right: 30px;
}

body header div.top .container .location a span {
  font-size: 13px;
  line-height: 16px;
  color: #9A9A9A;
  margin-left: 5px;
  vertical-align: middle;
}

body header div.top .container .location a:hover {
  text-decoration: unset;
}

body header div.top .container .contacts a:first-child {
  margin-top: 14px;
}

body header div.top .container .contacts a {
  display: block;
  font-weight: 600;
  font-size: 20px;
  line-height: 25px;
  color: #252525;
}

body header div.top .container .contacts a span {
  margin-left: 8px;
}

body header div.top .container .contacts a.email {
  text-align: right;
  font-size: 14px;
  line-height: 18px;
  text-decoration-line: underline;
  color: #959595;
}

body header div.bottom {
  background: #1A68B0;
}

body header div.bottom .container {
  display: flex;
}

body header div.bottom div.nav a {
  font-weight: 600;
  font-size: 14px;
  line-height: 18px;
  text-align: center;
  color: #FFFFFF;
  padding: 21px 26px;
}

body header div.bottom div.nav a.active, body header div.bottom div.nav a:hover {
  background: #D85252;
  text-decoration: unset;
}

body header div.bottom .user_button_lk {
  display: flex;
  background: #D85252;
  margin-left: auto;
  padding: 0 31px;
}

body header div.bottom .user_button_lk .lk_button {
  font-weight: 600;
  font-size: 14px;
  line-height: 18px;
  color: #FFFFFF;
  margin: auto;
}

body header div.bottom .user_button_lk .lk_button span {
  vertical-align: middle;
  margin-left: 6px;
}

body #slider1 {
  margin-bottom: 0px;
}

body #slider1 .slick-dots {
  top: calc(50% - 42px);
  bottom: unset;
  right: 0px;
  width: 35px;
}

body #slider2, body #slider3, body #slider4 {
  margin-top: -60px;
  margin-bottom: 60px;
}

body #slider2 .slick-dots, body #slider3 .slick-dots, body #slider4 .slick-dots {
  bottom: -60px;
}

body #slider2 li button, body #slider3 li button, body #slider4 li button {
  width: 0;
  height: 0;
}

body #slider2 li.slick-active button, body #slider3 li.slick-active button, body #slider4 li.slick-active button {
  color: white;
  font-size: 17px;
  margin-left: -68px;
  font-family: Source Sans Pro;
  font-style: normal;
  font-weight: bold;
  font-size: 0;
  margin-top: 0;
}

body #slider2 li.slick-active:after, body #slider3 li.slick-active:after, body #slider4 li.slick-active:after {
  content: '-';
  font-size: 0px;
  text-align: center;
  opacity: 1;
  position: absolute;
  width: 20px;
  border: unset;
  top: 4px;
  left: -39px;
}

body #slider3, body #slider4 {
  margin-top: 0px;
}

body section.main-slider .slide {
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
  min-height: 600px;
}

body section.main-slider .slide .container {
  padding-top: 169px;
/*  padding-bottom: 231px;	*/
}

body section.main-slider .slide .container p.p1 {
  font-weight: 600;
  font-size: 50px;
  line-height: 63px;
  text-transform: uppercase;
  color: #FFFFFF;
  width: 720px;
/*  width: 659px;	*/
}

body section.main-slider .slide .container p.p2 {
  font-size: 16px;
  line-height: 138%;
  color: #BCC9E4;
  width: 481px;
}

body section.news-slider .news {
/**	padding: 10px 22px;	*/
/*  padding: 48px 37px;	
  height: 327px;*/
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
  margin: 0 3px;
  background-color: #425884;
}

body section.news-slider .news .p1 {
  font-weight: bold;
  font-size: 18px;
  line-height: 23px;
  text-transform: uppercase;
  color: #FFFFFF;
}

body section.news-slider .news .p2 {
  font-size: 14px;
  line-height: 18px;
  color: #FFFFFF;
  margin-top: 10px;
}

body section.news-slider .news a {
	position: absolute;

	font-weight: bold;
	font-size: 16px;
	line-height: 20px;
	text-decoration-line: none;
/*	bottom: 35px;	*/
	color: #FFFFFF;
	display: block;
 
	border-top-left-radius: 15px;
	border-bottom-right-radius: 15px;
	padding: 8px 26px;
	background-color: #e13e3ec2;
	top: 6%;
	margin-left: 0.26%;
}

/*
body section.news-slider .news:before {
  content: '';
  position: absolute;
  width: 30px;
  height: 2px;
  background: #FFFFFF;
  margin-bottom: 9px;
  margin-top: -10px;
}
*/

body section.issue-agreement {
  padding-bottom: 80px;
}

body section.issue-agreement .p0 {
  display: none;
}

body section.issue-agreement .agreement {
  max-width: 532px;
  width: 100%;
  margin: auto;
}

body section.issue-agreement .agreement .p1 {
  font-weight: bold;
  font-size: 18px;
  line-height: 23px;
  color: #000000;
  margin-top: 16px;
}

body section.issue-agreement .agreement .p2 {
  font-size: 16px;
  line-height: 140%;
  color: #6A6A6A;
  margin-top: -10px;
}

body section.issue-agreement .agreement a {
  font-size: 16px;
  line-height: 140%;
  text-decoration: underline;
}

body section.depos-map1 {
  position: relative;
}

body section.depos-map1 .anatomy {
  padding: 57px 78px;
  width: 606px;
  background: #FFFFFF;
  position: relative;
  z-index: 1;
}

body section.depos-map1 .anatomy .p1 {
  font-weight: bold;
  font-size: 30px;
  line-height: 38px;
  color: #000000;
  margin-bottom: 3px;
}

body section.depos-map1 .anatomy .p2 {
  font-size: 16px;
  line-height: 140%;
  color: #6A6A6A;
}

body section.depos-map2 {
  position: relative;
}

body section.depos-map2 .anatomy {
  padding: 57px 78px;
  width: 606px;
  background: #FFFFFF;
  margin-left: auto;
  position: relative;
  z-index: 1;
}

body section.depos-map2 .anatomy .p1 {
  font-weight: bold;
  font-size: 30px;
  line-height: 38px;
  color: #000000;
  margin-bottom: 3px;
}

body section.depos-map2 .anatomy .p2 {
  font-size: 16px;
  line-height: 140%;
  color: #6A6A6A;
}

body section.preorder {
  background: #1A68B0;
}

body section.preorder .container::before {
  content: '';
  width: 55px;
  height: 5px;
  border: 1px solid #FFFFFF;
  background-color: #FFFFFF;
  position: absolute;
  top: 0;
}

body section.preorder .container:after {
  background-size: cover;
  background-position: center;
  content: '.';
  font-size: 0;
  background-image: url(/img/info.svg);
  position: absolute;
  bottom: 18px;
  width: 28px;
  height: 28px;
}

body section.preorder .container {
  position: relative;
  display: flex;
  justify-content: space-between;
  padding: 28px 0;
}

body section.preorder .container .btn-red {
  margin: 0;
}

body section.preorder .container p {
  font-weight: 600;
  font-size: 20px;
  line-height: 25px;
  text-transform: uppercase;
  color: #FFFFFF;
}

body section.logotips {
  padding-top: 51px;
  padding-bottom: 120px;
  background: #F9F9F9;
}

body section.logotips .logo {
  display: flex;
  height: 109px;
}

body section.logotips .logo img {
  margin: auto;
}

body section.service {
  padding: 57px 0;
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
  background-image: url(/img/main-info.png);
}

body section.service .info {
  width: 173px;
  margin-bottom: 62px;
}

body section.service .info .p3 {
  font-weight: 600;
  font-size: 16px;
  line-height: 20px;
  color: #FFFFFF;
}

body section.service .p1 {
  font-weight: bold;
  font-size: 30px;
  line-height: 38px;
  color: #FFFFFF;
  margin-bottom: 5px;
  max-width: 462px;
}

body section.service .p2 {
  font-size: 16px;
  line-height: 138%;
  color: #BCC9E4;
  margin-bottom: 49px;
  max-width: 462px;
}

body section.service .row {
  max-width: 788px;
}

body section.personal {
  padding-top: 67px;
  padding-bottom: 46px;
}

body section.personal .container {
  padding: 0;
}

body section.personal .p1 {
  font-weight: bold;
  font-size: 30px;
  line-height: 38px;
  color: #000000;
  max-width: 573px;
}

body section.personal .slick-track {
  padding: 15px 0;
}

body section.personal .person {
  width: 200px;
  background: #FFFFFF;
  margin-top: 100px;
}

body section.personal .person .setting {
  width: 190px;
  height: 190px;
  margin: auto;
  overflow: hidden;
  box-shadow: 0px 8px 12px rgba(0, 0, 0, 0.08);
}

body section.personal .person .setting img {
  width: 100%;
  height: auto;
}

body section.personal .person .info {
  padding-bottom: 1px;
  display: none;
}

body section.personal .person .info .name {
  font-weight: bold;
  font-size: 18px;
  line-height: 20px;
  text-align: center;
  color: #000000;
  width: 156px;
  margin: auto;
  margin-top: 16px;
  margin-bottom: 7px;
}

body section.personal .person .info .title {
  font-weight: 600;
  font-size: 14px;
  line-height: 18px;
  text-align: center;
  color: #848484;
}

body section.personal .person.first {
  margin-top: 0;
}

body section.personal .person.first .setting {
  box-shadow: 0px 8px 12px rgba(0, 0, 0, 0.08);
  height: unset;
  width: 220px;
}

body section.personal .person.first .info {
  display: block;
}

body section.personal .person.second, body section.personal .person.third {
  margin-top: 55px;
}

body section.personal .person.second .setting, body section.personal .person.third .setting {
  box-shadow: 0px 8px 12px rgba(0, 0, 0, 0.08);
  height: unset;
  width: 200px;
}

body section.personal .person.second .image, body section.personal .person.third .image {
  height: 190px;
  overflow: hidden;
}

body section.personal .person.second .info, body section.personal .person.third .info {
  display: block;
}

body section.personal .person.second .info .name, body section.personal .person.third .info .name {
  font-weight: bold;
  font-size: 14px;
  line-height: 109.7%;
}

body section.personal .person.second .info .title, body section.personal .person.third .info .title {
  font-weight: 600;
  font-size: 12px;
  line-height: 15px;
}

body footer {
  padding-top: 55px;
  padding-bottom: 25px;
  background: #29344C;
}

body footer .logo .p1 {
  font-weight: 900;
  font-size: 30px;
  line-height: 38px;
  text-transform: uppercase;
  color: #FFFFFF;
  margin-bottom: 0;
}

body footer .logo .p2 {
  font-size: 14px;
  line-height: 18px;
  color: #B2BFDA;
}

body footer .license {
  font-weight: 600;
  font-size: 12px;
  line-height: 15px;
  color: #B2BFDA;
  width: 167px;
}

body footer .apps {
  font-size: 13px;
  line-height: 16px;
  color: #B2BFDA;
}

body footer .apps p {
  margin-bottom: 7px;
}

body footer .menu a {
  display: block;
  font-size: 14px;
  line-height: 18px;
  text-align: left;
  color: #B2BFDA;
  margin-bottom: 13px;
}

body footer .menu a.active {
  color: #D85252;
}

body footer .menu2 .lk {
  font-weight: 600;
  font-size: 14px;
  line-height: 18px;
  color: #B2BFDA;
  margin-bottom: 13px;
  display: block;
}

body footer .menu2 .lk img {
  margin-top: -4px;
  margin-right: 4px;
}

body footer .menu2 .tg {
  font-size: 13px;
  line-height: 10px;
  color: #B2BFDA;
}

body footer .menu2 .tg u {
  font-size: 14px;
  line-height: 16px;
  text-decoration-line: underline;
  color: #FFFFFF;
}

body footer .menu2 .soc {
  display: flex;
  justify-content: space-between;
  width: 100px;
  margin-top: 22px;
}

body footer .menu2 .email {
  font-size: 14px;
  line-height: 18px;
  text-decoration-line: underline;
  color: #FFFFFF;
  margin-top: -2px;
  display: block;
}

body footer .menu2 .address {
  font-size: 13px;
  line-height: 16px;
  color: #B2BFDA;
  margin-top: 20px;
}

body footer .menu2 .address span {
  color: #FFFFFF;
}

body footer .callme {
  text-align: right;
}

body footer .callme .phone {
  font-weight: 600;
  font-size: 20px;
  line-height: 25px;
  color: #FFFFFF;
  margin-top: -7px;
  display: block;
}

body footer .callme .btn-invis {
  margin-top: 20px;
}

body footer .callme .up {
  display: block;
  font-weight: bold;
  font-size: 14px;
  line-height: 18px;
  text-decoration-line: underline;
  color: #FFFFFF;
  margin-top: 40px;
}

body footer .finfo {
  justify-content: space-between;
  display: flex;
  margin-top: 20px;
}

body footer .finfo .p1 {
  font-weight: 600;
  font-size: 20px;
  line-height: 140%;
  color: #FFFFFF;
  margin: auto 0;
}

body footer .finfo .p2 {
  font-size: 12px;
  line-height: 15px;
  color: #B2BFDA;
  margin: auto 0;
}

body section.prepare-article {
  padding-top: 80px;
}

body section.prepare-article .group {
  margin-bottom: 69px;
}

body section.prepare-article .group .title {
  font-weight: bold;
  font-size: 30px;
  line-height: 38px;
  color: #000000;
  max-width: 638px;
}

body section.prepare-article .group .block {
  margin-top: 36px;
  margin-bottom: 36px;
}

body section.prepare-article .group .block .b-title {
  font-weight: bold;
  font-size: 20px;
  line-height: 25px;
  color: #000000;
}

body section.prepare-article .group .block .b-title span {
  font-weight: bold;
  font-size: 35px;
  line-height: 44px;
  color: #1A68B0;
  vertical-align: sub;
  margin-right: 13px;
}

body section.prepare-article .group .block .b-title img {
  margin-top: -5px;
  margin-right: 15px;
}

body section.prepare-article .group .block .text {
  font-size: 18px;
  line-height: 140%;
  color: #6A6A6A;
}

body section.prepare-article .group .block ul.p-text {
  list-style: none;
}

body section.prepare-article .group .block ul.p-text li {
  position: relative;
  font-weight: 600;
  font-size: 16px;
  line-height: 140%;
  color: #6A6A6A;
  margin-bottom: 11px;
}

body section.prepare-article .group .block ul.p-text li p {
  margin-bottom: 0;
  font-weight: 600;
  font-size: 14px;
  line-height: 130%;
  color: #6A6A6A;
}

body section.prepare-article .group .block ul.p-text li::before {
  content: "";
  height: 10px;
  width: 10px;
  position: absolute;
  top: 8px;
  background-color: red;
  border-radius: 10px;
  margin-left: -25px;
  background: #1A68B0;
}

body section.about-us {
  padding-top: 70px;
  padding-bottom: 40px;
}

body section.about-us .p1 {
  font-weight: bold;
  font-size: 30px;
  line-height: 38px;
  color: #000000;
  max-width: 573px;
  margin-bottom: 63px;
}

body section.about-us .about {
  max-width: 491px;
  display: flex;
  margin-bottom: 78px;
  position: relative;
}

body section.about-us .about .icon {
  width: 100px;
  height: 100px;
  display: flex;
  background: #1A68B0;
  box-shadow: 0px 2px 32px rgba(0, 0, 0, 0.07);
  border-radius: 50px;
}

body section.about-us .about .icon img {
  margin: auto;
}

body section.about-us .about .text {
  margin: auto 0;
  width: calc(100% - 120px);
  margin-left: 15px;
}

body section.about-us .about .text p {
  font-size: 16px;
  line-height: 20px;
  color: #2E2E2E;
}

body section.about-us .about .text p.static {
  margin: 0;
  position: absolute;
  font-size: 126px;
  line-height: 126px;
  text-transform: lowercase;
  color: rgba(26, 104, 176, 0.05);
  top: -20px;
}

body section.lab-service {
  background: #1A68B0;
  padding: 68px 0;
}

body section.lab-service .container {
  display: flex;
  justify-content: space-between;
}

body section.lab-service .container .left {
  max-width: 573px;
}

body section.lab-service .container .left .p1 {
  font-weight: bold;
  font-size: 30px;
  line-height: 38px;
  color: #FFFFFF;
}

body section.lab-service .container .left .p2 {
  font-size: 16px;
  line-height: 138%;
  color: #FFFFFF;
}

body section.lab-service .container .right {
  margin: -68px 0;
  margin-top: -97px;
}

body section.lab-service .container .right .image {
  width: 529px;
  height: 509px;
  background-image: url("/img/canva-doctor-working-with-tablet--MACk8LCDhhs 1.png");
}

body section.doct-callme {
  background-image: url(/img/doctors-g.png);
  background-size: cover;
  padding: 85px 0;
}

body section.doct-callme .container .p1 {
  font-weight: bold;
  font-size: 30px;
  line-height: 38px;
  text-align: center;
  color: #FFFFFF;
  max-width: 573px;
  margin: auto;
}

body section.doct-callme .container .p2 {
  font-size: 16px;
  line-height: 20px;
  text-align: center;
  color: #FFFFFF;
  max-width: 573px;
  margin: auto;
}

body section.doct-callme .container .cont {
  justify-content: space-between;
  display: flex;
  max-width: 523px;
  width: 100%;
  margin: auto;
}

body section.doct-steps {
  padding: 100px 0;
}

body section.doct-steps .step {
  width: 343px;
}

body section.doct-steps .step .image {
  display: flex;
}

body section.doct-steps .step .image img {
  margin: auto;
}

body section.doct-steps .step .p1 {
  font-weight: bold;
  font-size: 20px;
  line-height: 25px;
  text-align: center;
  color: #000000;
  margin-bottom: 6px;
}

body section.doct-steps .step .p2 {
  font-size: 14px;
  line-height: 18px;
  text-align: center;
  color: #6A6A6A;
  margin-bottom: 0;
}

body section.lab-service2 {
  background: #1A68B0;
  padding: 68px 0;
}

body section.lab-service2 .container {
  display: flex;
  justify-content: space-between;
}

body section.lab-service2 .container .left {
  max-width: 573px;
}

body section.lab-service2 .container .left .p1 {
  font-weight: bold;
  font-size: 30px;
  line-height: 38px;
  color: #FFFFFF;
}

body section.lab-service2 .container .left .p2 {
  font-size: 16px;
  line-height: 138%;
  color: #FFFFFF;
}

body section.lab-service2 .container .right {
  margin: -68px 0;
  margin-top: -97px;
}

body section.lab-service2 .container .right p {
  position: absolute;
  font-weight: 900;
  font-size: 143px;
  line-height: 180px;
  letter-spacing: 0.1em;
  color: rgba(255, 255, 255, 0.05);
  margin-top: 140px;
}

body section.lab-service2 .container .right .image {
  width: 508px;
  height: 501px;
  background-image: url("/img/Doctor-Isolation_v2-b 1.png");
}

body section.lab-service3 {
  background: #FFFFFF;
  padding: 68px 0;
}

body section.lab-service3 .container {
  display: flex;
  justify-content: space-between;
}

body section.lab-service3 .container .left {
  max-width: 573px;
}

body section.lab-service3 .container .left .p1 {
  font-weight: bold;
  font-size: 30px;
  line-height: 38px;
  color: #000000;
}

body section.lab-service3 .container .left .p2 {
  font-size: 16px;
  line-height: 138%;
  color: #6A6A6A;
}

body section.lab-service3 .container .right {
  margin: -68px 0;
  margin-top: 0;
}

body section.lab-service3 .container .right .image {
  width: 606px;
  height: 465px;
  background-image: url("/img/Group 1.png");
}

body section.welcome {
  padding-top: 89px;
}

body section.welcome .container {
  display: flex;
}

body section.welcome .left .btn-red {
  margin-top: 17px;
}

body section.welcome .left .p1 {
  font-weight: bold;
  font-size: 30px;
  line-height: 38px;
  color: #000000;
  max-width: 573px;
  margin-bottom: 18px;
}

body section.welcome .left .p2 {
  font-size: 16px;
  line-height: 140%;
  color: #2E2E2E;
  max-width: 529px;
  margin-bottom: 27px;
}

body section.welcome .left .p3 {
  font-weight: bold;
  font-size: 16px;
  line-height: 140%;
  text-transform: uppercase;
  color: #000000;
  margin-bottom: 13px;
}

body section.welcome .left .how {
  display: flex;
  max-width: 381px;
  margin-bottom: 15px;
}

body section.welcome .left .how img {
  margin: auto 0;
}

body section.welcome .left .how p {
  font-weight: 600;
  font-size: 16px;
  line-height: 140%;
  color: #2E2E2E;
  margin: auto 0;
  margin-left: 13px;
}

body section.welcome .left .how p u {
  color: #D85252;
}

body section.welcome .right {
  width: 480px;
  margin-left: -40px;
}

body section.welcome .right .image {
  width: 600px;
  height: 545px;
  background-image: url("/img/Group 2.png");
  background-size: cover;
}

body section.dominate {
  background: #1A68B0;
  padding: 76px 0;
}

body section.dominate .container .p1 {
  font-weight: bold;
  font-size: 30px;
  line-height: 38px;
  color: #FFFFFF;
  width: 546px;
  margin-bottom: 66px;
}

body section.dominate .container .info {
  margin-bottom: 73px;
}

body section.dominate .container .info .head .image {
  margin-bottom: 10px;
}

body section.dominate .container .info .head p.title {
  font-weight: bold;
  font-size: 20px;
  line-height: 25px;
  color: #FFFFFF;
}

body section.dominate .container .info .body p {
  font-size: 16px;
  line-height: 140%;
  color: #FFFFFF;
}

body section.dominate .container .info.full {
  background: #2673BA;
  border-radius: 0px;
  padding: 32px 27px;
  display: flex;
  justify-content: space-between;
  padding-bottom: 0;
}

body section.dominate .container .info.full .head .title1 {
  font-weight: bold;
  font-size: 20px;
  line-height: 140%;
  text-transform: uppercase;
  color: #FFFFFF;
  width: 488px;
}

body section.comp-learn {
  position: relative;
}

body section.comp-learn .container .content {
  padding: 82px 0;
  position: relative;
  z-index: 1;
  width: 50%;
  margin-left: auto;
  padding-left: 70px;
}

body section.comp-learn .container .content .p1 {
  font-weight: bold;
  font-size: 30px;
  line-height: 38px;
  color: #FFFFFF;
}

body section.comp-learn .container .content .p2 {
  font-size: 16px;
  line-height: 20px;
  color: #FFFFFF;
  margin-bottom: 44px;
}

body section.comp-learn .container .content .group {
  display: flex;
  margin-bottom: 30px;
}

body section.comp-learn .container .content .group .num {
  font-weight: 900;
  font-size: 30px;
  line-height: 38px;
  color: #FFFFFF;
  margin: auto 0;
  margin-right: 17px;
  margin-top: 0;
}

body section.comp-learn .container .content .group .text {
  font-size: 14px;
  line-height: 18px;
  color: #FFFFFF;
  margin: auto 0;
  margin-top: 5px;
}

body section.comp-learn .left {
  position: absolute;
  width: 51vw;
  height: 100%;
  left: 0px;
  top: 0;
  background-image: url(/img/comp-l.png);
  background-size: cover;
  background-position: center top;
}

body section.comp-learn .right {
  position: absolute;
  background: #29344C;
  width: 51vw;
  height: 100%;
  top: 0;
  right: 0;
}

body section.doct-portal {
  padding: 73px 0;
}

body section.doct-portal .p1 {
  font-weight: bold;
  font-size: 30px;
  line-height: 38px;
  color: #000000;
  max-width: 521px;
  margin-bottom: 58px;
}

body section.doct-portal .protal:before {
  content: '';
  position: absolute;
  top: 0;
  width: 50px;
  height: 3px;
  background: #1A68B0;
}

body section.doct-portal .protal {
  padding-top: 10px;
  position: relative;
}

body section.doct-portal .protal .p2 {
  font-weight: bold;
  font-size: 18px;
  line-height: 23px;
  text-transform: uppercase;
  color: #000000;
  margin-bottom: 6px;
}

body section.doct-portal .protal .p3 {
  font-size: 14px;
  line-height: 18px;
  color: #6A6A6A;
}

body section.comp-learn2 {
  position: relative;
}

body section.comp-learn2 .container .content {
  padding: 82px 0;
  position: relative;
  z-index: 1;
  width: 50%;
  margin-right: auto;
  padding-right: 70px;
}

body section.comp-learn2 .container .content .p1 {
  font-weight: bold;
  font-size: 30px;
  line-height: 38px;
  color: #FFFFFF;
}

body section.comp-learn2 .container .content .p2 {
  font-size: 16px;
  line-height: 20px;
  color: #FFFFFF;
  margin-bottom: 44px;
}

body section.comp-learn2 .container .content .group {
  display: flex;
  margin-bottom: 30px;
}

body section.comp-learn2 .container .content .group .num {
  font-weight: 900;
  font-size: 30px;
  line-height: 38px;
  color: #FFFFFF;
  margin: auto 0;
  margin-right: 17px;
  margin-top: 0;
}

body section.comp-learn2 .container .content .group .text {
  font-size: 14px;
  line-height: 18px;
  color: #FFFFFF;
  margin: auto 0;
  margin-top: 5px;
}

body section.comp-learn2 .left {
  position: absolute;
  width: 51vw;
  height: 100%;
  right: 0px;
  top: 0;
  background-image: url(/img/comp-2.png);
  background-size: cover;
  background-position: center top;
}

body section.comp-learn2 .right {
  position: absolute;
  background: #1A68B0;
  width: 51vw;
  height: 100%;
  top: 0;
  left: 0;
}

body section.comp-learn3 {
  position: relative;
}

body section.comp-learn3 .container .content {
  padding: 82px 0;
  position: relative;
  z-index: 1;
  width: 50%;
  margin-left: auto;
  padding-left: 70px;
}

body section.comp-learn3 .container .content .p1 {
  font-weight: bold;
  font-size: 30px;
  line-height: 38px;
  color: #FFFFFF;
  margin-bottom: 44px;
}

body section.comp-learn3 .container .content .p2 {
  font-size: 16px;
  line-height: 20px;
  color: #FFFFFF;
}

body section.comp-learn3 .container .content .group {
  display: flex;
  margin-bottom: 30px;
}

body section.comp-learn3 .container .content .group .num {
  font-weight: 900;
  font-size: 30px;
  line-height: 38px;
  color: #FFFFFF;
  margin: auto 0;
  margin-right: 17px;
  margin-top: 0;
}

body section.comp-learn3 .container .content .group .text {
  font-size: 14px;
  line-height: 18px;
  color: #FFFFFF;
  margin: auto 0;
  margin-top: 5px;
}

body section.comp-learn3 .left {
  position: absolute;
  width: 51vw;
  height: 100%;
  left: 0px;
  top: 0;
  background-image: url(/img/main-first.png);
  background-size: cover;
  background-position: center top;
}

body section.comp-learn3 .right {
  position: absolute;
  background: #29344C;
  width: 51vw;
  height: 100%;
  top: 0;
  right: 0;
}

body section.lab-service4 {
  background: #FFFFFF;
  padding: 68px 0;
}

body section.lab-service4 .container {
  display: flex;
  justify-content: space-between;
}

body section.lab-service4 .container .left {
  max-width: 573px;
}

body section.lab-service4 .container .left .p1 {
  font-weight: bold;
  font-size: 30px;
  line-height: 38px;
  color: #000000;
}

body section.lab-service4 .container .left .p2 {
  font-size: 16px;
  line-height: 138%;
  color: #6A6A6A;
  margin-bottom: 45px;
}

body section.lab-service4 .container .left .serv4 {
  margin-bottom: 38px;
}

body section.lab-service4 .container .left .serv4 .num {
  font-style: normal;
  font-weight: 900;
  font-size: 30px;
  line-height: 140%;
  color: #1A68B0;
  margin-bottom: 6px;
}

body section.lab-service4 .container .left .serv4 .p3 {
  font-size: 14px;
  line-height: 140%;
  color: #29344C;
}

body section.lab-service4 .container .right {
  margin: -68px 0;
  margin-top: 0;
}

body section.lab-service4 .container .right .image {
  width: 480px;
  height: 560px;
  background-image: url("/img/girl-cashback-480x560 1.png");
}

body {
  font-family: Source Sans Pro;
  font-style: normal;
  overflow-x: hidden;
}

@media (min-width: 1200px) {
  body .container {
    max-width: 1170px;
  }
}

body .mobile-button {
  position: absolute;
  top: 10px;
  right: 30px;
  display: none;
}

body .slick-slide {
  outline: none;
}

body .btn.btn-white {
  background: #FFFFFF;
  font-weight: 600;
  font-size: 16px;
  line-height: 15px;
  padding: 18px 28px;
  margin-top: 28px;
  border-radius: 0px;
}

body .btn.btn-red {
  background: #D85252;
  box-shadow: 0px 0px 16px rgba(216, 82, 82, 0.86);
  font-weight: 600;
  font-size: 16px;
  line-height: 20px;
  color: white;
  padding: 18px 28px;
  margin-top: 28px;
  border-radius: 0px;
}

body .btn.btn-invis {
  border: 2px solid #FFFFFF;
  box-sizing: border-box;
  font-weight: 600;
  font-size: 16px;
  line-height: 20px;
  color: white;
  padding: 18px 28px;
  border-radius: 0px;
}

body .slick-dots li {
  margin-bottom: 10px;
}

body .slick-dots li button:before {
  opacity: 1;
  color: unset;
  font-size: 0;
}

body .slick-dots li button:after {
  font-family: 'slick';
  font-size: 12px;
  line-height: 12px;
  position: absolute;
  top: 0;
  left: 0;
  width: 10px;
  height: 10px;
  content: '•';
  text-align: center;
  opacity: 1;
  color: #DBDBDB;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

body .slick-dots li.slick-active button {
  color: white;
  font-size: 17px;
  margin-left: -68px;
  font-family: Source Sans Pro;
  font-style: normal;
  font-weight: bold;
  font-size: 18px;
  margin-top: -5.5px;
}

body .slick-dots li.slick-active button:after {
  color: #1A68B0;
}

body .slick-dots li.slick-active button:before {
  opacity: 1;
  color: unset;
  border: 1px solid #1a68b0;
  box-sizing: border-box;
  height: 34px;
  width: 34px;
  top: -12px;
  right: 12px;
  left: -11px;
  border-radius: 20px;
}

body .slick-dots li.slick-active:after {
  content: '-';
  font-size: 0px;
  text-align: center;
  opacity: 1;
  position: absolute;
  width: 20px;
  border: 1px solid #818896;
  top: 4px;
  left: -39px;
}

body .logo-text2 p {
  font-weight: 900;
  font-size: 143px;
  line-height: 180px;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: rgba(255, 255, 255, 0.05);
  position: absolute;
  right: 0;
}

body .logo-text3 p {
  font-weight: 900;
  font-size: 143px;
  line-height: 180px;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: rgba(26, 104, 176, 0.05);
  position: absolute;
  left: 50%;
  margin-top: -70px;
}

body .logo-text p {
  font-weight: 900;
  font-size: 143px;
  line-height: 180px;
  letter-spacing: 0.1em;
  color: rgba(26, 104, 176, 0.05);
  text-align: right;
}

body .group-list::-webkit-scrollbar {
  width: 2px;
  background: #E8E8E8;
}

body .group-list::-webkit-scrollbar-track {
  background: #E8E8E8;
}

body .group-list::-webkit-scrollbar-thumb {
  background: #1A68B0;
}

body .group-list::-webkit-scrollbar-thumb:hover {
  background: #555;
}

body .group-list {
  height: 385px;
  overflow-y: scroll;
  margin-top: 36px;
}

body .group-list .item.active {
  background: #1A68B0;
}

body .group-list .item.active .short-info {
  display: none;
}

body .group-list .item.active .full-info {
  display: block;
}

body .group-list .item.active .p3 {
  color: #FFFFFF;
  margin-bottom: 20px;
}

body .group-list .item {
  background: #FFFFFF;
  border: 1px solid #E8E8E8;
  box-sizing: border-box;
  width: 385px;
  position: relative;
  padding: 19px;
  cursor: pointer;
}

body .group-list .item .short-info {
  display: block;
}

body .group-list .item .full-info {
  display: none;
}

body .group-list .item .p3 {
  font-weight: bold;
  font-size: 16px;
  line-height: 140%;
  color: #6A6A6A;
  margin: 0;
}

body .group-list .item .plus {
  position: absolute;
  top: 22px;
  right: 22px;
  background: white;
}

body .group-list .item .minus {
  position: absolute;
  top: 22px;
  right: 22px;
}

body .group-list .item .address {
  font-weight: 600;
  font-size: 14px;
  line-height: 140%;
  color: #FFFFFF;
  margin-bottom: 10px;
}

body .group-list .item .how {
  font-size: 12px;
  line-height: 140%;
  color: #FFFFFF;
  margin-bottom: 0;
}

body .group-list .item .cars {
  font-weight: 600;
  font-size: 14px;
  line-height: 140%;
  color: #FFFFFF;
  margin-bottom: 0;
}

body .group-list .item .clock {
  font-weight: 600;
  font-size: 14px;
  line-height: 125%;
  color: #FFFFFF;
  margin-left: 20px;
  margin-bottom: 13px;
}

body .group-list .item .clock:before {
  background-size: cover;
  background-position: center;
  content: '.';
  font-size: 0;
  width: 15px;
  height: 14px;
  margin-left: -20px;
  background-image: url(/img/clock.svg);
  position: absolute;
  margin-top: 1px;
}

body .group-list .item .bus {
  font-weight: 600;
  font-size: 14px;
  line-height: 125%;
  color: #FFFFFF;
  margin-left: 20px;
  margin-bottom: 13px;
}

body .group-list .item .bus:before {
  background-size: cover;
  background-position: center;
  content: '.';
  font-size: 0;
  width: 15px;
  height: 14px;
  margin-left: -20px;
  background-image: url(/img/bus.svg);
  position: absolute;
  margin-top: 1px;
}

body .maps {
  position: absolute;
  top: 0;
  z-index: 0;
  left: 0;
  height: 100%;
  width: 100%;
}

body .balooneP {
  padding-bottom: 16px;
}

body .balooneP .baloone {
  background: #1A68B0;
  box-shadow: 0px 4px 9px rgba(0, 0, 0, 0.22);
  border-radius: 5px;
  width: 231px;
  padding: 12px 15px;
  position: relative;
}

body .balooneP .baloone p {
  font-weight: 600;
  font-size: 13px;
  line-height: 140%;
  color: #FFFFFF;
  margin: 0;
}

body .balooneP .baloone:after {
  content: '';
  background-image: url("/img/b-bot.svg");
  position: absolute;
  bottom: -15px;
  width: 22px;
  height: 18px;
  background-size: cover;
  left: calc(50% - 11px);
}

body .balooneP .baloone:before {
  content: '';
  width: 30px;
  height: 3px;
  background: #FFFFFF;
  position: absolute;
  top: 0;
}

.ymaps-2-1-75-balloon__layout, .ymaps-2-1-75-balloon__content {
  background: unset !important;
}

.ymaps-2-1-75-balloon {
  box-shadow: unset !important;
}

.ymaps-2-1-75-balloon__tail {
  display: none !important;
}

.ymaps-2-1-75-balloon {
  margin-top: -25px;
  margin-left: -67px;
}

.slick-arrow {
  z-index: -1;
  display: none;
  position: absolute;
  top: 0;
  left: 0;
}

.slick-dots li button {
  width: 35px;
}

/*-------------------*/

body header div.top .container .soc p, body header div.top .container .location p {
  margin: auto;
}

body header div.top .container .soc p:not(:last-child), body header div.top .container .location p:not(:last-child) {
  margin-right: 30px;
}

body header div.top .container .location p span {
  font-size: 13px;
  line-height: 16px;
  color: #9A9A9A;
  margin-left: 5px;
  vertical-align: middle;
}

body header div.top .container .location p:hover {
  text-decoration: unset;
}

body header div.top .container .bottom-mobile .address .adr {
  font-weight: 600;
  font-size: 15px;
  line-height: 24px;
  text-align: center;
  color: #FFFFFF;
}
.caption-news{
 height:285px;
 text-align: justify;
 padding: 14px;
 overflow: auto;
 display: -webkit-flex;
 display: flex;
}
.media-custom {
  background: #fff;
  -webkit-transition: .3s all ease;
  -o-transition: .3s all ease;
  transition: .3s all ease;
  -webkit-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
  box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.1);
  margin-bottom: 30px;
}

body header div.top .container .bottom-mobile .menu a .mob-active {
  font-weight: 600;
  font-size: 14px;
  line-height: 18px;
  text-align: center;
  color: #D85252;
  display: block;
  margin-bottom: 12px;
}

.news-left {
	position: absolute;
	width: 2px;
	height: 32px;
	background-color: #D85252;
	top: 44%;
	left: 7%;
	content: '';
	display: block;
	z-index: 3;
	-webkit-transform: rotate(-45deg);
	-moz-transform: rotate(-45deg);
	-ms-transform: rotate(-45deg);
	-o-transform: rotate(-45deg);
	transform: rotate(-45deg);
}

.news-left:after{
	width: 32px;
	height: 2px;
	background-color: #D85252;
	content: '';
	display: block;
	z-index: 3;
	-webkit-transform: rotate(180deg);
	-moz-transform: rotate(180deg);
	-ms-transform: rotate(180deg);
	-o-transform: rotate(180deg);
	transform: rotate(180deg);
}

.news-right {
	position: absolute;
	height: 32px;
	width: 2px;
	background-color: #D85252;
	top: 37.5%;
	right: 7%;
	content: '';
	display: block;
	z-index: 3;
	-webkit-transform: rotate(135deg);
	-moz-transform: rotate(135deg);
	-ms-transform: rotate(135deg);
	-o-transform: rotate(135deg);
	transform: rotate(135deg);
}

.news-right:after{
	width: 32px;
	height: 2px;
	background-color: #D85252;
	content: '';
	display: block;
	z-index: 3;
	-webkit-transform: rotate(0deg);
	-moz-transform: rotate(0deg);
	-ms-transform: rotate(0deg);
	-o-transform: rotate(0deg);
	transform: rotate(0deg);
}
</style>	
        <!-- <div class="row mt-4">
            <div class="site-branding col-12 col-md-12 text-center mt-1">
                <div class="banner-2">
                    <?php 
                        /* $attachment_id = 81;
                        $attachment_id = 177;
                        $size = 'full';
                        $icon = false;
                        $attr = [];
                        echo wp_get_attachment_image( $attachment_id, $size, $icon, $attr ); */
                    ?>
                </div>
            </div>
        </div> -->
            
	</header><!-- #masthead -->
            
    <div class="bottom" style="background: #1A68B0;">
        <div class="container pb-3 pt-3">
            <div class="row nav">
                <div class="col-12 text-center">

                    <nav id="site-navigation" class="main-navigation-top ">
                        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'medlab' ); ?></button>
                        <?php
                        wp_nav_menu( array(
                            'menu_class'=>'nav justify-content-end',
                            'theme_location' => 'menu-1',
                            'menu_id'        => 'primary-menu',
                            'walker'        => new AMWalker(),
                        ) );
                        ?>
                    </nav><!-- #site-navigation -->
                </div>
            </div>
        </div>
    </div>
            
    <div class="container">
        <div class="row justify-content-end">
            <div class="col-12 col-md-4 text-right"><?php
                if ( function_exists('dynamic_sidebar') ){
                    dynamic_sidebar('sbar-right-cart-btn');
                }
            ?></div>
        </div>
    </div>
            
    <div class="container">
        <div class="row ">
            <div class="col-12" id="info_messages_wrap"><?php
                if ( function_exists('showLogInfo') ){
                    showLogInfo();
                }
            ?></div>
        </div>
    </div>

	<div id="content" class="site-content">
