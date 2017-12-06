<div class="faq">
	<div class="groupLeft show">
		<div></div>
	</div>
	<div class="groupLeft">
		[foreach block=faq]<div class="group">
			<div class="question">{faq.fQuestion}</div>
			<div class="answer">{faq.fAnswer}</div>
		</div>[/foreach]
	</div>
</div>
<style>@import "{C_default_http_local}skins/faq.min.css";</style>
<script type="text/javascript">
$(document).ready(function() {
	$(".faq .show > div").fadeOut(300, function() {
		$(".faq .group").removeClass('open');
		$(".faq .group").eq(0).addClass("open");
		$(".faq .show > div").html($(".faq .group").eq(0).html());
		$(".faq .show > div").fadeIn(300);
	});
	$(".faq .group").click(function() {
		var elem = this;
		$(".faq .show > div").fadeOut(300, function() {
			$(".faq .group").removeClass('open');
			$(elem).addClass("open");
			$(".faq .show > div").html($(elem).html());
			$(".faq .show > div").fadeIn(300);
		});
		return false;
	});
});
</script>