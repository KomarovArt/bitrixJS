<?
//подключаем пролог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//устанавливаем заголовок страницы
$APPLICATION->SetTitle("AJAX");


//Подключаем ядро аякс
   CJSCore::Init(array('ajax'));
//создаем переменную для проверки
   $sidAjax = 'testAjax';

if(isset($_REQUEST['ajax_form']) && $_REQUEST['ajax_form'] == $sidAjax){
   //вывод аякс результата. если все ок привет, в ином случае пусто.
   $GLOBALS['APPLICATION']->RestartBuffer();
   echo CUtil::PhpToJSObject(array(
            'RESULT' => 'HELLO',
            'ERROR' => ''
   ));
   die();
}

?>

<!--верстка блоков-->
<div class="group">
   <div id="block"></div >
   <div id="process">wait ... </div >
</div>


<script>
//Если определить window.BXDEBUG = true; То можно вывести в консоль значение и стек: BX.debug('AJAX-DEMOResponse ', data);
   window.BXDEBUG = true;

//демо загрузка, пока получаем дсоны видим надпись вайт...
function DEMOLoad(){
   BX.hide(BX("block"));
   BX.show(BX("process"));
   BX.ajax.loadJSON(
      '<?=$APPLICATION->GetCurPage()?>?ajax_form=<?=$sidAjax?>',
      DEMOResponse
   );
}

//в блок выводим даные data.RESULT
function DEMOResponse (data){
   BX.debug('AJAX-DEMOResponse ', data);
   BX("block").innerHTML = data.RESULT;
   BX.show(BX("block"));
   BX.hide(BX("process"));

   BX.onCustomEvent(
      BX(BX("block")),
      'DEMOUpdate'
   );
}


BX.ready(function(){
   /*
   BX.addCustomEvent(BX("block"), 'DEMOUpdate', function(){
      window.location.href = window.location.href;
   });
   */
  
//При загрузке страницы скрывает верстку с классом блок и процесс
   BX.hide(BX("block"));
   BX.hide(BX("process"));
   

   //при нажатии клик ми вызывает обработчик событий
    BX.bindDelegate(
      document.body, 'click', {className: 'css_ajax' },
      function(e){
         if(!e)
            e = window.event;
         
         DEMOLoad();
         return BX.PreventDefault(e);
      }
   );
   
});

</script>

<div class="css_ajax">click Me</div>
<?
//подключаем эпилог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
