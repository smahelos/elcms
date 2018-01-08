define("ace/snippets/smarty",["require","exports","module"], function(require, exports, module) {
"use strict";

exports.snippetText = "# Latte snippets\n\
snippet fore\n\
	{foreach $${1:array} as $${2:item}}\n\
		${3:}\n\
	{/foreach}\n\
snippet inc\n\
	{layout '${1:layout}.latte'}\n\
	${2}\n\
";
exports.scope = "smarty";

});
