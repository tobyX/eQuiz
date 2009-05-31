<fieldset id="eQuizBoard">
	<legend>{lang}wbb.acp.board.eQuizBoard{/lang}</legend>

	<div class="formElement" id="eQuizBoardDiv">
		<div class="formField">
			<label id="eQuizBoard"><input type="checkbox" name="eQuizBoard" value="1" {if $eQuizBoard}checked="checked" {/if}/> {lang}wbb.acp.board.eQuizBoard{/lang}</label>
		</div>
		<div class="formFieldDesc hidden" id="eQuizBoardHelpMessage">
			{lang}wbb.acp.board.eQuizBoard.description{/lang}
		</div>
	</div>
	<script type="text/javascript">//<![CDATA[
		inlineHelp.register('eQuizBoard');
	//]]></script>
</fieldset>