<?php
date_default_timezone_set('UTC');
class Download
{
    private static function responseHeader($filename, $charset = 'UTF-8', $mimeType = 'application/octet-stream')
    {
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: {$mimeType}; charset={$charset}");
        header("Content-Transfer-Encoding: binary");
        header(self::cDispositionHeader($_SERVER["HTTP_USER_AGENT"], $filename, 'attachment', $charset));
    }

    private static function cDispositionHeader($ua, $filename, $cdis = 'attachment', $charset = 'UTF-8')
    {
        if (preg_match("/MSIE/", $ua)) {
            $filename = urlencode($filename);
            $filename = str_replace("+", "%20", $filename);// 替换空格
            $attachmentHeader = "Content-Disposition: {$cdis}; filename=\"{$filename}\"; charset={$charset}";
        }
        else if (preg_match("/Firefox/", $ua)) {
            $attachmentHeader = "Content-Disposition: {$cdis}; filename=\"{$filename}\"; filename*=\"utf8''{$filename}\"";
        }
        else {
            $attachmentHeader = "Content-Disposition: {$cdis}; filename=\"{$filename}\"; charset={$charset}";
        }
        return $attachmentHeader;
    }

    /**
     * 向浏览器发送文件内容
     *
     * @param string $serverPath 文件在服务器上的路径（绝对或者相对路径）
     * @param string $filename 发送给浏览器的文件名（尽可能不要使用中文）
     * @param string $charset 指示文件字符编码
     * @param string $mimeType 指示文件类型
     *
     * @return string
     */
    public static function sendFile($content, $filename, $charset = 'UTF-8', $mimeType = 'application/octet-stream')
    {
        self::responseHeader($filename, $charset, $mimeType);
        header('Pragma: cache');
        header('Cache-Control: public, must-revalidate, max-age=0');
        $filesize = strlen($content);
        header("Content-Length: {$filesize}");
        echo $content;
    }

}

if (!empty($_POST['d'])) {
	Download::sendFile($_POST['d'], date('Y-m-dHis') . '.json');
}
