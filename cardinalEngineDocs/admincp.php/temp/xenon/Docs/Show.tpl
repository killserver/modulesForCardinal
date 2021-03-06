<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/rainbow.min.css" />
<div class="documentation container" >
    <div>
    	<div class="documentation-header col-sm-12" >
    		<a class="button [if {documentation-page}=demos] active [/if {documentation-page}=demos]" title="Не реализовано">Примеры</a>
    		<a href="./?pages=Docs" class="button [if {documentation-page}=engine] active [/if {documentation-page}=engine]" >Ядро</a>
    		<a class="button [if {documentation-page}=modules] active [/if {documentation-page}=modules]" title="Не реализовано">Дополнения</a>
    	</div>
        <div class="row">
            <div class="col-xs-12 search-env">
                <form method="get" action="" enctype="application/x-www-form-urlencoded">
                    <input type="hidden" name="pages" value="Docs">
                    <input type="text" class="form-control" placeholder="{L_"Поиск"}..." name="search" value="{input}">
                    <button type="submit" class="btn-unstyled"><i class="linecons-search"></i></button>
                </form>
                [if {IS_seachMaybe}==1]<div class="col-sm-12 maybe">Возможно Вы искали: <b>{seachMaybe}</b></div>[/if {IS_seachMaybe}==1]
            </div>
        </div>
        <div class="row">
        	<div class="documentation-content col-xs-12 col-md-73 pull-right" >
                [if {path_exists}==1]<ol class="breadcrumb bc-3">
                    [foreach block=path]<li[foreachif {path.is_link}==1]><a href="{path.path}">{path.title}</a>[/foreachif {path.is_link}==1][foreachif {path.is_link}==0] class="active"><strong>{path.title}</strong>[/foreachif {path.is_link}==0]
                    </li>[/foreach]
                </ol>[/if {path_exists}==1]
                {content}
                <div class="row">
                    <div class="pull-right small text-right col-sm-12"><br><br>Документация от: {doc_date}<br>Внешний вид от создателя: <a href="{copyright-documentation}" target="_blank" rel="noindex" class="copy">Dreamlike</a></div>
                    <div class="col-xs-12 remove-cache"><a class="pull-right btn btn-purple btn-xs no-decoration">Удалить кеш файлов</a></div>
                </div>
            </div>
            <div class="documentation-nav col-xs-12 col-md-3 pull-left"><div class="scrollable doc" data-max-height="450">{menus}</div></div>
        </div>
    </div>
</div>
<script>
$(function(){
	$(".documentation-nav ul > li > a:not(.no-find)").click(function(a) {
		 a.preventDefault(); 
		if($(this).attr("href") == undefined) {
			$(this).parent().toggleClass("active");
		} else {
			link = $(this).attr("href")+"&ajax";
			$.post(link, function(data) {
                console.log(data);
				$(".documentation-content").html(data);
				//hljs.ReInit();
                $('pre code').each(function(i, block) {
                    hljs.highlightBlock(block);
                });
			});
		}
	});
    $(".remove-cache a").click(function() {
        jQuery.post("./?pages=Docs&clearCache=1", function(d) {
            toastr.success("Прелоадер успешно деактивирован", "Переключение прелоадера");
        });
        return false;
    });
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
<style>
@import "{C_default_http_local}{D_ADMINCP_DIRECTORY}/temp/{C_skins[admincp]}/Docs/style.css";
</style>