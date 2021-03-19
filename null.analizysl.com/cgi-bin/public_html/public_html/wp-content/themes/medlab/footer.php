<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package medlab
 */

?>

	</div><!-- #content -->

	<footer>
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-sm-12">
            <div class="logo">
              <p class="p1">«SWISSLAB»</p>
              <p class="p2">Независимая лаборатория</p>
            </div>
            <div class="license">
              <p>Лицензия ООО СП «SwissLab» лицензия #01034 от 6.12.2018 </p>
            </div>
            <!--	div class="apps">
              <p>Наше приложение:</p>
              <a href="#"><img src="img/iapp.svg" alt=""> </a>
              <a href="#"><img src="img/google.svg" alt=""> </a>
            </div	-->
          </div>
          <div class="col-lg-2 col-sm-12">
            <div class="menu">
              <a class="active" href="http://analizy.uz/index.php">Главная</a>
              <a href="http://analizysl.com/index.php">Анализы и цены</a>
              <a href="http://analizy.uz/index.php#depos-map1">Где и как сдать анализ</a>
              <a href="http://analizy.uz/prepare.php">Подготовка к анализам</a>
              <a href="http://analizy.uz/doctors.php">Врачам</a>
              <a href="http://analizy.uz/company.php">Организациям</a>
            </div>
          </div>
          <div class="col-lg-2 col-sm-12">
            <div class="menu2">
              <a href="#" class="lk"><img src="http://analizy.uz/img/fuser.svg" alt=""> Личный кабинет</a>
              <a href="https://t.me/AnalizySwissLabbot" class="lk"><img src="http://analizy.uz/img/ftg.svg" style="margin-right: 6px;" alt="">Telegram-bot</a>
              <br>
              <div class="soc">
                <a href="https://www.facebook.com/AnalizySwissLab"><img src="http://analizy.uz/img/ffb.svg" alt=""></a>
                <a href="https://www.instagram.com/analizyswisslab"><img src="http://analizy.uz/img/finst.svg" alt=""></a>
                <a href="https://t.me/t.me/swisslabuz"><img src="http://analizy.uz/img/ftg.svg" alt=""></a>
              </div>
            </div>
          </div>
          <div class="col-lg-2 col-sm-12">
            <div class="menu2">
              <a href="mailto:info@analizy.uz" class="email">info@analizy.uz</a>
              <p class="address">Наш адрес: <br><span>г.Ташкент, Алмазарский р-н, ул Широк, д.100</span></p>
            </div>
          </div>
          <div class="col-lg-3 col-sm-12">
            <div class="callme">
              <a href="tel:+998712076556" class="phone"><img src="img/fphone.svg" alt=""> +998 71 207 6556</a>
              <a href="#" onclick="$('#callme').modal('show');return false;" class="btn btn-invis">Заказать звонок</a>
              <a href="#" class="up">Наверх</a>
            </div>
          </div>
        </div>
        <div class="finfo">
          <p class="p1">ИМЕЮТСЯ ПРОТИВОПОКАЗАНИЯ, ПРОКОНСУЛЬТИРУЙТЕСЬ СО СПЕЦИАЛИСТОМ</p>
          <p class="p2">Все права защищены. 2019 - 2020. SWISSLAB</p>
        </div>
      </div>
    </footer>
</div><!-- #page -->
<script>
var ajax_url = '<?=admin_url("admin-ajax.php") ?>';
</script>

<?php wp_footer(); ?>

</body>
</html>
