<div class="slider">
	[foreach block=sliderz]<div style="background-image: url('{C_default_http_local}{slide_img}');">
		<b>{slide_title}</b>
		<p>{slide_descr}</p>
	</div>[/foreach]
</div>
<script>
jQuery(document).ready(function(){
	jQuery(".slider").cardinalBxSlider();
});
</script>