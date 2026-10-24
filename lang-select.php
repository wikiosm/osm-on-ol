<?php
/**
 * Options to select image sources.
 */

require_once ( "helpers.php" ) ;

// all wikis
$alllangs=array("wikidata","aa","ab","ace","af","ak","als","am","an","ang","ar","arc","arz","ast","av","ay","az","ba",
"bar","bat-smg","bcl","be","be-x-old","bg","bh","bi","bm","bn","bo","bpy","br","bs","bug","bxr","ca","cbk-zam","cdo",
"ce","ceb","ch","cho","chr","chy","ckb","co","cr","crh","cs","csb","cu","cv","cy","da","de","diq","dsb","dv","dz","ee",
"el","eml","en","en-simple","eo","es","et","eu","ext","fa","ff","fi","fiu-vro","fj","fo","fr","frp","fur","fy","ga",
"gan","gd","gl","glk","gn","got","gu","gv","ha","hak","haw","he","hi","hif","ho","hr","hsb","ht","hu","hy","hz","ia",
"id","ie","ig","ii","ik","ilo","io","is","it","iu","ja","jbo","jv","ka","kaa","kab","kg","ki","kj","kk","kl","km",
"kn","ko","kr","krc","ks","ksh","ku","kv","kw","ky","la","lad","lb","lbe","lg","li","lij","lmo","ln","lo","lt","lv",
"map-bms","mdf","mg","mh","mhr","mi","mk","ml","mn","mo","mr","ms","mt","mus","mwl","my","myv","mzn","na","nah",
"nap","nds","nds-nl","ne","new","ng","nl","nn","no","nostalgia","nov","nrm","nv","ny","oc","om","or",
"os","pa","pag","pam","pap","pcd","pdc","pi","pih","pl","pms","pnb","pnt","ps","pt","qu","rm","rmy","rn","ro",
"roa-rup","roa-tara","ru","rw","sa","sah","sc","scn","sco","sd","se","sg","sh","si","simple","sk","sl","sm","sn",
"so","sq","sr","srn","ss","st","stq","su","sv","sw","szl","ta","te","tet","tg","th","ti","tk","tl","tlh","tn","to",
"tokipona","tpi","tr","ts","tt","tum","tw","ty","udm","ug","uk","ur","uz","ve","vec","vi","vls","vo","wa","war","wo",
"wuu","xal","xh","yi","yo","za","zea","zh","zh-classical","zh-min-nan","zh-yue","zu");
// Wikipedias with >1000 articles
$alllangs_red=array("wikidata","ace","af","als","am","an","ang","ar","arc","arz","ast","ay","az","bar","bat-smg","bcl","be",
"be-x-old","bg","bh","bn","bo","bpy","br","bs","ca","cbk-zam","ceb","ckb","co","crh","cs","csb","cv","cy","da","de","diq","dv",
"el","eml","en","eo","es","et","eu","ext","fa","fi","fiu-vro","fj","fo","fr","frp","fur","fy","ga","gan","gd","gl","glk","gn",
"gu","gv","hak","haw","he","hi","hif","hr","hsb","ht","hu","hy","ia","id","ie","ilo","io","is","it","ja","jbo","jv","ka","kk",
"km","kn","ko","krc","ksh","ku","kv","kw","ky","la","lad","lb","li","lij","lmo","ln","lt","lv","map-bms","mg","mhr","mi","mk",
"ml","mn","mr","ms","mt","my","myv","mzn","nah","nap","nds","nds-nl","ne","new","nl","nn","no","nov","nrm","nv","oc","os","pa",
"pag","pam","pcd","pdc","pi","pl","pms","pnb","ps","pt","qu","rm","ro","roa-rup","roa-tara","ru","sa","sah","sc","scn","sco",
"se","sg","sh","si","simple","sk","sl","so","sq","sr","stq","su","sv","sw","szl","ta","te","tg","th","tk","tl","to","tr","tt",
"ug","uk","ur","uz","vec","vi","vls","vo","wa","war","wo","wuu","xal","yi","yo","zh","zh-classical","zh-min-nan","zh-yue"); 
// map lang to CLDR files
$cldrLang = array(
	'zh' => 'Zh_hans',
	'zh-classical' => 'Zh_hant',
	'zh-min-nan' => 'Zh_hant',
	'zh-yue' => 'Zh_hant',
	'sr' => 'Sr_ec',
	'kk' => 'Kk_cyrl',
	'tg' => 'Tg_cyrl',
	'wikidata' => 'En',
);

// validate lang and fallback
$uselang = param_get('lang', 'en');
$uselang = is_string($uselang) ? strtolower($uselang) : 'en';
if (!in_array($uselang, $alllangs, true)) {
	$uselang = 'en';
}

// CLDR loading with fallback
$langCapital = isset($cldrLang[$uselang]) ? $cldrLang[$uselang]	: ucfirst($uselang);
$langFile = __DIR__ . "/cldr/LanguageNames{$langCapital}.php";
if (!is_file($langFile)) {
	$langFile = __DIR__ . "/cldr/LanguageNamesEn.php";
}
$names = array();
include $langFile;

echo "\n<option value=''>"
	. htmlspecialchars(translate('all', $uselang), ENT_QUOTES, 'UTF-8')
	. "</option>\n";
foreach ($alllangs_red as $la) {
	$select = ($la === $uselang) ? ' selected' : '';
	$name = empty($names[$la]) ? $la : $names[$la] . "\t(" . $la . ")";
	echo '<option value="' . htmlspecialchars($la, ENT_QUOTES, 'UTF-8') . '"' . $select . '>'
		. htmlspecialchars($name, ENT_QUOTES, 'UTF-8')
		. "</option>\n";
}