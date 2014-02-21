<!-- BEGIN: main -->
<div class="music-clear"></div>
<div class="music-av-gird">
	<!-- BEGIN: loop -->
	<div class="item" style="width:{COL_WIDTH_PER}%">
		<div class="item-content" style="width:{CONFIG.image_size}px">
			<a href="{ROW.link}"><img class="musicsmalllalbum" src="{ROW.thumb}" alt="{ROW.tname}" width="{CONFIG.image_size}" height="{CONFIG.image_size}"/></a>
			<div class="caption<!-- BEGIN: inset --> inset<!-- END: inset -->">
				<div class="ct">
					<h3><a href="{ROW.link}" title="{ROW.tname}" class="msStrCut" strlength="{CONFIG.str_length}">{ROW.tname}</a></h3>
					<a href="{ROW.link_singers}" title="{ROW.singers}" class="msStrCut" strlength="{CONFIG.str_length}">{ROW.singers}</a>
				</div>
			</div>
		</div>
	</div>
	<!-- BEGIN: break --><div class="music-clear"></div><!-- END: break -->
	<!-- END: loop -->
</div>
<div class="music-clear"></div>
<!-- END: main -->