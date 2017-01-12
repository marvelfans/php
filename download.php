<?php
//// 将要下载的文件内容先都保存到这个数组里边[在这里是个形式，data的获取方式根据场景决定]
$data = array();

//// 文件名
$time = strval(date('YmdHis', time()));
$file_path = 'your_path/your_file_name_' . $time . '.csv';
$file_name = 'your_file_name_' . $time . '.csv';


//// 先将内容写入到文件
if( ($fp = fopen($file_path, 'w')) === false ) {
    var_dump("file open error!");
    exit;
}

//// 如果是输出文件，然后下载到本地乱码，加上这句
// fwrite($fp, chr(0xEF).chr(0xBB).chr(0xBF));
foreach($data as $k => &$v) {
    foreach($v as $kk => $vv) {
        $v[kk] = iconv('utf-8', 'gbk', $vv);
    }
    // php 中对于有引用的foreach循环, 记得unset一下, php这里有个坑
    unset($vv);
    fputcsv($fp, $v);
}
fclose($fp);
//
//// 返回给浏览器
header('Expires:0');
header('Cache-control: private');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Description: File Transfer');
header('Content-Type: application/csv; charset=utf-8');
header('Content-Length:' . filesize($file_path));       // 如果没有设置长度，接口中的其他返回也会输出到文件中
if(strpos($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
    # IE浏览器, 名字要urlencode
    header('Content-Disposition: attachment; filename=' . urlencode($file_name));
} else {
    header('Content-Disposition: attachment; filename=' . $file_name);
}
//
if($len = readfile($file_path)) {
    if(file_exists($file_path)) {
        unlink($file_path);
    }
}



