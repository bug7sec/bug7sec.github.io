<?php
class Search
{
	function engines($dork,$namesave,$option){
		Action::Msg("[INFO] Search Started : ".$dork."\r\n"); 
		$subBing  	= array(" ",);
		$subGoogle 	= array("com","ac","ad","ae","com.af","com.ag","com.ai","al","am","co.ao","com.ar","as","at","com.au","az","ba","com.bd","be","bf","bg","com.bh","bi","bj","com.bn","com.bo","com.br","bs","bt","co.bw","by","com.bz","ca","com.kh","cc","cd","cf","cat","cg","ch","ci","co.ck","cl","cm","cn","com.co","co.cr","com.cu","cv","com.cy","cz","de","dj","dk","dm","com.do","dz","com.ec","ee","com.eg","es","com.et","fi","com.fj","fm","fr","ga","ge","gf","gg","com.gh","com.gi","gl","gm","gp","gr","com.gt","gy","com.hk","hn","hr","ht","hu","co.id","iq","ie","co.il","im","co.in","io","is","it","je","com.jm","jo","co.jp","co.ke","ki","kg","co.kr","com.kw","kz","la","com.lb","com.lc","li","lk","co.ls","lt","lu","lv","com.ly","co.ma","md","me","mg","mk","ml","com.mm","mn","ms","com.mt","mu","mv","mw","com.mx","com.my","co.mz","com.na","ne","com.nf","com.ng","com.ni","nl","no","com.np","nr","nu","co.nz","com.om","com.pk","com.pa","com.pe","com.ph","pl","com.pg","pn","com.pr","ps","pt","com.py","com.qa","ro","rs","ru","rw","com.sa","com.sb","sc","se","com.sg","sh","si","sk","com.sl","sn","sm","so","st","sr","com.sv","td","tg","co.th","com.tj","tk","tl","tm","to","tn","com.tr","tt","com.tw","co.tz","com.ua","co.ug","co.uk","us","com.uy","co.uz","com.vc","co.ve","vg","co.vi","com.vn","vu","ws","co.za","co.zm","co.zw","org","net");
		/**************************************** [SEARCH ENGINE : BING ] ****************************************/
		foreach ($subBing as $key => $domain) {
			$shc 	= 1000;
			for($is=0; $is <= $shc; $is+=10){
				$query = array('q' => $dork ,'go' => 'Submit','qs' => 'n','pq' => $dork,'sc' => '0-9','cc' => $domain ,'sp' => '-1','sk' => '','cvid' => 'A075046B151E4255BE6ABA9FFA67457E','first' => $is ,'FORM' => 'PERE');
				$str = Action::NgeCurl("http://www.bing.com/search?".http_build_query($query));
				$check404 = "/class=\"b_no\"/"; 
				preg_match($check404, $str, $matches404); 
				if(! $matches404[0] ){
					$re = "/a _ctf=\"rdr_T\" href=\"(.*?)\"/"; 
					preg_match_all($re, $str, $matches);
					foreach ($matches[1] as $key => $value) {
						$link = Action::filterDomain($value);
						if( $link ){
							Action::Ngesave("report/temp/result.txt" , $link );
							$LinkCount[] = $link;
							$BingCount[] = $link;
						}
					}
					Action::Msg("[BING ".$domain."] Total w00t : ".count($matches[1])." | Page : ".$is."\r\n"); 
				}else{
						Action::Msg("[BING] ---- No results found [".$is."] ----\r\n");
						$shc=1;
				}				
			}
		}
		Action::Msg("[BING] Total Result : ".count(array_unique($BingCount))."\r\n");
		/*********************************************************************************************************/
		/**************************************** [SEARCH ENGINE : GOOGLE ] **************************************/
		/*foreach ($subGoogle as $key => $domain) {
			$shc 	= 1000;
			for($is=0; $is <= $shc; $is+=10){
				$str = Action::NgeCurl("https://www.google.".$domain."/search?q=x&start=".$is."&sa=N&dpr=1");
				Action::Debug($str);
				$re = "/<div class=\"std uc card-section ucm\">/"; 
				$re2 = "/<h3 class=\"r\"><a href=\"(.*?)\"/"; 
				$re3 = "/action=\"CaptchaRedirect\"/";
				preg_match_all($re, $str, $matches);
				preg_match_all($re2, $str, $matches2);
				preg_match($re3, $str, $matches3);

				if($matches3[0]){
					Action::Msg("[GOOGLE ".$domain."] ---- Captcha Detect ----\r\n");
					$shc=1;
				}else if(! $matches[0][0] ){
					foreach ($matches2[1] as $key => $value) {
						$link = Action::filterDomain($value);
						if( $link ){
							Action::Ngesave("report/temp/result.txt" , $link );
							$LinkCount[] 	= $link;
							$GoogleCount[] 	= $link;
						}
					}
					Action::Msg("[GOOGLE ".$domain."] Total w00t : ".count($matches2[1])." | Page : ".$is."\r\n"); 	
				}else{
					Action::Msg("[GOOGLE ".$domain."] ---- No results found [".$is."] ----\r\n");
					$shc=1;
				}
			}
		}
		Action::Msg("[GOOGLE] Total Result : ".array_unique($GoogleCount)."\r\n");*/ 
		/*********************************************************************************************************/
		Action::Clean($namesave,$option);
		Action::Msg("[INFO] Search ".$dork." | w0ot : ".count($LinkCount)." | Clean : ".count(array_unique($LinkCount))."\r\n");
		Action::Msg("[Output] File result in directory : ".$namesave);
	}
	function runSearch($dork,$option){
		$namesave = "report/result-".time().".txt";
		if( $option ){
			Search::engines($dork,$namesave,true);
		}else{
			Search::engines($dork,$namesave,false);
		}
	}
}