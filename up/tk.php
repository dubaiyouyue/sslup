<?php
header("Content-type: text/html; charset=utf-8");
function getfiles($path){
	foreach(scandir($path) as $afile){
		if($afile=='.'||$afile=='..') continue;
		if(is_dir($path.'/'.$afile)){
			getfiles($path.'/'.$afile);
		}else {
			//echo $path.'/'.$afile.'<br />';
			$list[]=$afile;
			$filetime[]=date('Y-m-d H:i:s', filemtime($path.'/'.$afile)); // 获取文件最近修改日期
		}
	}
	array_multisort($filetime,SORT_DESC,SORT_STRING, $list);//按时间排序
	return $list;
}
//简单的demo,列出当前目录下所有的文件
$list=getfiles('upload');
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title>图库</title>
    <style>*{padding:0px;margin:0px;}
    /*瀑布流层*/
    .waterfall{
        -moz-column-count:4; /* Firefox */
        -webkit-column-count:4; /* Safari 和 Chrome */
        column-count:4;
        -moz-column-gap: 1em;
      -webkit-column-gap: 1em;
      column-gap: 1em;
    }img:hover {
    -webkit-filter: grayscale(100%); /* Chrome, Safari, Opera */
    filter: grayscale(100%);background: green;
}img{padding:5px;}</style><head><body>
<script>
var nnw=document.body.clientWidth;
var nums=parseInt(nnw/200);
document.write('<style>.waterfall{-moz-column-count:'+nums+'; /* Firefox */-webkit-column-count:'+nums+'; /* Safari 和 Chrome */column-count:'+nums+';-moz-column-gap: 1em;-webkit-column-gap: 1em;column-gap: 1em;}</style>') 
</script>

	<div class="waterfall">
	<?php
		foreach($list as $k=>$v){
			echo '<a href="/up/upload/'.$v.'" target="_blank"><img src="/up/upload/'.$v.'" width="190" /></a>';
		}
	?>
	
	</div>
	</body></html>